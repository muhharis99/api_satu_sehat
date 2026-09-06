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
                ['no'=>1,'title'=>'Pendaftaran Pasien','resources'=>['Patient']],
                ['no'=>2,'title'=>'Pendaftaran Kunjungan','resources'=>['Encounter']],
                ['no'=>3,'title'=>'Anamnesis','resources'=>['Condition','FamilyMemberHistory','AllergyIntolerance','MedicationStatement']],
                ['no'=>4,'title'=>'Pemeriksaan Fisik','resources'=>['Observation']],
                ['no'=>5,'title'=>'Pemeriksaan Fungsional','resources'=>['Observation']],
                ['no'=>6,'title'=>'Riwayat Perjalanan Penyakit','resources'=>['ClinicalImpression']],
                ['no'=>7,'title'=>'Tujuan Perawatan','resources'=>['Goal']],
                ['no'=>8,'title'=>'Rencana Rawat','resources'=>['CarePlan']],
                ['no'=>9,'title'=>'Pemeriksaan Penunjang','resources'=>['ServiceRequest','Specimen','Observation','DiagnosticReport']],
                ['no'=>10,'title'=>'Tindakan','resources'=>['Procedure']],
                ['no'=>11,'title'=>'Peresepan dan Pengeluaran Obat','resources'=>['MedicationRequest','MedicationDispense']],
                ['no'=>12,'title'=>'Resume Medis','resources'=>['Composition']],
            ],
        ],
        'rawat_inap' => [
            'title'=>'Rawat Inap','url'=>'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/rawat-inap-new/',
            'steps'=>[
                ['no'=>1,'title'=>'Pendaftaran Pasien','resources'=>['Patient']],['no'=>2,'title'=>'Pendaftaran Kunjungan','resources'=>['Encounter']],
                ['no'=>3,'title'=>'Anamnesis','resources'=>['Condition','FamilyMemberHistory','AllergyIntolerance','MedicationStatement']],['no'=>4,'title'=>'Pemeriksaan Fisik/Fungsional','resources'=>['Observation']],
                ['no'=>5,'title'=>'Pemeriksaan Penunjang','resources'=>['ServiceRequest','Specimen','DiagnosticReport','Observation']],['no'=>6,'title'=>'Tindakan','resources'=>['ServiceRequest','Procedure','Observation']],
                ['no'=>7,'title'=>'Diagnosis','resources'=>['Condition']],['no'=>8,'title'=>'Penilaian Risiko','resources'=>['RiskAssessment']],['no'=>9,'title'=>'Peresepan Obat','resources'=>['MedicationRequest']],
                ['no'=>10,'title'=>'Pengkajian Resep','resources'=>['QuestionnaireResponse']],['no'=>11,'title'=>'Pengeluaran/Pemberian Obat','resources'=>['MedicationDispense','MedicationAdministration']],['no'=>12,'title'=>'Diet','resources'=>['NutritionOrder']],
                ['no'=>13,'title'=>'Edukasi','resources'=>['Procedure']],['no'=>14,'title'=>'Pemulangan','resources'=>['Observation','CarePlan']],['no'=>15,'title'=>'Prognosis','resources'=>['ClinicalImpression']],
                ['no'=>16,'title'=>'Rencana Tindak Lanjut','resources'=>['ServiceRequest']],['no'=>17,'title'=>'Rujukan','resources'=>['ServiceRequest']],['no'=>18,'title'=>'Kondisi Saat Keluar','resources'=>['Condition','Encounter']],['no'=>19,'title'=>'Resume Medis','resources'=>['Composition']],
            ],
        ],
        'igd'=>['title'=>'Instalasi Gawat Darurat (IGD)','url'=>'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/igd/','steps'=>[
            ['no'=>1,'title'=>'Pendaftaran Pasien','resources'=>['Patient']],['no'=>2,'title'=>'Pendaftaran Kunjungan','resources'=>['Encounter']],['no'=>3,'title'=>'Triase','resources'=>['Encounter','Observation']],['no'=>4,'title'=>'Anamnesis','resources'=>['Condition','FamilyMemberHistory','AllergyIntolerance','MedicationStatement']],['no'=>5,'title'=>'Asesmen Awal','resources'=>['Observation']],['no'=>6,'title'=>'Skrining','resources'=>['Observation','QuestionnaireResponse']],['no'=>7,'title'=>'Pemeriksaan Fungsional','resources'=>['Observation']],['no'=>8,'title'=>'Diagnosis dan Tindakan','resources'=>['Condition','Procedure']],['no'=>9,'title'=>'Farmasi','resources'=>['MedicationRequest','MedicationDispense']],['no'=>10,'title'=>'Resume','resources'=>['Composition']],
        ]],
        'rujukan'=>['title'=>'Rujukan Pasien','url'=>'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/rujukan/','steps'=>[
            ['no'=>1,'title'=>'Kandidat Fasyankes Rujukan','resources'=>['Organization','Location','Task']],['no'=>2,'title'=>'Permintaan Rujukan','resources'=>['ServiceRequest','CarePlan']],['no'=>3,'title'=>'Tugas Rujukan','resources'=>['Task']],
        ]],
        'tumbuh_kembang'=>['title'=>'Tumbuh Kembang','url'=>'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/tumbuh-kembang-new/','steps'=>[
            ['no'=>1,'title'=>'Pendaftaran Pasien','resources'=>['Patient']],['no'=>2,'title'=>'Pendaftaran Kunjungan','resources'=>['Encounter']],['no'=>3,'title'=>'Anamnesis','resources'=>['Condition','FamilyMemberHistory','AllergyIntolerance','MedicationStatement']],['no'=>4,'title'=>'Antropometri','resources'=>['Observation']],['no'=>5,'title'=>'SDIDTK','resources'=>['Observation','QuestionnaireResponse']],['no'=>6,'title'=>'Gizi','resources'=>['Observation','NutritionOrder']],
        ]],
        'inc'=>['title'=>'Intranatal Care (INC)','url'=>'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/inc/','steps'=>[
            ['no'=>1,'title'=>'Pendaftaran Pasien','resources'=>['Patient']],['no'=>2,'title'=>'Pendaftaran Kunjungan','resources'=>['Encounter']],['no'=>3,'title'=>'Menutup Episode Kehamilan ANC','resources'=>['EpisodeOfCare']],['no'=>4,'title'=>'Data Persalinan','resources'=>['Observation','Procedure']],['no'=>5,'title'=>'Pelayanan Persalinan','resources'=>['Procedure','Observation']],['no'=>6,'title'=>'Diagnosis','resources'=>['Condition']],['no'=>7,'title'=>'Farmasi','resources'=>['AllergyIntolerance','MedicationRequest','MedicationDispense']],
        ]],
        'klaim_bpjs'=>['title'=>'Klaim BPJS Kesehatan','url'=>'https://satusehat.kemkes.go.id/platform/docs/id/interoperability/klaim-bpjs/','steps'=>[
            ['no'=>1,'title'=>'Kepesertaan','resources'=>['Patient','Coverage']],['no'=>2,'title'=>'Kunjungan','resources'=>['Encounter']],['no'=>3,'title'=>'Akun','resources'=>['Account']],['no'=>4,'title'=>'Data Klinis','resources'=>['Condition','Observation','Procedure']],['no'=>5,'title'=>'Billing','resources'=>['ChargeItem']],['no'=>6,'title'=>'Invoice','resources'=>['Invoice']],['no'=>7,'title'=>'Bundle Klaim/RME','resources'=>['Bundle']],
        ]],
    ];

    public function index(): ResponseInterface { return $this->response->setJSON(['ok'=>true,'source'=>'SATUSEHAT Platform Playbook','catalog'=>$this->catalog]); }
    public function show(string $useCase): ResponseInterface { if(!isset($this->catalog[$useCase])) return $this->response->setStatusCode(404)->setJSON(['ok'=>false,'message'=>'Use case playbook tidak ditemukan.']); return $this->response->setJSON(['ok'=>true,'use_case'=>$useCase,'playbook'=>$this->catalog[$useCase]]); }
    public function template(string $resource): ResponseInterface { $resource=ucfirst(trim($resource)); $templates=$this->templates(); if(!isset($templates[$resource])) return $this->response->setStatusCode(404)->setJSON(['ok'=>false,'message'=>'Template resource belum tersedia.','resource'=>$resource]); return $this->response->setJSON(['ok'=>true,'resource'=>$resource,'template'=>$templates[$resource]]); }

    private function templates(): array
    {
        $refPatient=['reference'=>'Patient/PATIENT_IHS']; $refEncounter=['reference'=>'Encounter/ENCOUNTER_ID'];
        return [
            'Patient'=>['resourceType'=>'Patient','identifier'=>[['system'=>'https://fhir.kemkes.go.id/id/nik','value'=>'NIK']],'name'=>[['text'=>'NAMA PASIEN']],'gender'=>'unknown','birthDate'=>'YYYY-MM-DD'],
            'Practitioner'=>['resourceType'=>'Practitioner','identifier'=>[['system'=>'https://fhir.kemkes.go.id/id/nik','value'=>'NIK']],'name'=>[['text'=>'NAMA PRACTITIONER']]],
            'Organization'=>['resourceType'=>'Organization','identifier'=>[['use'=>'official','system'=>'http://sys-ids.kemkes.go.id/organization/ORGANIZATION_ID','value'=>'ORGANIZATION_ID']],'active'=>true,'name'=>'NAMA ORGANISASI'],
            'Location'=>['resourceType'=>'Location','identifier'=>[['system'=>'http://sys-ids.kemkes.go.id/location/ORGANIZATION_ID','value'=>'KODE_LOKASI']],'status'=>'active','name'=>'NAMA LOKASI','managingOrganization'=>['reference'=>'Organization/ORGANIZATION_ID']],
            'Encounter'=>['resourceType'=>'Encounter','identifier'=>[['system'=>'http://sys-ids.kemkes.go.id/encounter/ORGANIZATION_ID','use'=>'official','value'=>'NOMOR_KUNJUNGAN']],'status'=>'arrived','class'=>['system'=>'http://terminology.hl7.org/CodeSystem/v3-ActCode','code'=>'AMB','display'=>'ambulatory'],'serviceType'=>['coding'=>[['system'=>'TERMINOLOGI_SATUSEHAT','code'=>'KODE_TIPE_PELAYANAN','display'=>'TIPE PELAYANAN']]],'subject'=>$refPatient,'participant'=>[['individual'=>['reference'=>'Practitioner/PRACTITIONER_IHS']]],'period'=>['start'=>'UTC_DATETIME'],'location'=>[['location'=>['reference'=>'Location/LOCATION_ID']]],'serviceProvider'=>['reference'=>'Organization/ORGANIZATION_ID']],
            'Condition'=>['resourceType'=>'Condition','clinicalStatus'=>['coding'=>[['system'=>'http://terminology.hl7.org/CodeSystem/condition-clinical','code'=>'active']]],'category'=>[['coding'=>[['system'=>'http://terminology.hl7.org/CodeSystem/condition-category','code'=>'problem-list-item','display'=>'Problem List Item']]]],'code'=>['coding'=>[['system'=>'http://snomed.info/sct','code'=>'SNOMED_CODE','display'=>'SNOMED DESCRIPTION']]],'subject'=>$refPatient,'encounter'=>$refEncounter],
            'Observation'=>['resourceType'=>'Observation','status'=>'final','category'=>[['coding'=>[['system'=>'http://terminology.hl7.org/CodeSystem/observation-category','code'=>'vital-signs']]]],'code'=>['coding'=>[['system'=>'http://loinc.org','code'=>'LOINC_CODE','display'=>'LOINC DESCRIPTION']]],'subject'=>$refPatient,'encounter'=>$refEncounter,'effectiveDateTime'=>'UTC_DATETIME','valueQuantity'=>['value'=>0,'unit'=>'UNIT','system'=>'http://unitsofmeasure.org','code'=>'UNIT']],
            'Procedure'=>['resourceType'=>'Procedure','status'=>'completed','category'=>['coding'=>[['system'=>'http://snomed.info/sct','code'=>'SNOMED_CATEGORY','display'=>'CATEGORY']]],'code'=>['coding'=>[['system'=>'http://snomed.info/sct','code'=>'SNOMED_CODE','display'=>'SNOMED DESCRIPTION']]],'subject'=>$refPatient,'encounter'=>$refEncounter,'performedDateTime'=>'UTC_DATETIME','performer'=>[['actor'=>['reference'=>'Practitioner/PRACTITIONER_IHS']]]],
            'ServiceRequest'=>['resourceType'=>'ServiceRequest','status'=>'active','intent'=>'order','code'=>['coding'=>[['system'=>'http://snomed.info/sct','code'=>'SNOMED_CODE','display'=>'PEMERIKSAAN']]],'subject'=>$refPatient,'encounter'=>$refEncounter,'authoredOn'=>'UTC_DATETIME','requester'=>['reference'=>'Practitioner/PRACTITIONER_IHS'],'performer'=>[['reference'=>'Practitioner/PERFORMER_IHS']]],
            'Specimen'=>['resourceType'=>'Specimen','status'=>'available','type'=>['coding'=>[['system'=>'http://snomed.info/sct','code'=>'SPECIMEN_CODE','display'=>'SPECIMEN']]],'subject'=>$refPatient,'request'=>[['reference'=>'ServiceRequest/SERVICEREQUEST_ID']]],
            'DiagnosticReport'=>['resourceType'=>'DiagnosticReport','status'=>'final','category'=>[['coding'=>[['system'=>'http://terminology.hl7.org/CodeSystem/v2-0074','code'=>'LAB']]]],'code'=>['coding'=>[['system'=>'http://loinc.org','code'=>'LOINC_CODE','display'=>'HASIL PEMERIKSAAN']]],'subject'=>$refPatient,'encounter'=>$refEncounter,'effectiveDateTime'=>'UTC_DATETIME','result'=>[['reference'=>'Observation/OBSERVATION_ID']]],
            'MedicationRequest'=>['resourceType'=>'MedicationRequest','status'=>'active','intent'=>'order','medicationCodeableConcept'=>['coding'=>[['system'=>'http://sys-ids.kemkes.go.id/kfa','code'=>'KFA_CODE','display'=>'NAMA OBAT']]],'subject'=>$refPatient,'encounter'=>$refEncounter,'authoredOn'=>'UTC_DATETIME','requester'=>['reference'=>'Practitioner/PRACTITIONER_IHS'],'dosageInstruction'=>[['text'=>'DOSIS DAN ATURAN PAKAI']]],
            'MedicationDispense'=>['resourceType'=>'MedicationDispense','status'=>'completed','medicationCodeableConcept'=>['coding'=>[['system'=>'http://sys-ids.kemkes.go.id/kfa','code'=>'KFA_CODE','display'=>'NAMA OBAT']]],'subject'=>$refPatient,'authorizingPrescription'=>[['reference'=>'MedicationRequest/MEDICATIONREQUEST_ID']],'performer'=>[['actor'=>['reference'=>'Practitioner/PRACTITIONER_IHS']]],'whenHandedOver'=>'UTC_DATETIME'],
            'MedicationAdministration'=>['resourceType'=>'MedicationAdministration','status'=>'completed','medicationCodeableConcept'=>['coding'=>[['system'=>'http://sys-ids.kemkes.go.id/kfa','code'=>'KFA_CODE','display'=>'NAMA OBAT']]],'subject'=>$refPatient,'context'=>$refEncounter,'effectiveDateTime'=>'UTC_DATETIME','performer'=>[['actor'=>['reference'=>'Practitioner/PRACTITIONER_IHS']]],'dosage'=>['route'=>['coding'=>[['system'=>'http://www.whocc.no/atc','code'=>'ATC_ROUTE','display'=>'RUTE']]],'dose'=>['value'=>0,'unit'=>'UNIT','system'=>'http://unitsofmeasure.org','code'=>'UNIT']]],
            'ClinicalImpression'=>['resourceType'=>'ClinicalImpression','status'=>'completed','subject'=>$refPatient,'encounter'=>$refEncounter,'effectiveDateTime'=>'UTC_DATETIME','summary'=>'RINGKASAN PERJALANAN PENYAKIT'],
            'Goal'=>['resourceType'=>'Goal','lifecycleStatus'=>'active','subject'=>$refPatient,'description'=>['text'=>'TUJUAN PERAWATAN']],
            'CarePlan'=>['resourceType'=>'CarePlan','status'=>'active','intent'=>'plan','subject'=>$refPatient,'encounter'=>$refEncounter,'description'=>'RENCANA PERAWATAN'],
            'FamilyMemberHistory'=>['resourceType'=>'FamilyMemberHistory','status'=>'completed','patient'=>$refPatient,'relationship'=>['coding'=>[['system'=>'http://terminology.hl7.org/CodeSystem/v3-RoleCode','code'=>'FTH','display'=>'Father']]],'condition'=>[]],
            'AllergyIntolerance'=>['resourceType'=>'AllergyIntolerance','clinicalStatus'=>['coding'=>[['system'=>'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical','code'=>'active']]],'patient'=>$refPatient,'code'=>['coding'=>[['system'=>'http://snomed.info/sct','code'=>'ALLERGY_CODE','display'=>'ALERGI']]]],
            'MedicationStatement'=>['resourceType'=>'MedicationStatement','status'=>'active','subject'=>$refPatient,'medicationCodeableConcept'=>['coding'=>[['system'=>'http://sys-ids.kemkes.go.id/kfa','code'=>'KFA_CODE','display'=>'NAMA OBAT']]],'effectiveDateTime'=>'UTC_DATETIME'],
            'QuestionnaireResponse'=>['resourceType'=>'QuestionnaireResponse','status'=>'completed','subject'=>$refPatient,'encounter'=>$refEncounter,'authored'=>'UTC_DATETIME','item'=>[]],
            'RiskAssessment'=>['resourceType'=>'RiskAssessment','status'=>'final','subject'=>$refPatient,'encounter'=>$refEncounter,'occurrenceDateTime'=>'UTC_DATETIME','prediction'=>[]],
            'NutritionOrder'=>['resourceType'=>'NutritionOrder','status'=>'active','intent'=>'order','patient'=>$refPatient,'encounter'=>$refEncounter,'dateTime'=>'UTC_DATETIME'],
            'EpisodeOfCare'=>['resourceType'=>'EpisodeOfCare','status'=>'active','patient'=>$refPatient],
            'Task'=>['resourceType'=>'Task','status'=>'requested','intent'=>'order','for'=>$refPatient,'authoredOn'=>'UTC_DATETIME'],
            'Coverage'=>['resourceType'=>'Coverage','status'=>'active','beneficiary'=>$refPatient,'payor'=>[['reference'=>'Organization/ORGANIZATION_ID']]],
            'Account'=>['resourceType'=>'Account','status'=>'active','subject'=>[$refPatient]],
            'ChargeItem'=>['resourceType'=>'ChargeItem','status'=>'billable','code'=>['coding'=>[['system'=>'http://terminology.kemkes.go.id/CodeSystem/kptl','code'=>'KPTL_CODE','display'=>'TINDAKAN']]],'subject'=>$refPatient,'encounter'=>$refEncounter],
            'Invoice'=>['resourceType'=>'Invoice','status'=>'issued','subject'=>$refPatient,'account'=>['reference'=>'Account/ACCOUNT_ID'],'lineItem'=>[]],
            'Composition'=>['resourceType'=>'Composition','status'=>'final','type'=>['coding'=>[['system'=>'http://loinc.org','code'=>'81215-6','display'=>'Discharge summary']]],'subject'=>$refPatient,'encounter'=>$refEncounter,'date'=>'UTC_DATETIME','author'=>[['reference'=>'Practitioner/PRACTITIONER_IHS']],'title'=>'RESUME MEDIS','section'=>[]],
            'Bundle'=>['resourceType'=>'Bundle','type'=>'transaction','entry'=>[]],
        ];
    }
}
