<?php
namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $environment = strtolower((string) env('satusehat.environment', 'sandbox'));
        $organizationId = trim((string) env('satusehat.organization_id', ''));
        $clientId = trim((string) env('satusehat.client_id', ''));

        return view('dashboard', [
            'environment' => in_array($environment, ['sandbox', 'production'], true) ? $environment : 'sandbox',
            'organizationId' => $organizationId,
            'credentialReady' => $clientId !== '' && trim((string) env('satusehat.client_secret', '')) !== '',
        ]);
    }

    public function health()
    {
        return $this->response->setJSON([
            'ok' => true,
            'application' => 'API Satu Sehat CI4',
            'time' => date(DATE_ATOM),
        ]);
    }
}
