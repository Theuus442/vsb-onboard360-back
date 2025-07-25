<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Usuario;
use App\Services\UsuarioService;

class UsuarioServiceTest extends TestCase
{
    protected $usuarioMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria o mock do alias UMA ÚNICA VEZ
        if (!class_exists('Mockery_0_App_Models_Usuario')) {
            $this->usuarioMock = Mockery::mock('alias:' . Usuario::class);
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_criar_usuario()
    {
        $dados = [
            'nome' => 'Teste',
            'email' => 'teste@email.com',
            'senha' => '123456',
            'papel' => 'interno',
            'departamento' => 'TI'
        ];

        $this->usuarioMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(fn($arg) => $arg['nome'] === $dados['nome']))
            ->andReturn((object) $dados);

        $service = new UsuarioService();
        $resultado = $service->criar($dados);

        $this->assertEquals($dados['nome'], $resultado->nome);
    }

    public function test_listar_usuarios()
    {
        $usuarios = [
            (object)['id' => 1, 'nome' => 'Usuário 1'],
            (object)['id' => 2, 'nome' => 'Usuário 2'],
        ];

        $this->usuarioMock->shouldReceive('all')
            ->once()
            ->andReturn($usuarios);

        $service = new UsuarioService();
        $resultado = $service->listar();

        $this->assertCount(2, $resultado);
        $this->assertEquals('Usuário 1', $resultado[0]->nome);
    }

    public function test_buscar_usuario_por_id()
    {
        $usuario = (object)[
            'id' => 1,
            'nome' => 'Usuário Teste',
            'email' => 'teste@email.com'
        ];

        $this->usuarioMock->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($usuario);

        $service = new UsuarioService();
        $resultado = $service->buscarPorId(1);

        $this->assertEquals('Usuário Teste', $resultado->nome);
    }

    public function test_atualizar_usuario()
    {
        $dadosAtualizados = ['nome' => 'Nome Atualizado'];

        $mockUsuario = Mockery::mock();
        $mockUsuario->shouldReceive('update')
            ->once()
            ->with($dadosAtualizados)
            ->andReturn(true);  // update retorna true

        $mockUsuario->nome = 'Nome Atualizado'; // Simula alteração do nome

        $this->usuarioMock->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($mockUsuario);

        $service = new UsuarioService();
        $resultado = $service->atualizar(1, $dadosAtualizados);

        $this->assertEquals('Nome Atualizado', $resultado->nome);
    }

    public function test_deletar_usuario()
    {
        // Mock do usuário instanciado
        $mockUsuario = Mockery::mock();
        $mockUsuario->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->usuarioMock->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($mockUsuario);

        $service = new UsuarioService();
        $resultado = $service->deletar(1);

        $this->assertTrue($resultado);
    }
}
