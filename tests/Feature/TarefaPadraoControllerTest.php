<?php

namespace Tests\Feature;

use App\Services\TarefaPadraoService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
Use Mockery;

class TarefaPadraoControllerTest extends TestCase
{
    use WithoutMiddleware;

    protected $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock do TarefaPadraoService
        $this->serviceMock = Mockery::mock(TarefaPadraoService::class);
        $this->app->instance(TarefaPadraoService::class, $this->serviceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_deve_retornar_lista_de_tarefas()
    {
        $tarefas = [
            (object)['id' => 1, 'titulo' => 'Tarefa A'],
            (object)['id' => 2, 'titulo' => 'Tarefa B'],
        ];

        $this->serviceMock
            ->shouldReceive('getAllPaginated')
            ->once()
            ->andReturn($tarefas);

        $response = $this->getJson('/api/tarefas-padrao');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['titulo' => 'Tarefa A']);
    }
}
