<?php
namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Playbook extends BaseController
{
    private array $catalog = [
        'rawat_jalan' => [
            'title' => 'Rawat Jalan',
            'url' => 'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/rme-rawat-jalan/',
            'steps' => [
                ['no' => 1, 'title' => 'Pendaftaran Pasien', 'resources' => ['Patient']],
                ['no' => 2, 'title' => 'Pendaftaran Kunjungan', 'resources' => ['Encounter']],
                ['no' => 3, 'title' => 'Anamnesis', 'resources' => ['Condition', 'FamilyMemberHistory', 'AllergyIntolerance', 'MedicationStatement']],
                ['no' => 4, 'title' => 'Pemeriksaan Fisik', 'resources' => ['Observation']],
                ['no' => 5, 'title' => 'Pemeriksaan Fungsional', 'resources' => ['Observation']],
                ['no' => 6, 'title' => 'Riwayat Perjalanan Penyakit', 'resources' => ['ClinicalImpression']],
                ['no' => 7, 'title' => 'Tujuan Perawatan', 'resources' => ['Goal']],
                ['no' => 8, 'title' => 'Rencana Rawat', 'resources' => ['CarePlan']],
            ],
        ],
        'rawat_inap' => [
            'title' => 'Rawat Inap',
            'url' => 'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/rawat-inap-new/',
            'steps' => [
                ['no' => 1, 'title' => 'Pendaftaran Pasien', 'resources' => ['Patient']],
                ['no' => 2, 'title' => 'Pendaftaran Kunjungan', 'resources' => ['Encounter']],
                ['no' => 3, 'title' => 'Anamnesis', 'resources' => ['Condition', 'FamilyMemberHistory', 'AllergyIntolerance', 'MedicationStatement']],
                ['no' => 4, 'title' => 'Pemeriksaan Fisik/Fungsional', 'resources' => ['Observation']],
                ['no' => 5, 'title' => 'Pemeriksaan Penunjang', 'resources' => ['ServiceRequest', 'Specimen', 'DiagnosticReport', 'Observation']],
                ['no' => 6, 'title' => 'Tindakan', 'resources' => ['Procedure']],
                ['no' => 7, 'title' => 'Pemberian Obat', 'resources' => ['MedicationRequest', 'MedicationAdministration', 'MedicationDispense']],
                ['no' => 8, 'title' => 'Resume Medis', 'resources' => ['Composition']],
            ],
        ],
        'igd' => [
            'title' => 'Instalasi Gawat Darurat (IGD)',
            'url' => 'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/igd/',
            'steps' => [
                ['no' => 1, 'title' => 'Pendaftaran Pasien', 'resources' => ['Patient']],
                ['no' => 2, 'title' => 'Pendaftaran Kunjungan', 'resources' => ['Encounter']],
                ['no' => 3, 'title' => 'Triase dan Gawat Darurat', 'resources' => ['Encounter', 'Observation']],
                ['no' => 4, 'title' => 'Anamnesis', 'resources' => ['Condition', 'FamilyMemberHistory', 'AllergyIntolerance', 'MedicationStatement']],
                ['no' => 5, 'title' => 'Asesmen Awal IGD', 'resources' => ['Observation']],
                ['no' => 6, 'title' => 'Skrining', 'resources' => ['Observation', 'QuestionnaireResponse']],
                ['no' => 7, 'title' => 'Pemeriksaan Fungsional', 'resources' => ['Observation']],
            ],
        ],
        'rujukan' => [
            'title' => 'Rujukan Pasien',
            'url' => 'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/rujukan/',
            'steps' => [
                ['no' => 1, 'title' => 'Pra Permintaan Kandidat Fasyankes Rujukan', 'resources' => ['Organization', 'Location', 'Task']],
                ['no' => 2, 'title' => 'Permintaan Rujukan', 'resources' => ['ServiceRequest', 'CarePlan']],
                ['no' => 3, 'title' => 'Tugas Rujukan', 'resources' => ['Task']],
            ],
        ],
        'tumbuh_kembang' => [
            'title' => 'Tumbuh Kembang',
            'url' => 'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/tumbuh-kembang-new/',
            'steps' => [
                ['no' => 1, 'title' => 'Pendaftaran Pasien', 'resources' => ['Patient']],
                ['no' => 2, 'title' => 'Pendaftaran Kunjungan', 'resources' => ['Encounter']],
                ['no' => 3, 'title' => 'Anamnesis', 'resources' => ['Condition', 'FamilyMemberHistory', 'AllergyIntolerance', 'MedicationStatement']],
                ['no' => 4, 'title' => 'Antropometri', 'resources' => ['Observation']],
                ['no' => 5, 'title' => 'SDIDTK', 'resources' => ['Observation', 'QuestionnaireResponse']],
                ['no' => 6, 'title' => 'Riwayat Gizi dan Konsumsi Makanan', 'resources' => ['Observation', 'NutritionOrder']],
            ],
        ],
        'inc' => [
            'title' => 'Intranatal Care (INC)',
            'url' => 'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/inc/',
            'steps' => [
                ['no' => 1, 'title' => 'Pendaftaran Pasien', 'resources' => ['Patient']],
                ['no' => 2, 'title' => 'Pendaftaran Kunjungan', 'resources' => ['Encounter']],
                ['no' => 3, 'title' => 'Menutup Episode Kehamilan ANC', 'resources' => ['EpisodeOfCare']],
                ['no' => 4, 'title' => 'Data Persalinan', 'resources' => ['Observation', 'Procedure']],
                ['no' => 5, 'title' => 'Pelayanan Persalinan', 'resources' => ['Procedure', 'Observation']],
                ['no' => 6, 'title' => 'Diagnosis', 'resources' => ['Condition']],
                ['no' => 7, 'title' => 'Farmasi', 'resources' => ['AllergyIntolerance', 'MedicationRequest', 'MedicationDispense']],
            ],
        ],
        'klaim_bpjs' => [
            'title' => 'Klaim BPJS Kesehatan',
            'url' => 'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/klaim-bpjs/',
            'steps' => [
                ['no' => 1, 'title' => 'Data Kepesertaan', 'resources' => ['Patient', 'Coverage']],
                ['no' => 2, 'title' => 'Pendaftaran Kunjungan', 'resources' => ['Encounter']],
                ['no' => 3, 'title' => 'Data Akun', 'resources' => ['Account']],
                ['no' => 4, 'title' => 'Data Klinis', 'resources' => ['Condition', 'Observation', 'Procedure']],
                ['no' => 5, 'title' => 'Data Billing', 'resources' => ['ChargeItem']],
                ['no' => 6, 'title' => 'Invoice', 'resources' => ['Invoice']],
                ['no' => 7, 'title' => 'Bundle Klaim dan RME', 'resources' => ['Bundle']],
            ],
        ],
    ];

    public function index(): ResponseInterface
    {
        return $this->response->setJSON([
            'ok' => true,
            'source' => 'SATUSEHAT Platform Playbook',
            'catalog' => $this->catalog,
        ]);
    }

    public function show(string $useCase): ResponseInterface
    {
        if (! isset($this->catalog[$useCase])) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'Use case playbook tidak ditemukan.']);
        }
        return $this->response->setJSON(['ok' => true, 'use_case' => $useCase, 'playbook' => $this->catalog[$useCase]]);
    }

    public function template(string $resource): ResponseInterface
    {
        $resource = ucfirst(trim($resource));
        $templates = $this->templates();
        if (! isset($templates[$resource])) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'Template resource belum tersedia.', 'resource' => $resource]);
        }
        return $this->response->setJSON(['ok' => true, 'resource' => $resource, 'template' => $templates[$resource]]);
    }

    private function templates(): array
    {
        return [
            'Patient' => ['resourceType' => 'Patient', 'identifier' => [['system' => 'https://fhir.kemkes.go.id/id/nik', 'value' => 'NIK']], 'name' => [['text' => 'NAMA PASIEN']], 'gender' => 'unknown', 'birthDate' => 'YYYY-MM-DD'],
            'Organization' => ['resourceType' => 'Organization', 'identifier' => [['use' => 'official', 'system' => 'http://sys-ids.kemkes.go.id/organization/ORGANIZATION_ID', 'value' => 'ORGANIZATION_ID']], 'active' => true, 'name' => 'NAMA ORGANISASI'],
            'Location' => ['resourceType' => 'Location', 'identifier' => [['system' => 'http://sys-ids.kemkes.go.id/location/ORGANIZATION_ID', 'value' => 'KODE_LOKASI']], 'status' => 'active', 'name' => 'NAMA LOKASI', 'managingOrganization' => ['reference' => 'Organization/ORGANIZATION_ID']],
            'Encounter' => ['resourceType' => 'Encounter', 'identifier' => [['system' => 'http://sys-ids.kemkes.go.id/encounter/ORGANIZATION_ID', 'use' => 'official', 'value' => 'NOMOR_KUNJUNGAN']], 'status' => 'arrived', 'class' => ['system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode', 'code' => 'AMB', 'display' => 'ambulatory'], 'subject' => ['reference' => 'Patient/PATIENT_IHS'], 'participant' => [['individual' => ['reference' => 'Practitioner/PRACTITIONER_IHS']]], 'period' => ['start' => 'UTC_DATETIME'], 'location' => [['location' => ['reference' => 'Location/LOCATION_ID']]], 'serviceProvider' => ['reference' => 'Organization/ORGANIZATION_ID']],
            'Condition' => ['resourceType' => 'Condition', 'clinicalStatus' => ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical', 'code' => 'active']]], 'code' => ['coding' => [['system' => 'http://hl7.org/fhir/sid/icd-10', 'code' => 'KODE_ICD10', 'display' => 'DESKRIPSI']]], 'subject' => ['reference' => 'Patient/PATIENT_IHS'], 'encounter' => ['reference' => 'Encounter/ENCOUNTER_ID']],
            'Observation' => ['resourceType' => 'Observation', 'status' => 'final', 'category' => [['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => 'vital-signs']]]], 'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => 'LOINC_CODE', 'display' => 'DESKRIPSI']]], 'subject' => ['reference' => 'Patient/PATIENT_IHS'], 'encounter' => ['reference' => 'Encounter/ENCOUNTER_ID']],
            'Procedure' => ['resourceType' => 'Procedure', 'status' => 'completed', 'code' => ['coding' => [['system' => 'http://snomed.info/sct', 'code' => 'SNOMED_CODE', 'display' => 'DESKRIPSI']]], 'subject' => ['reference' => 'Patient/PATIENT_IHS'], 'encounter' => ['reference' => 'Encounter/ENCOUNTER_ID']],
            'ServiceRequest' => ['resourceType' => 'ServiceRequest', 'status' => 'active', 'intent' => 'order', 'code' => ['coding' => [['system' => 'http://snomed.info/sct', 'code' => 'SNOMED_CODE', 'display' => 'DESKRIPSI']]], 'subject' => ['reference' => 'Patient/PATIENT_IHS'], 'encounter' => ['reference' => 'Encounter/ENCOUNTER_ID']],
            'Bundle' => ['resourceType' => 'Bundle', 'type' => 'transaction', 'entry' => []],
        ];
    }
}
