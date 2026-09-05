<?php
namespace App\Controllers;

use App\Libraries\SatuSehatClient;
use App\Libraries\TerminologyService;
use Throwable;

class Terminology extends BaseController
{
    public function search()
    {
        try {
            $payload = $this->request->getJSON(true);
            $payload = is_array($payload) ? $payload : [];
            $type = strtolower(trim((string) ($payload['type'] ?? 'icd10')));
            $query = trim((string) ($payload['q'] ?? ''));
            $limit = (int) ($payload['limit'] ?? 25);

            if ($type === 'kfa') {
                return $this->searchKfa($payload, $query, $limit);
            }

            $service = new TerminologyService();
            $result = $service->search($type, $query, $limit);

            return $this->response->setJSON([
                'ok' => true,
                'type' => $type,
                'query' => $query,
                ...$result,
            ]);
        } catch (Throwable $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function systems()
    {
        $service = new TerminologyService();
        return $this->response->setJSON([
            'ok' => true,
            'systems' => $service->systems() + [
                'kfa' => [
                    'label' => 'KFA',
                    'system' => 'http://sys-ids.kemkes.go.id/kfa',
                ],
            ],
        ]);
    }

    private function searchKfa(array $payload, string $query, int $limit)
    {
        $token = trim((string) ($payload['token'] ?? ''));
        $productType = strtolower(trim((string) ($payload['product_type'] ?? 'farmasi')));
        $client = new SatuSehatClient([
            'environment' => $payload['environment'] ?? null,
            'organization_id' => $payload['organization_id'] ?? null,
            'client_id' => $payload['client_id'] ?? null,
            'client_secret' => $payload['client_secret'] ?? null,
        ]);

        $result = $client->searchKfa($query, $token, $productType, $limit);
        $items = [];
        $body = $result['body'] ?? [];

        if (is_array($body)) {
            if (isset($body['result']) && is_array($body['result'])) {
                $item = $this->normalizeKfa($body['result']);
                if ($item !== null) {
                    $items[] = $item;
                }
            } else {
                $rows = $body['items']['data'] ?? $body['meta']['data'] ?? $body['data'] ?? [];
                if (is_array($rows)) {
                    foreach ($rows as $row) {
                        if (! is_array($row)) {
                            continue;
                        }
                        $item = $this->normalizeKfa($row);
                        if ($item !== null) {
                            $items[] = $item;
                        }
                    }
                }
            }
        }

        $status = (int) ($result['status'] ?? 500);
        return $this->response->setStatusCode($status > 0 ? $status : 500)->setJSON([
            'ok' => (bool) ($result['ok'] ?? false),
            'type' => 'kfa',
            'query' => $query,
            'source' => $productType === 'alkes' ? 'satusehat-kfa-v3-alkes' : 'satusehat-kfa-v2',
            'system' => [
                'label' => 'KFA',
                'system' => 'http://sys-ids.kemkes.go.id/kfa',
            ],
            'items' => $items,
            'status' => $status,
            'url' => $result['url'] ?? null,
            'raw' => ($result['ok'] ?? false) ? null : $body,
        ]);
    }

    private function normalizeKfa(array $row): ?array
    {
        $code = trim((string) ($row['kfa_code'] ?? ''));
        $display = trim((string) ($row['name'] ?? $row['display_name'] ?? ''));
        if ($code === '' || $code === '/' || $display === '') {
            return null;
        }

        return [
            'code' => $code,
            'display' => $display,
            'system' => 'http://sys-ids.kemkes.go.id/kfa',
            'type' => 'kfa',
            'source' => 'satusehat',
            'meta' => [
                'state' => $row['state'] ?? null,
                'active' => $row['active'] ?? null,
                'farmalkes_type' => $row['farmalkes_type']['name'] ?? null,
                'product_template_code' => $row['product_template']['kfa_code'] ?? null,
                'product_template_name' => $row['product_template']['name'] ?? null,
            ],
        ];
    }
}
