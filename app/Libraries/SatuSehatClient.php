<?php
namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use RuntimeException;

class SatuSehatClient
{
    private string $environment;
    private string $oauthBase;
    private string $fhirBase;
    private string $kfaBase;
    private string $kfaAlkesBase;
    private string $organizationId;
    private string $clientId;
    private string $clientSecret;
    private CURLRequest $http;

    public function __construct(?array $override = null)
    {
        $override ??= [];
        $environment = strtolower((string) ($override['environment'] ?? env('satusehat.environment', 'sandbox')));
        $this->environment = in_array($environment, ['sandbox', 'production'], true) ? $environment : 'sandbox';

        $official = $this->environment === 'production'
            ? [
                'oauth' => 'https://api-satusehat.kemkes.go.id/oauth2/v1',
                'fhir' => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1',
                'kfa' => 'https://api-satusehat.kemkes.go.id/kfa-v2',
                'kfa_alkes' => 'https://api-satusehat.kemkes.go.id/kfa-v3',
            ]
            : [
                'oauth' => 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1',
                'fhir' => 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1',
                'kfa' => 'https://api-satusehat-stg.dto.kemkes.go.id/kfa-v2',
                'kfa_alkes' => 'https://api-satusehat-stg.dto.kemkes.go.id/kfa-v3',
            ];

        $this->oauthBase = rtrim((string) ($override['oauth_base'] ?? env('satusehat.oauth_base', '')), '/') ?: $official['oauth'];
        $this->fhirBase = rtrim((string) ($override['fhir_base'] ?? env('satusehat.fhir_base', '')), '/') ?: $official['fhir'];
        $this->kfaBase = rtrim((string) ($override['kfa_base'] ?? env('satusehat.kfa_base', '')), '/') ?: $official['kfa'];
        $this->kfaAlkesBase = rtrim((string) ($override['kfa_alkes_base'] ?? env('satusehat.kfa_alkes_base', '')), '/') ?: $official['kfa_alkes'];
        $this->organizationId = trim((string) ($override['organization_id'] ?? env('satusehat.organization_id', '')));
        $this->clientId = trim((string) ($override['client_id'] ?? env('satusehat.client_id', '')));
        $this->clientSecret = trim((string) ($override['client_secret'] ?? env('satusehat.client_secret', '')));
        $this->http = service('curlrequest', ['timeout' => 45, 'http_errors' => false]);
    }

    public function metadata(): array
    {
        return [
            'environment' => $this->environment,
            'organization_id' => $this->organizationId,
            'oauth_base' => $this->oauthBase,
            'fhir_base' => $this->fhirBase,
            'kfa_base' => $this->kfaBase,
            'kfa_alkes_base' => $this->kfaAlkesBase,
            'credential_ready' => $this->clientId !== '' && $this->clientSecret !== '',
        ];
    }

    public function token(): array
    {
        if ($this->clientId === '' || $this->clientSecret === '') {
            throw new RuntimeException('Client ID dan Client Secret belum diisi.');
        }

        $response = $this->http->post($this->oauthBase . '/accesstoken', [
            'query' => ['grant_type' => 'client_credentials'],
            'headers' => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ]);

        return $this->normalizeResponse($response->getStatusCode(), $response->getBody(), $response->getHeaders());
    }

    public function request(string $method, string $resource, ?string $id, array $query, ?array $body, string $token): array
    {
        $method = strtoupper($method);
        if (! in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            throw new RuntimeException('HTTP method tidak didukung.');
        }
        if (! preg_match('/^[A-Z][A-Za-z0-9]+$/', $resource)) {
            throw new RuntimeException('Nama resource FHIR tidak valid.');
        }
        if ($id !== null && $id !== '' && ! preg_match('/^[A-Za-z0-9.\-]{1,128}$/', $id)) {
            throw new RuntimeException('FHIR resource ID tidak valid.');
        }
        if ($token === '') {
            throw new RuntimeException('Access Token belum tersedia. Ambil token terlebih dahulu.');
        }

        $url = $this->fhirBase . '/' . $resource . (($id !== null && $id !== '') ? '/' . rawurlencode($id) : '');
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/fhir+json, application/json',
                'Content-Type' => 'application/fhir+json',
            ],
            'query' => $query,
        ];
        if ($body !== null && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            $options['json'] = $body;
        }

        $response = $this->http->request($method, $url, $options);
        return $this->normalizeResponse($response->getStatusCode(), $response->getBody(), $response->getHeaders(), $url);
    }

    public function searchKfa(string $keyword, string $token, string $productType = 'farmasi', int $size = 25): array
    {
        $keyword = trim($keyword);
        if ($keyword === '') {
            throw new RuntimeException('Kata pencarian KFA belum diisi.');
        }
        if ($token === '') {
            throw new RuntimeException('Access Token diperlukan untuk pencarian KFA resmi.');
        }

        $productType = strtolower(trim($productType));
        if (! in_array($productType, ['farmasi', 'alkes'], true)) {
            $productType = 'farmasi';
        }

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];

        if ($productType === 'alkes') {
            $url = $this->kfaAlkesBase . '/alkes/products';
            $body = [
                'page' => 1,
                'size' => max(1, min($size, 100)),
                'state' => 'valid',
                'active' => true,
            ];
            if (preg_match('/^\d{8}$/', $keyword)) {
                $body['kfa_code'] = $keyword;
            } else {
                $body['search'] = $keyword;
            }

            $response = $this->http->post($url, [
                'headers' => $headers + ['Content-Type' => 'application/json'],
                'json' => $body,
            ]);

            return $this->normalizeResponse($response->getStatusCode(), $response->getBody(), $response->getHeaders(), $url);
        }

        if (preg_match('/^\d{8}$/', $keyword)) {
            $url = $this->kfaBase . '/products';
            $response = $this->http->get($url, [
                'headers' => $headers,
                'query' => [
                    'identifier' => 'kfa',
                    'code' => $keyword,
                ],
            ]);

            return $this->normalizeResponse($response->getStatusCode(), $response->getBody(), $response->getHeaders(), $url);
        }

        $url = $this->kfaBase . '/products/all';
        $response = $this->http->get($url, [
            'headers' => $headers,
            'query' => [
                'page' => 1,
                'size' => max(1, min($size, 100)),
                'product_type' => 'farmasi',
                'keyword' => $keyword,
            ],
        ]);

        return $this->normalizeResponse($response->getStatusCode(), $response->getBody(), $response->getHeaders(), $url);
    }

    private function normalizeResponse(int $status, string $body, array $headers, ?string $url = null): array
    {
        $decoded = json_decode($body, true);
        return [
            'ok' => $status >= 200 && $status < 300,
            'status' => $status,
            'url' => $url,
            'body' => is_array($decoded) ? $decoded : $body,
            'headers' => [
                'content-type' => isset($headers['Content-Type']) ? (string) $headers['Content-Type']->getValueLine() : null,
            ],
        ];
    }
}
