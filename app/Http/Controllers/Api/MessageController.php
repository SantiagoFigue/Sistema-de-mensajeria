<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Almacenar un nuevo mensaje en un thread.
     */
    public function store(Request $request, $threadId)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('api')->user();

        // Verificar que el usuario es participante del thread
        $thread = Thread::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->find($threadId);

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not found or access denied'
            ], 404);
        }

        // Crear mensaje
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        // Actualizar timestamp del thread
        $thread->touch();

        $message->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ], 201);
    }
}
