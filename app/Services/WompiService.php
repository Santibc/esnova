<?php

namespace App\Services;

use App\Models\ConfiguracionPasarela;
use App\Models\Compra;
use App\Models\TransaccionPago;
use Illuminate\Support\Facades\Log;

class WompiService
{
    protected $config;
    protected $baseUrl;

    public function __construct()
    {
        $this->config = ConfiguracionPasarela::obtenerConfiguracionActiva('wompi');
        $this->baseUrl = $this->config && $this->config->modo_prueba 
            ? 'https://sandbox.wompi.co/v1' 
            : 'https://production.wompi.co/v1';
    }

    /**
     * Generar firma de integridad para el checkout
     */
    public function generarFirmaIntegridad($referencia, $montoEnCentavos, $moneda = 'COP')
    {
        if (!$this->config) {
            throw new \Exception('Configuración de Wompi no encontrada');
        }

        $integrityKey = $this->config->configuracion_adicional['integrity_key'] ?? null;
        
        if (!$integrityKey) {
            throw new \Exception('Llave de integridad no configurada');
        }

        // Concatenar: referencia + monto + moneda + integrity_key
        $cadena = $referencia . $montoEnCentavos . $moneda . $integrityKey;
        
        // Generar hash SHA256
        return hash('sha256', $cadena);
    }

    /**
     * Generar datos para el formulario de Web Checkout
     */
    public function generarDatosCheckout(Compra $compra, TransaccionPago $transaccion)
    {
        if (!$this->config) {
            throw new \Exception('Configuración de Wompi no encontrada');
        }

        $montoEnCentavos = intval($compra->total * 100);
        
        // Generar firma de integridad
        $firma = $this->generarFirmaIntegridad(
            $transaccion->referencia_transaccion,
            $montoEnCentavos,
            'COP'
        );

        // URL de redirección después del pago
        $redirectUrl = route('tienda.pago.confirmacion', [
            'slug' => $compra->empresa->slug,
            'referencia' => $transaccion->referencia_transaccion
        ]);

        return [
            'public_key' => $this->config->public_key,
            'currency' => 'COP',
            'amount_in_cents' => $montoEnCentavos,
            'reference' => $transaccion->referencia_transaccion,
            'signature_integrity' => $firma,
            'redirect_url' => $redirectUrl,
            'customer_email' => $compra->email_cliente,
            'customer_full_name' => $compra->nombre_cliente,
            'customer_phone_number' => $compra->telefono_cliente,
            'shipping_address' => $compra->direccion_envio,
            'shipping_city' => $compra->ciudad->nombre,
            'shipping_phone_number' => $compra->telefono_cliente,
            'shipping_region' => $compra->ciudad->departamento->nombre,
            'action_url' => 'https://checkout.wompi.co/p/'
        ];
    }

    /**
     * Verificar firma del webhook
     */
    public function verificarFirmaWebhook($payload, $firmaRecibida)
    {
        if (!$this->config || !$this->config->event_key) {
            return false;
        }

        $firmaEsperada = hash_hmac('sha256', $payload, $this->config->event_key);
        return hash_equals($firmaEsperada, $firmaRecibida);
    }

    /**
     * Consultar estado de transacción
     */
    public function consultarTransaccion($transaccionId)
    {
        if (!$this->config) {
            throw new \Exception('Configuración de Wompi no encontrada');
        }

        try {
            $url = $this->baseUrl . '/transactions/' . $transaccionId;
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->config->public_key
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                return $data['data'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error consultando transacción Wompi: ' . $e->getMessage());
            return null;
        }
    }
}