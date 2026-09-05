const resources=['Patient','Practitioner','Organization','Location','Encounter','Condition','Observation','Procedure','ServiceRequest','Specimen','DiagnosticReport','MedicationRequest','MedicationDispense','Composition','Bundle'];
const examples={
Patient:{method:'GET',query:'identifier=https://fhir.kemkes.go.id/id/nik|3273010101010001',body:''},
Practitioner:{method:'GET',query:'identifier=https://fhir.kemkes.go.id/id/nik|3273010101010002',body:''},
Organization:{method:'GET',query:'',body:''},
Location:{method:'GET',query:'organization=YOUR_ORGANIZATION_ID',body:''},
Encounter:{method:'POST',query:'',body:JSON.stringify({resourceType:'Encounter',status:'arrived',class:{system:'http://terminology.hl7.org/CodeSystem/v3-ActCode',code:'AMB',display:'ambulatory'},subject:{reference:'Patient/PATIENT_IHS'},participant:[{individual:{reference:'Practitioner/PRACTITIONER_IHS'}}],period:{start:new Date().toISOString()},location:[{location:{reference:'Location/LOCATION_ID'}}],serviceProvider:{reference:'Organization/ORGANIZATION_ID'},identifier:[{system:'http://sys-ids.kemkes.go.id/encounter/ORGANIZATION_ID',value:'NO-REG-001'}]},null,2)},
Condition:{method:'POST',query:'',body:JSON.stringify({resourceType:'Condition',clinicalStatus:{coding:[{system:'http://terminology.hl7.org/CodeSystem/condition-clinical',code:'active'}]},category:[{coding:[{system:'http://terminology.hl7.org/CodeSystem/condition-category',code:'encounter-diagnosis'}]}],code:{coding:[{system:'http://hl7.org/fhir/sid/icd-10',code:'J40',display:'Bronchitis, not specified as acute or chronic'}]},subject:{reference:'Patient/PATIENT_IHS'},encounter:{reference:'Encounter/ENCOUNTER_ID'}},null,2)},
Observation:{method:'GET',query:'subject=PATIENT_IHS',body:''},Procedure:{method:'GET',query:'subject=PATIENT_IHS',body:''},ServiceRequest:{method:'GET',query:'subject=PATIENT_IHS',body:''},Specimen:{method:'GET',query:'subject=PATIENT_IHS',body:''},DiagnosticReport:{method:'GET',query:'subject=PATIENT_IHS',body:''},MedicationRequest:{method:'GET',query:'subject=PATIENT_IHS',body:''},MedicationDispense:{method:'GET',query:'subject=PATIENT_IHS',body:''},Composition:{method:'GET',query:'subject=PATIENT_IHS',body:''},Bundle:{method:'POST',query:'',body:JSON.stringify({resourceType:'Bundle',type:'transaction',entry:[]},null,2)}
};
const terminologyLabels={icd10:'ICD-10',snomed:'SNOMED CT',loinc:'LOINC',kfa:'KFA',kptl:'KPTL'};
const $=id=>document.getElementById(id); const base=(window.APP_BASE||'').replace(/\/$/,'');
const endpoints={
  sandbox:{oauth:'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1',fhir:'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1',kfa:'https://api-satusehat-stg.dto.kemkes.go.id/kfa-v2',kfaAlkes:'https://api-satusehat-stg.dto.kemkes.go.id/kfa-v3'},
  production:{oauth:'https://api-satusehat.kemkes.go.id/oauth2/v1',fhir:'https://api-satusehat.kemkes.go.id/fhir-r4/v1',kfa:'https://api-satusehat.kemkes.go.id/kfa-v2',kfaAlkes:'https://api-satusehat.kemkes.go.id/kfa-v3'}
};
let terminologyItems=[]; let selectedCoding=null;

function init(){
  resources.forEach(r=>{const o=document.createElement('option');o.value=r;o.textContent=r;$('resource').appendChild(o);const b=document.createElement('button');b.className='resource-item';b.textContent=r;b.onclick=()=>{showSection('workspace');$('resource').value=r;loadExample()};$('resourceNav').appendChild(b)});
  document.querySelectorAll('.nav-item').forEach(b=>b.onclick=()=>showSection(b.dataset.section));
  $('environment').onchange=updateEndpoint;$('btnToken').onclick=getToken;$('btnTokenSmall').onclick=getToken;$('btnSend').onclick=sendRequest;$('btnExample').onclick=loadExample;$('btnHistory').onclick=loadHistory;$('method').onchange=updateBodyState;$('resource').onchange=loadExample;
  $('terminologyType').onchange=updateTerminologyMode;$('kfaProductType').onchange=updateTerminologyMode;$('btnTerminologySearch').onclick=searchTerminology;$('terminologyQuery').addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();searchTerminology()}});$('btnCopyCoding').onclick=copyCoding;$('btnInsertCoding').onclick=insertCoding;
  updateEndpoint();updateTerminologyMode();loadExample();
}
function showSection(name){document.querySelectorAll('.content-section').forEach(s=>s.hidden=true);$('section-'+name).hidden=false;document.querySelectorAll('.nav-item').forEach(b=>b.classList.toggle('active',b.dataset.section===name));if(name==='history')loadHistory()}
function updateEndpoint(){const env=$('environment').value;const ep=endpoints[env];$('oauthUrl').textContent=ep.oauth;$('fhirUrl').textContent=ep.fhir;if($('kfaUrl'))$('kfaUrl').textContent=ep.kfa;if($('kfaAlkesUrl'))$('kfaAlkesUrl').textContent=ep.kfaAlkes;$('envBadge').textContent=env.toUpperCase();$('envBadge').classList.toggle('danger',env==='production')}
function connection(){return{environment:$('environment').value,organization_id:$('organizationId').value.trim(),client_id:$('clientId').value.trim(),client_secret:$('clientSecret').value}}
async function getToken(){toggle(true);setResponse('Mengambil access token...',0);try{const res=await fetch(base+'/satusehat/token',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(connection())});const data=await res.json();if(data.body&&data.body.access_token){$('accessToken').value=data.body.access_token;setResponse(data,data.status||res.status)}else setResponse(data,data.status||res.status)}catch(e){setResponse({message:e.message},0)}finally{toggle(false)}}
async function sendRequest(){toggle(true);setResponse('Mengirim request...',0);const payload={...connection(),method:$('method').value,resource:$('resource').value,id:$('resourceId').value.trim(),query:$('query').value.trim(),body:$('requestBody').value,token:$('accessToken').value.trim()};try{const res=await fetch(base+'/satusehat/request',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});let data;try{data=await res.json()}catch{data={message:await res.text()}}setResponse(data,data.status||res.status)}catch(e){setResponse({message:e.message},0)}finally{toggle(false)}}
function setResponse(data,status){const body=typeof data==='string'?data:JSON.stringify(data,null,2);$('responseBody').textContent=body;const s=$('responseStatus');s.className='status';if(status){s.textContent='HTTP '+status;s.classList.add(status>=200&&status<300?'ok':'err')}else{s.textContent=typeof data==='string'?data:'Error';s.classList.add('err')}}
function loadExample(){const ex=examples[$('resource').value]||examples.Patient;$('method').value=ex.method;$('query').value=ex.query.replaceAll('YOUR_ORGANIZATION_ID',$('organizationId').value.trim()||'ORGANIZATION_ID');$('requestBody').value=ex.body.replaceAll('ORGANIZATION_ID',$('organizationId').value.trim()||'ORGANIZATION_ID');$('resourceId').value='';updateBodyState()}
function updateBodyState(){const enabled=['POST','PUT','PATCH'].includes($('method').value);$('requestBody').disabled=!enabled;$('requestHint').textContent=enabled?'Masukkan JSON FHIR yang valid.':'Body tidak dikirim untuk method '+$('method').value+'.'}
async function loadHistory(){try{const r=await fetch(base+'/satusehat/history');const d=await r.json();const tb=$('historyBody');tb.innerHTML='';if(!d.items||!d.items.length){tb.innerHTML='<tr><td colspan="4" class="empty">Belum ada data.</td></tr>';return}d.items.forEach(x=>{const tr=document.createElement('tr');[new Date(x.time).toLocaleString('id-ID'),x.method,x.target,String(x.status)].forEach(v=>{const td=document.createElement('td');td.textContent=v;tr.appendChild(td)});tb.appendChild(tr)})}catch(e){$('historyBody').innerHTML='<tr><td colspan="4" class="empty">Gagal memuat riwayat.</td></tr>'}}

function updateTerminologyMode(){const isKfa=$('terminologyType').value==='kfa';$('kfaProductWrap').hidden=!isKfa;if(isKfa){const alkes=$('kfaProductType').value==='alkes';$('terminologyNotice').textContent=alkes?'KFA Alat Kesehatan menggunakan endpoint KFA v3 resmi SATUSEHAT. Pastikan Access Token sudah tersedia.':'KFA Farmasi/Obat menggunakan endpoint KFA v2 resmi SATUSEHAT. Pastikan Access Token sudah tersedia.'}else{$('terminologyNotice').textContent='Pencarian memakai dataset lokal di writable/terminology. Jika dataset belum ada, aplikasi memakai starter data untuk pengujian.'}}
async function searchTerminology(){
  const type=$('terminologyType').value;const q=$('terminologyQuery').value.trim();if(!q){renderTerminologyMessage('Masukkan kode atau istilah yang ingin dicari.');return}
  toggle(true);renderTerminologyMessage('Mencari data...');clearSelectedCoding();
  const payload={...connection(),type,q,limit:25,product_type:$('kfaProductType').value,token:$('accessToken').value.trim()};
  try{
    const res=await fetch(base+'/terminology/search',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});const data=await res.json();
    if(!res.ok||!data.ok){renderTerminologyMessage(data.message||(data.raw?JSON.stringify(data.raw):'Pencarian gagal.'));return}
    terminologyItems=Array.isArray(data.items)?data.items:[];renderTerminologyResults(terminologyItems);
    const notice=[];notice.push('Sumber: '+(data.source||'-'));if(data.dataset)notice.push('Dataset: '+data.dataset);if(data.notice)notice.push(data.notice);$('terminologyNotice').textContent=notice.join(' · ');
  }catch(e){renderTerminologyMessage('Gagal mencari: '+e.message)}finally{toggle(false)}
}
function renderTerminologyMessage(message){terminologyItems=[];$('terminologyBody').innerHTML='';const tr=document.createElement('tr');const td=document.createElement('td');td.colSpan=4;td.className='empty';td.textContent=message;tr.appendChild(td);$('terminologyBody').appendChild(tr)}
function renderTerminologyResults(items){const tb=$('terminologyBody');tb.innerHTML='';if(!items.length){renderTerminologyMessage('Data tidak ditemukan.');return}items.forEach((item,index)=>{const tr=document.createElement('tr');const system=document.createElement('td');system.textContent=terminologyLabels[item.type]||item.type||'-';const code=document.createElement('td');code.className='mono';code.textContent=item.code||'-';const display=document.createElement('td');display.textContent=item.display||'-';const action=document.createElement('td');const btn=document.createElement('button');btn.className='btn compact';btn.textContent='Pilih';btn.onclick=()=>selectCoding(index);action.appendChild(btn);tr.append(system,code,display,action);tb.appendChild(tr)})}
function selectCoding(index){const item=terminologyItems[index];if(!item)return;selectedCoding={system:item.system,code:item.code,display:item.display};$('codingPreview').value=JSON.stringify({coding:[selectedCoding]},null,2);$('codingStatus').textContent=(terminologyLabels[item.type]||item.type||'Kode')+' dipilih';$('codingStatus').className='status ok';$('btnCopyCoding').disabled=false;$('btnInsertCoding').disabled=false}
function clearSelectedCoding(){selectedCoding=null;$('codingPreview').value='';$('codingStatus').textContent='Belum dipilih';$('codingStatus').className='status';$('btnCopyCoding').disabled=true;$('btnInsertCoding').disabled=true}
async function copyCoding(){if(!selectedCoding)return;const text=JSON.stringify({coding:[selectedCoding]},null,2);try{await navigator.clipboard.writeText(text);$('codingStatus').textContent='Coding tersalin'}catch{$('codingPreview').select();document.execCommand('copy');$('codingStatus').textContent='Coding tersalin'}}
function insertCoding(){
  if(!selectedCoding)return;
  let body={};const raw=$('requestBody').value.trim();
  if(raw){try{body=JSON.parse(raw)}catch{$('codingStatus').textContent='JSON Request tidak valid';$('codingStatus').className='status err';return}}
  const resource=$('resource').value;body.resourceType=body.resourceType||resource;
  const coding={...selectedCoding};
  if(['MedicationRequest','MedicationDispense','MedicationStatement'].includes(resource)){
    body.medicationCodeableConcept=body.medicationCodeableConcept||{};body.medicationCodeableConcept.coding=mergeCoding(body.medicationCodeableConcept.coding,coding);
  }else if(resource==='Procedure'&&coding.system==='http://sys-ids.kemkes.go.id/kfa'){
    body.usedCode=Array.isArray(body.usedCode)?body.usedCode:[];body.usedCode.push({coding:[coding]});
  }else{
    body.code=body.code||{};body.code.coding=mergeCoding(body.code.coding,coding);
  }
  if(!['POST','PUT','PATCH'].includes($('method').value))$('method').value='POST';updateBodyState();$('requestBody').value=JSON.stringify(body,null,2);showSection('workspace');$('codingStatus').textContent='Sudah dimasukkan ke JSON Request';$('codingStatus').className='status ok';
}
function mergeCoding(existing,coding){const list=Array.isArray(existing)?existing:[];const filtered=list.filter(x=>!(x&&x.system===coding.system&&x.code===coding.code));filtered.push(coding);return filtered}
function toggle(on){document.body.classList.toggle('loading',on)}
document.addEventListener('DOMContentLoaded',init);
