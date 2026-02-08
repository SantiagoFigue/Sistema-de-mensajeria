<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Thread;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Probar listado de threads del usuario.
     */
    public function test_usuario_puede_ver_sus_threads()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        // Crear thread donde el usuario es participante
        $thread = Thread::factory()->create();
        $thread->participants()->attach($user->id);
        Message::factory()->create(['thread_id' => $thread->id, 'user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/threads');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'subject', 'created_by', 'created_at', 'messages_count', 'creator', 'participants']
                    ]
                ]
            ])
            ->assertJson(['success' => true]);
    }

    /**
     * Probar creación de thread exitosa.
     */
    public function test_usuario_puede_crear_thread()
    {
        $user = User::factory()->create();
        $participant = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/threads', [
                'subject' => 'Test Thread',
                'body' => 'This is a test message',
                'participants' => [$participant->id],
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'subject', 'messages', 'participants']
            ])
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('threads', [
            'subject' => 'Test Thread',
            'created_by' => $user->id
        ]);

        $this->assertDatabaseHas('messages', [
            'body' => 'This is a test message',
            'user_id' => $user->id
        ]);
    }

    /**
     * Probar creación de thread sin autenticación.
     */
    public function test_crear_thread_requiere_autenticacion()
    {
        $response = $this->postJson('/api/threads', [
            'subject' => 'Test Thread',
            'body' => 'Test message',
            'participants' => [1],
        ]);

        $response->assertStatus(401);
    }

    /**
     * Probar validación en creación de thread.
     */
    public function test_crear_thread_valida_datos_requeridos()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/threads', [
                'subject' => '',
                'body' => '',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['subject', 'body', 'participants']);
    }

    /**
     * Probar ver detalles de un thread.
     */
    public function test_participante_puede_ver_detalles_del_thread()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create();
        $thread->participants()->attach($user->id);
        Message::factory()->count(3)->create(['thread_id' => $thread->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/threads/{$thread->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'subject',
                    'messages' => [
                        '*' => ['id', 'body', 'user']
                    ],
                    'participants'
                ]
            ])
            ->assertJson(['success' => true]);
    }

    /**
     * Probar que no participante no puede ver thread.
     */
    public function test_no_participante_no_puede_ver_thread()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/threads/{$thread->id}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Thread not found or access denied'
            ]);
    }

    /**
     * Probar eliminación de thread por creador.
     */
    public function test_creador_puede_eliminar_thread()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create(['created_by' => $user->id]);
        $thread->participants()->attach($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/threads/{$thread->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thread deleted successfully'
            ]);

        $this->assertSoftDeleted('threads', ['id' => $thread->id]);
    }

    /**
     * Probar que no creador no puede eliminar thread.
     */
    public function test_no_creador_no_puede_eliminar_thread()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create(['created_by' => $creator->id]);
        $thread->participants()->attach($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/threads/{$thread->id}");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Thread not found or you do not have permission to delete it'
            ]);
    }

    /**
     * Probar que ver thread marca como leído.
     */
    public function test_ver_thread_marca_como_leido()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $thread = Thread::factory()->create();
        $thread->participants()->attach($user->id, ['last_read_at' => null]);

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/threads/{$thread->id}");

        $this->assertDatabaseHas('thread_participants', [
            'thread_id' => $thread->id,
            'user_id' => $user->id,
        ]);

        $participant = $thread->participants()->where('user_id', $user->id)->first();
        $this->assertNotNull($participant->pivot->last_read_at);
    }

    /**
     * Probar que admin puede ver todos los threads.
     */
    public function test_admin_puede_ver_todos_los_threads()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $token = auth('api')->login($admin);

        // Crear thread donde el admin NO es participante
        $thread = Thread::factory()->create(['created_by' => $user->id]);
        $thread->participants()->attach($user->id);
        Message::factory()->create(['thread_id' => $thread->id, 'user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/threads');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonPath('data.total', 1);
    }

    /**
     * Probar que admin puede ver cualquier thread.
     */
    public function test_admin_puede_ver_cualquier_thread()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $token = auth('api')->login($admin);

        // Crear thread donde admin NO es participante
        $thread = Thread::factory()->create(['created_by' => $user->id]);
        $thread->participants()->attach($user->id);
        Message::factory()->count(2)->create(['thread_id' => $thread->id, 'user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/threads/{$thread->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonPath('data.id', $thread->id);
    }

    /**
     * Probar que admin puede eliminar cualquier thread.
     */
    public function test_admin_puede_eliminar_cualquier_thread()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $token = auth('api')->login($admin);

        // Crear thread creado por otro usuario
        $thread = Thread::factory()->create(['created_by' => $user->id]);
        $thread->participants()->attach($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/threads/{$thread->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thread deleted successfully'
            ]);

        $this->assertSoftDeleted('threads', ['id' => $thread->id]);
    }
}
