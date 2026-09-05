<?php
namespace App\Libraries;

use RuntimeException;

class TerminologyService
{
    private const SYSTEMS = [
        'icd10' => [
            'label' => 'ICD-10 2010',
            'system' => 'http://hl7.org/fhir/sid/icd-10',
        ],
        'snomed' => [
            'label' => 'SNOMED CT',
            'system' => 'http://snomed.info/sct',
        ],
        'loinc' => [
            'label' => 'LOINC',
            'system' => 'http://loinc.org',
        ],
        'kptl' => [
            'label' => 'KPTL',
            'system' => 'http://terminology.kemkes.go.id/CodeSystem/kptl',
        ],
    ];

    private const STARTER = [
        'icd10' => [
            ['code' => 'J40', 'display' => 'Bronchitis, not specified as acute or chronic'],
            ['code' => 'A15.0', 'display' => 'Tuberculosis of lung, confirmed by sputum microscopy with or without culture'],
            ['code' => 'A41.9', 'display' => 'Sepsis, unspecified'],
            ['code' => 'C47.0', 'display' => 'Malignant neoplasm, peripheral nerves of head, face and neck'],
        ],
        'snomed' => [
            ['code' => '21522001', 'display' => 'Abdominal pain (finding)'],
            ['code' => '170968001', 'display' => 'Prognosis good (finding)'],
            ['code' => '450847001', 'display' => 'Responds to pain (finding)'],
            ['code' => '698608004', 'display' => 'Hand washing education (procedure)'],
            ['code' => '108252007', 'display' => 'Laboratory procedure'],
            ['code' => '363679005', 'display' => 'Imaging'],
            ['code' => '792805006', 'display' => 'Fasting'],
            ['code' => '409073007', 'display' => 'Education'],
            ['code' => '302551006', 'display' => 'Entire Thorax'],
            ['code' => '24484000', 'display' => 'Severe'],
        ],
        'loinc' => [
            ['code' => '34534-8', 'display' => '12 lead EKG panel'],
            ['code' => '8867-4', 'display' => 'Heart rate'],
            ['code' => '8480-6', 'display' => 'Systolic blood pressure'],
            ['code' => '8462-4', 'display' => 'Diastolic blood pressure'],
            ['code' => '8310-5', 'display' => 'Body temperature'],
            ['code' => '9279-1', 'display' => 'Respiratory rate'],
            ['code' => '29463-7', 'display' => 'Body weight'],
            ['code' => '8302-2', 'display' => 'Body height'],
            ['code' => '39156-5', 'display' => 'Body mass index (BMI)'],
            ['code' => '59408-5', 'display' => 'Oxygen saturation in Arterial blood by Pulse oximetry'],
        ],
        'kptl' => [],
    ];

    public function systems(): array
    {
        return self::SYSTEMS;
    }

    public function search(string $type, string $query, int $limit = 25): array
    {
        $type = strtolower(trim($type));
        $query = trim($query);
        $limit = max(1, min($limit, 100));

        if (! isset(self::SYSTEMS[$type])) {
            throw new RuntimeException('Jenis terminologi tidak didukung.');
        }
        if ($query === '') {
            throw new RuntimeException('Masukkan kode atau istilah yang ingin dicari.');
        }

        $dataset = $this->datasetFile($type);
        if ($dataset !== null) {
            $items = $this->searchDataset($dataset, $type, $query, $limit);
            return [
                'items' => $items,
                'source' => 'dataset-lokal',
                'dataset' => basename($dataset),
                'system' => self::SYSTEMS[$type],
            ];
        }

        return [
            'items' => $this->searchRows(self::STARTER[$type] ?? [], $type, $query, $limit),
            'source' => 'starter',
            'dataset' => null,
            'system' => self::SYSTEMS[$type],
            'notice' => $type === 'kptl'
                ? 'Dataset KPTL belum dipasang. Letakkan kptl.jsonl, kptl.json, atau kptl.csv di writable/terminology.'
                : 'Hasil memakai starter data. Pasang dataset resmi di writable/terminology untuk pencarian lengkap.',
        ];
    }

    private function datasetFile(string $type): ?string
    {
        $dir = WRITEPATH . 'terminology' . DIRECTORY_SEPARATOR;
        foreach (['jsonl', 'json', 'csv'] as $extension) {
            $file = $dir . $type . '.' . $extension;
            if (is_file($file) && is_readable($file)) {
                return $file;
            }
        }
        return null;
    }

    private function searchDataset(string $file, string $type, string $query, int $limit): array
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($extension === 'jsonl') {
            return $this->searchJsonLines($file, $type, $query, $limit);
        }
        if ($extension === 'json') {
            $decoded = json_decode((string) file_get_contents($file), true);
            if (! is_array($decoded)) {
                throw new RuntimeException('Dataset JSON terminologi tidak valid.');
            }
            $rows = array_is_list($decoded) ? $decoded : ($decoded['items'] ?? $decoded['data'] ?? []);
            return $this->searchRows(is_array($rows) ? $rows : [], $type, $query, $limit);
        }
        return $this->searchCsv($file, $type, $query, $limit);
    }

    private function searchJsonLines(string $file, string $type, string $query, int $limit): array
    {
        $handle = fopen($file, 'rb');
        if ($handle === false) {
            throw new RuntimeException('Dataset terminologi tidak dapat dibaca.');
        }

        $items = [];
        while (($line = fgets($handle)) !== false && count($items) < $limit) {
            $row = json_decode(trim($line), true);
            if (! is_array($row)) {
                continue;
            }
            $normalized = $this->normalizeRow($row, $type);
            if ($normalized !== null && $this->matches($normalized, $query)) {
                $items[] = $normalized;
            }
        }
        fclose($handle);
        return $items;
    }

    private function searchCsv(string $file, string $type, string $query, int $limit): array
    {
        $handle = fopen($file, 'rb');
        if ($handle === false) {
            throw new RuntimeException('Dataset CSV terminologi tidak dapat dibaca.');
        }

        $header = fgetcsv($handle);
        if (! is_array($header)) {
            fclose($handle);
            return [];
        }
        $header = array_map(static fn ($value) => strtolower(trim((string) $value)), $header);
        $items = [];

        while (($values = fgetcsv($handle)) !== false && count($items) < $limit) {
            $row = [];
            foreach ($header as $index => $key) {
                $row[$key] = $values[$index] ?? null;
            }
            $normalized = $this->normalizeRow($row, $type);
            if ($normalized !== null && $this->matches($normalized, $query)) {
                $items[] = $normalized;
            }
        }
        fclose($handle);
        return $items;
    }

    private function searchRows(array $rows, string $type, string $query, int $limit): array
    {
        $items = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $normalized = $this->normalizeRow($row, $type);
            if ($normalized !== null && $this->matches($normalized, $query)) {
                $items[] = $normalized;
            }
            if (count($items) >= $limit) {
                break;
            }
        }
        return $items;
    }

    private function normalizeRow(array $row, string $type): ?array
    {
        $code = trim((string) ($row['code'] ?? $row['kode'] ?? $row['conceptid'] ?? $row['concept_id'] ?? $row['loincnumber'] ?? $row['loinc_num'] ?? ''));
        $display = trim((string) ($row['display'] ?? $row['description'] ?? $row['deskripsi'] ?? $row['term'] ?? $row['name'] ?? $row['longcommonname'] ?? $row['long_common_name'] ?? ''));

        if ($code === '' || $display === '') {
            return null;
        }

        return [
            'code' => $code,
            'display' => $display,
            'system' => (string) ($row['system'] ?? self::SYSTEMS[$type]['system']),
            'type' => $type,
            'source' => 'local',
        ];
    }

    private function matches(array $row, string $query): bool
    {
        $needle = mb_strtolower($query);
        return str_contains(mb_strtolower((string) $row['code']), $needle)
            || str_contains(mb_strtolower((string) $row['display']), $needle);
    }
}
