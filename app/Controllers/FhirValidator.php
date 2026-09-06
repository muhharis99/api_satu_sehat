<?php
namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class FhirValidator extends BaseController
{
    private array $required = [
        'Patient' => ['resourceType'],
        'Practitioner' => ['resourceType'],
        'Organization' => ['resourceType'],
        'Location' => ['resourceType'],
        'Encounter' => ['resourceType','status','class','subject','period','serviceProvider'],
        'Condition' => ['resourceType','code','subject'],
        'Observation' => ['resourceType','status','code','subject'],
        'Procedure' => ['resourceType','status','code','subject','encounter'],
        'ServiceRequest' => ['resourceType','status','intent','code','subject','encounter'],
        'DiagnosticReport' => ['resourceType','status','code','subject'],
        'MedicationRequest' => ['resourceType','status','intent','subject','requester'],
        'MedicationDispense' => ['resourceType','status','subject'],
        'MedicationAdministration' => ['resourceType','status','subject'],
        'Composition' => ['resourceType','status','type','subject','date','author','title'],
        'Bundle' => ['resourceType','type'],
    ];

    public function validate(): ResponseInterface
    {
        $payload = $this->request->getJSON(true);
        if (!is_array($payload)) return $this->response->setStatusCode(422)->setJSON(['ok'=>false,'errors'=>['Body harus berupa JSON object.']]);
        $errors=[]; $warnings=[]; $resource=(string)($payload['resourceType']??'');
        if($resource==='') $errors[]='resourceType wajib diisi.';
        if(isset($this->required[$resource])) foreach($this->required[$resource] as $field){if(!array_key_exists($field,$payload)||$payload[$field]===null||$payload[$field]===''||$payload[$field]===[]) $errors[]="$resource.$field wajib diisi.";}
        if($resource==='Encounter'){
            $system=$payload['identifier'][0]['system']??'';
            if($system!=='' && !preg_match('#^http://sys-ids\.kemkes\.go\.id/encounter/[^/]+$#',$system)) $errors[]='Encounter.identifier.system harus menggunakan format http://sys-ids.kemkes.go.id/encounter/{Organization_ID}.';
            if(isset($payload['class']['system']) && $payload['class']['system']!=='http://terminology.hl7.org/CodeSystem/v3-ActCode') $warnings[]='Encounter.class.system tidak menggunakan v3-ActCode.';
        }
        $json=json_encode($payload,JSON_UNESCAPED_SLASHES);
        if($json!==false && preg_match('/\b(UTC_DATETIME|ORGANIZATION_ID|PATIENT_IHS|PRACTITIONER_IHS|ENCOUNTER_ID|LOCATION_ID|KFA_CODE|SNOMED_CODE|LOINC_CODE|NIK)\b/',$json)) $warnings[]='Payload masih mengandung placeholder template yang harus diganti sebelum dikirim.';
        $dates=$this->findDateValues($payload); foreach($dates as $path=>$value){if($value!=='' && !preg_match('/(Z|[+-]\d{2}:\d{2})$/',$value)) $warnings[]="$path sebaiknya dikirim dengan timezone/UTC offset (contoh +00:00).";}
        return $this->response->setJSON(['ok'=>count($errors)===0,'resourceType'=>$resource,'errors'=>$errors,'warnings'=>$warnings,'summary'=>['errors'=>count($errors),'warnings'=>count($warnings)]]);
    }

    private function findDateValues(array $data,string $path=''): array
    {
        $out=[]; foreach($data as $key=>$value){$p=$path===''?(string)$key:$path.'.'.$key; if(is_array($value)){$out=array_merge($out,$this->findDateValues($value,$p));}elseif(is_string($value)&&preg_match('/(date|Date|time|Time|period|Period|authored|effective|performed|occurrence)/',$key)&&preg_match('/^\d{4}-\d{2}-\d{2}T/',$value)){$out[$p]=$value;}} return $out;
    }
}
