<?php

namespace App\Services\Calendly;

use App\Services\Contracts\ApiClientInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ApiCalendly implements ApiClientInterface
{
    /**
     * La instancia del cliente HTTP pre-configurada.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected readonly PendingRequest $httpClient;

    /**
     * Crea una nueva instancia del cliente de API.
     *
     * @param string $baseUrl La URL base de la API.
     * @param string $token El Bearer Token para la autenticación.
     * @param int $timeout El tiempo de espera en segundos para las peticiones.
     */
    public function __construct(
        string $baseUrl,
        string $token,
        int $timeout = 30
    ) {
        // Creamos una instancia de PendingRequest que se reutilizará en todas las llamadas.
        // Esto es mucho más eficiente que llamar a Http::withToken() en cada método.
        $this->httpClient = Http::withToken($token)
            ->baseUrl($baseUrl)
            ->timeout($timeout)
            ->acceptJson()
            ->throw(); // Lanza una excepción si la respuesta no es exitosa (código 2xx).
    }

    public function get(string $endpoint, array $query = []): array
    {
        return $this->httpClient->get($endpoint, $query)->json();
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->httpClient->post($endpoint, $data)->json();
    }

    public function put(string $endpoint, array $data = []): array
    {
        return $this->httpClient->put($endpoint, $data)->json();
    }
    
    public function delete(string $endpoint): array
    {
        return $this->httpClient->delete($endpoint)->json();
    }
}