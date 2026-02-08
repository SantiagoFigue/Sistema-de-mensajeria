<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Message;
use App\Models\ThreadParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ThreadController extends Controller
{
    /**
     * Mostrar listado de threads para el usuario autenticado.
     * Admin puede ver todos los threads, user solo donde participa.
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();

        // Si es admin, obtener todos los threads
        if ($user->isAdmin()) {
            $threads = Thread::with(['creator', 'latestMessage.user', 'participants'])
                ->withCount('messages')
                ->orderBy('updated_at', 'desc')
                ->paginate(15);
        } else {
            // Si es user normal, solo threads donde es participante
            $threads = Thread::whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['creator', 'latestMessage.user', 'participants'])
            ->withCount('messages')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        }

        return response()->json([
            'success' => true,
            'data' => $threads
        ]);
    }

    /**
     * Almacenar un nuevo thread.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'participants' => 'required|array|min:1',
            'participants.*' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = auth('api')->user();

            // Crear thread
            $thread = Thread::create([
                'subject' => $request->subject,
                'created_by' => $user->id,
            ]);

            // Agregar creador como participante
            $participantIds = array_unique(array_merge([$user->id], $request->participants));
            $thread->participants()->attach($participantIds);

            // Crear primer mensaje
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'body' => $request->body,
            ]);

            DB::commit();

            $thread->load(['creator', 'messages.user', 'participants']);

            return response()->json([
                'success' => true,
                'message' => 'Thread created successfully',
                'data' => $thread
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating thread',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar el thread especificado con todos sus mensajes.
     * Admin puede ver cualquier thread, user solo donde participa.
     */
    public function show($id)
    {
        $user = auth('api')->user();

        // Si es admin, obtener cualquier thread
        if ($user->isAdmin()) {
            $thread = Thread::with(['messages.user', 'participants', 'creator'])->find($id);
        } else {
            // Si es user normal, solo threads donde es participante
            $thread = Thread::with(['messages.user', 'participants', 'creator'])
                ->whereHas('participants', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->find($id);
        }

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not found or access denied'
            ], 404);
        }

        // Marcar como leído solo si el usuario es participante
        if (!$user->isAdmin()) {
            ThreadParticipant::where('thread_id', $thread->id)
                ->where('user_id', $user->id)
                ->update(['last_read_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'data' => $thread
        ]);
    }

    /**
     * Eliminar el thread especificado (soft delete).
     * Admin puede eliminar cualquier thread, user solo los que creó.
     */
    public function destroy($id)
    {
        $user = auth('api')->user();

        // Si es admin, puede eliminar cualquier thread
        if ($user->isAdmin()) {
            $thread = Thread::find($id);
        } else {
            // Si es user normal, solo threads que creó
            $thread = Thread::where('created_by', $user->id)->find($id);
        }

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not found or you do not have permission to delete it'
            ], 403);
        }

        $thread->delete();

        return response()->json([
            'success' => true,
            'message' => 'Thread deleted successfully'
        ]);
    }
}
