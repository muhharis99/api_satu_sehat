<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class SatuSehatCatalog extends BaseConfig
{
    public array $resources = [
        'Patient','Practitioner','Organization','Location','Encounter','EpisodeOfCare','Condition','AllergyIntolerance',
        'FamilyMemberHistory','Observation','Procedure','ClinicalImpression','Goal','CarePlan','ServiceRequest',
        'Specimen','DiagnosticReport','ImagingStudy','Medication','MedicationRequest','MedicationDispense',
        'MedicationAdministration','MedicationStatement','QuestionnaireResponse','RiskAssessment','NutritionOrder',
        'Coverage','Account','ChargeItem','Invoice','Task','Composition','Bundle'
    ];

    public array $collections = [
        ['key'=>'00.fhir','title'=>'FHIR Resource - Contoh Penggunaan'], ['key'=>'01.rawat_jalan','title'=>'Pelayanan - Rawat Jalan'],
        ['key'=>'02.rawat_inap','title'=>'Pelayanan - Rawat Inap'], ['key'=>'03.igd','title'=>'Pelayanan - IGD'],
        ['key'=>'04.farmasi','title'=>'Pelayanan - Farmasi'], ['key'=>'06.anc','title'=>'Use Case - Antenatal Care (ANC)'],
        ['key'=>'07.inc','title'=>'Use Case - Intranatal Care (INC)'], ['key'=>'08.pnc','title'=>'Use Case - Postnatal Care (PNC)'],
        ['key'=>'09.neonatus','title'=>'Use Case - Neonatus'], ['key'=>'10.shk','title'=>'Use Case - Skrining Hipotiroid Kongenital (SHK)'],
        ['key'=>'11.kematian','title'=>'Use Case - Kematian Maternal dan Perinatal'], ['key'=>'12.mtbs','title'=>'Use Case - MTBS Prioritas'],
        ['key'=>'13.imunisasi','title'=>'Use Case - Imunisasi'], ['key'=>'14.gizi','title'=>'Use Case - Gizi'],
        ['key'=>'15.tumbuh_kembang','title'=>'Use Case - Tumbuh Kembang'], ['key'=>'16.pkpr','title'=>'Use Case - Pelayanan Kesehatan Peduli Remaja (PKPR) Luar Gedung'],
        ['key'=>'18.imunisasi_covid','title'=>'Use Case - Imunisasi Covid 19'], ['key'=>'19.ptm','title'=>'Use Case - Skrining PTM'],
        ['key'=>'20.kanker','title'=>'Use Case - Registrasi Kanker'], ['key'=>'21.jantung','title'=>'Use Case - Registrasi Jantung'],
        ['key'=>'22.stroke','title'=>'Use Case - Registrasi Stroke'], ['key'=>'23.uronefrologi','title'=>'Use Case - Registrasi Uronefrologi'],
        ['key'=>'24.gigi','title'=>'Use Case - Gigi'], ['key'=>'25.claim_swasta','title'=>'Use Case - Modul Klaim (Asuransi Swasta)'],
        ['key'=>'26.claim_bpjs','title'=>'Use Case - Modul Klaim (BPJS-K)'], ['key'=>'30.rujukan','title'=>'Use Case - Rujukan Pasien'],
        ['key'=>'31.mata','title'=>'Use Case - Registrasi Mata'], ['key'=>'32.telinga','title'=>'Use Case - Registrasi Telinga'],
        ['key'=>'34.tb','title'=>'Use Case - Tuberkulosis'], ['key'=>'36.hiv','title'=>'Use Case - HIV'], ['key'=>'42.geriatri','title'=>'Use Case - Geriatri (Kesehatan Lansia)'],
    ];

    public array $requestGroups = [
        'patient' => [
            ['name'=>'Patient - By ID','method'=>'GET','resource'=>'Patient','query'=>'_id={{patient_ihs}}'],
            ['name'=>'Patient - By NIK','method'=>'GET','resource'=>'Patient','query'=>'identifier=https://fhir.kemkes.go.id/id/nik|{{nik}}'],
            ['name'=>'Patient - Search Name, Birthdate, Gender','method'=>'GET','resource'=>'Patient','query'=>'name={{name}}&birthdate={{birthdate}}&gender={{gender}}'],
            ['name'=>'Patient - Create by NIK','method'=>'POST','resource'=>'Patient'],
        ],
        'practitioner' => [
            ['name'=>'Practitioner - By ID','method'=>'GET','resource'=>'Practitioner','query'=>'_id={{practitioner_ihs}}'],
            ['name'=>'Practitioner - By NIK','method'=>'GET','resource'=>'Practitioner','query'=>'identifier=https://fhir.kemkes.go.id/id/nik|{{nik}}'],
            ['name'=>'Practitioner - Search Name, Birthdate, Gender','method'=>'GET','resource'=>'Practitioner','query'=>'name={{name}}&birthdate={{birthdate}}&gender={{gender}}'],
        ],
        'encounter' => [
            ['name'=>'Encounter - By ID','method'=>'GET','resource'=>'Encounter','query'=>'_id={{encounter_id}}'],
            ['name'=>'Encounter - By Subject','method'=>'GET','resource'=>'Encounter','query'=>'subject={{patient_ihs}}'],
            ['name'=>'Encounter - Create','method'=>'POST','resource'=>'Encounter'],
            ['name'=>'Encounter - Update','method'=>'PUT','resource'=>'Encounter','path'=>'{{encounter_id}}'],
            ['name'=>'Encounter - Patch','method'=>'PATCH','resource'=>'Encounter','path'=>'{{encounter_id}}'],
        ],
    ];
}
