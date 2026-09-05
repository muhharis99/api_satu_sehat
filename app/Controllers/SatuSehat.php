<?php
namespace App\Controllers;

use App\Libraries\SatuSehatClient;
use Throwable;

class SatuSehat extends BaseController
{
    public function token()
    {
        try {
            $payload = $this->jsonInput();
            $client = new SatuSehatClient($this->connectionOverride($payload));
            $result = $client->token();
            $this->appendHistory('TOKEN', 'OAuth2', $result);
            return $this->response->setStatusCode($result['status'] ?: 200)->setJSON($result);
        } catch (Throwable $e) {
            return $this->fail($e);
        }
    }

    public function request()
    {
        try {
            $payload = $this->jsonInput();
            $method = strtoupper(trim((string) ($payload['method'] ?? 'GET')));
            $resource = trim((string) ($payload['resource'] ?? ''));
            $id = trim((string) ($payload['id'] ?? ''));
            $token = trim((string) ($payload['token'] ?? ''));
            $query = $this->parseQuery((string) ($payload['query'] ?? ''));
            $body = null;

            if (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
                $rawBody = trim((string) ($payload['body'] ?? ''));
                if ($rawBody !== '') {
                    $body = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
                }
            }

            $client = new SatuSehatClient($this->connectionOverride($payload));
            $result = $client->request($method, $resource, $id !== '' ? $id : null, $query, $body, $token);
            $this->appendHistory($method, $resource . ($id !== '' ? '/' . $id : ''), $result);
            return $this->response->setStatusCode($result['status'] ?: 200)->setJSON($result);
        } catch (Throwable $e) {
            return $this->fail($e);
        }
    }

    public function history()
    {
        $file = WRITEPATH . 'satusehat/history.jsonl';
        if (! is_file($file)) {
            return $this->response->setJSON(['items' => []]);
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        $items = [];
        foreach (array_slice(array_reverse($lines), 0, 25) as $line) {
            $row = json_decode($line, true);
            if (is_array($row)) {
                $items[] = $row;
            }
        }
        return $this->response->setJSON(['items' => $items]);
    }

    private function jsonInput(): array
    {
        $data = $this->request->getJSON(true);
        return is_array($data) ? $data : [];
    }

    private function connectionOverride(array $payload): array
    {
        return [
            'environment' => $payload['environment'] ?? null,
            'organization_id' => $payload['organization_id'] ?? null,
            'client_id' => $payload['client_id'] ?? null,
            'client_secret' => $payload['client_secret'] ?? null,
        ];
    }

    private function parseQuery(string $query): array
    {
        $query = ltrim(trim($query), '?');
        if ($query === '') {
            return [];
        }
        parse_str($query, $parsed);
        return is_array($parsed) ? $parsed : [];
    }

    private function appendHistory(string $method, string $target, array $result): void
    {
        $dir = WRITEPATH . 'satusehat';
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $row = [
            'time' => date(DATE_ATOM),
            'method' => $method,
            'target' => $target,
            'status' => $result['status'] ?? 0,
            'ok' => (bool) ($result['ok'] ?? false),
        ];
        @file_put_contents($dir . '/history.jsonl', json_encode($row, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private function fail(Throwable $e)
    {
        $status = $e instanceof \JsonException ? 422 : 400;
        return $this->response->setStatusCode($status)->setJSON([
            'ok' => false,
            'status' => $status,
            'message' => $e->getMessage(),
        ]);
    }
}
