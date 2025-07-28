<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Services\ChecklistParceiroService;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ChecklistParceiroControllerTest extends TestCase
{
    use WithoutMiddleware;

    protected $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = Mockery::mock(ChecklistParceiroService::class);

        $this->app->instance(ChecklistParceiroService::class, $this->serviceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_retorna_lista_de_checklists()
    {
        $checklists = [
            (object)[
                'id' => 1,
                'status' => 'pendente',
                'parceiro' => (object)['id' => 10, 'nome' => 'Parceiro A'],
                'tarefaPadrao' => (object)['id' => 100, 'titulo' => 'Tarefa 1']
            ],
            (object)[
                'id' => 2,
                'status' => 'concluido',
                'parceiro' => (object)['id' => 11, 'nome' => 'Parceiro B'],
                'tarefaPadrao' => (object)['id' => 101, 'titulo' => 'Tarefa 2']
            ],
        ];

        $this->serviceMock
            ->shouldReceive('listar')
            ->once()
            ->andReturn($checklists);

        $response = $this->getJson('/api/checklists-parceiro');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'pendente'])
            ->assertJsonFragment(['nome' => 'Parceiro A']);
    }
}
