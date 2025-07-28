<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\DocumentoService;
use Mockery;

class DocumentoControllerTest extends TestCase
{
    protected $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceMock = Mockery::mock(DocumentoService::class);
        $this->app->instance(DocumentoService::class, $this->serviceMock);
    }

    public function test_index_retorna_lista_de_documentos()
    {
        $documentos = [
            (object) [
                'id' => 1,
                'nome' => 'Documento 1',
                'status' => 'ativo',
                'arquivo' => 'documento1.pdf',
            ],
            (object) [
                'id' => 2,
                'nome' => 'Documento 2',
                'status' => 'inativo',
                'arquivo' => 'documento2.pdf',
            ],
        ];

        $this->serviceMock
            ->shouldReceive('listar')
            ->once()
            ->andReturn($documentos);

        $response = $this->getJson('/api/documentos');

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => 'Documento 1']);
    }
}
