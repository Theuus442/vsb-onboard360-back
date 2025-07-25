<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Services\UsuarioService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UsuarioControllerTest extends TestCase
{
    use WithFaker, WithoutMiddleware;

    protected $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock do UsuarioService
        $this->serviceMock = Mockery::mock(UsuarioService::class);

        // Injeta o mock no container para o Controller usar
        $this->app->instance(UsuarioService::class, $this->serviceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_retorna_lista_de_usuarios()
    {
        $usuarios = [
            (object)['id' => 1, 'nome' => 'Usuário 1'],
            (object)['id' => 2, 'nome' => 'Usuário 2'],
        ];

        $this->serviceMock
            ->shouldReceive('listar')
            ->once()
            ->andReturn($usuarios);

        $response = $this->getJson('/api/usuarios');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['nome' => 'Usuário 1']);
    }

    public function test_store_cria_usuario_com_sucesso()
    {
        $dados = [
            'nome' => 'João da Silva',
            'email' => 'joao@email.com',
            'senha' => '123456',
            'papel' => 'interno',
            'departamento' => 'TI',
        ];

        $usuarioCriado = (object) $dados;
        $usuarioCriado->id = 1;

        $this->serviceMock
            ->shouldReceive('criar')
            ->once()
            ->with(Mockery::subset($dados))
            ->andReturn($usuarioCriado);

        $response = $this->postJson('/api/usuarios', $dados);

        $response->assertStatus(201)
            ->assertJsonFragment(['nome' => 'João da Silva']);
    }

    public function test_show_retorna_usuario_por_id()
    {
        $usuario = (object)[
            'id' => 1,
            'nome' => 'Usuário Teste',
            'email' => 'teste@email.com'
        ];

        $this->serviceMock
            ->shouldReceive('buscarPorId')
            ->once()
            ->with(1)
            ->andReturn($usuario);

        $response = $this->getJson('/api/usuarios/1');

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => 'teste@email.com']);
    }
}
