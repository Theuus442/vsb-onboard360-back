<?php

namespace Tests\Feature;

use App\Services\ParceiroService;
use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class ParceiroControllerTest extends TestCase
{
    use WithoutMiddleware;

    protected $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceMock = Mockery::mock(ParceiroService::class);
        $this->app->instance(ParceiroService::class, $this->serviceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_retorna_lista_de_parceiros()
    {
        $parceiros = [
            (object)['id' => 1, 'nome' => 'Parceiro 1'],
            (object)['id' => 2, 'nome' => 'Parceiro 2'],
        ];

        $this->serviceMock
            ->shouldReceive('listar')
            ->once()
            ->andReturn($parceiros);

        $response = $this->getJson('/api/parceiros');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['nome' => 'Parceiro 1']);
    }


    public function test_show_retorna_parceiro_por_id()
    {
        $parceiro = (object)[
            'id' => 1,
            'nome' => 'Parceiro Y',
            'checklists' => [],
            'documentos' => []
        ];

        $this->serviceMock
            ->shouldReceive('buscarPorId')
            ->once()
            ->with(1)
            ->andReturn($parceiro);

        $response = $this->getJson('/api/parceiros/1');

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => 'Parceiro Y']);
    }
}
