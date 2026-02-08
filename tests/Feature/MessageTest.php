<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Thread;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Probar creación de mensaje en thread.
     */
    public function test_participante_puede_crear_mensaje_en_thread()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create();
        $thread->participants()->attach($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/threads/{$thread->id}/messages", [
                'body' => 'This is a test message',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'body', 'user_id', 'thread_id', 'user']
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);

        $this->assertDatabaseHas('messages', [
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'body' => 'This is a test message'
        ]);
    }

    /**
     * Probar que crear mensaje requiere autenticación.
     */
    public function test_crear_mensaje_requiere_autenticacion()
    {
        $thread = Thread::factory()->create();

        $response = $this->postJson("/api/threads/{$thread->id}/messages", [
            'body' => 'Test message',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Probar validación de body requerido.
     */
    public function test_crear_mensaje_valida_body_requerido()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create();
        $thread->participants()->attach($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/threads/{$thread->id}/messages", [
                'body' => '',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }

    /**
     * Probar que no participante no puede crear mensaje.
     */
    public function test_no_participante_no_puede_crear_mensaje()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create();
        // Usuario NO es participante

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/threads/{$thread->id}/messages", [
                'body' => 'Test message',
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Thread not found or access denied'
            ]);
    }

    /**
     * Probar que crear mensaje actualiza timestamp del thread.
     */
    public function test_crear_mensaje_actualiza_timestamp_del_thread()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create([
            'updated_at' => now()->subHour()
        ]);
        $thread->participants()->attach($user->id);

        $oldTimestamp = $thread->updated_at;

        sleep(1);

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/threads/{$thread->id}/messages", [
                'body' => 'New message',
            ]);

        $thread->refresh();

        $this->assertTrue($thread->updated_at->greaterThan($oldTimestamp));
    }

    /**
     * Probar mensaje en thread inexistente.
     */
    public function test_crear_mensaje_en_thread_inexistente_falla()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/threads/99999/messages", [
                'body' => 'Test message',
            ]);

        $response->assertStatus(404);
    }

    /**
     * Probar que admin puede crear mensaje en cualquier thread.
     */
    public function test_admin_puede_crear_mensaje_en_cualquier_thread()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $token = auth('api')->login($admin);

        // Crear thread donde admin NO es participante
        $thread = Thread::factory()->create(['created_by' => $user->id]);
        $thread->participants()->attach($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson("/api/threads/{$thread->id}/messages", [
                'body' => 'Admin message',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);

        $this->assertDatabaseHas('messages', [
            'thread_id' => $thread->id,
            'user_id' => $admin->id,
            'body' => 'Admin message'
        ]);
    }
}
