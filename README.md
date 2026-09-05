# API Satu Sehat — CodeIgniter 4

Simulator / FHIR explorer SATUSEHAT berbasis **CodeIgniter 4**. Project dibuat untuk membantu tim RME/faskes menguji OAuth2, resource FHIR, dan terminologi medis SATUSEHAT dari browser tanpa menyimpan credential di source code.

## Fitur

- CodeIgniter 4.7, PHP 8.2+
- Sandbox dan Production SATUSEHAT
- OAuth2 `client_credentials`
- FHIR Explorer: GET, POST, PUT, PATCH, DELETE
- Preset Patient, Practitioner, Organization, Location, Encounter, Condition, Observation, Procedure, ServiceRequest, Specimen, DiagnosticReport, MedicationRequest, MedicationDispense, Composition, Bundle
- JSON request editor dan response viewer
- Browser Kode Medis: ICD-10 2010, SNOMED CT, LOINC, KFA, dan KPTL
- KFA terhubung ke endpoint KFA v2 resmi SATUSEHAT
- Hasil terminologi dapat langsung dimasukkan ke JSON FHIR aktif
- Riwayat request lokal tanpa menyimpan token/payload pasien
- Credential dari `.env` atau input sementara di browser

## Instalasi

```bash
git clone https://github.com/muhharis99/api_satu_sehat.git
cd api_satu_sehat
composer install
cp env .env
```

Windows CMD:

```bat
copy env .env
```

Isi `.env`:

```dotenv
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

satusehat.environment = sandbox
satusehat.organization_id = 'ORGANIZATION_ID_ANDA'
satusehat.client_id = 'CLIENT_ID_ANDA'
satusehat.client_secret = 'CLIENT_SECRET_ANDA'
```

Jalankan:

```bash
php spark serve
```

Buka `http://localhost:8080`.

### Laragon

Arahkan document root ke folder `public`, atau akses melalui virtual host yang document root-nya menunjuk ke:

```text
C:\laragon\www\api_satu_sehat\public
```

## Kode Medis / Terminologi

Menu **Kode Medis** menyediakan pencarian:

- `ICD-10 2010` → `http://hl7.org/fhir/sid/icd-10`
- `SNOMED CT` → `http://snomed.info/sct`
- `LOINC` → `http://loinc.org`
- `KFA` → `http://sys-ids.kemkes.go.id/kfa`
- `KPTL` → `http://terminology.kemkes.go.id/CodeSystem/kptl`

### KFA

KFA tidak memakai data dummy. Pencarian diarahkan ke KFA v2 SATUSEHAT menggunakan Bearer access token aktif.

### ICD-10, SNOMED CT, LOINC, KPTL

Repository hanya membawa starter data kecil untuk pengujian. Dataset lengkap sengaja tidak disimpan di Git karena ukuran, pembaruan versi, dan ketentuan lisensi masing-masing terminologi.

Letakkan dataset resmi di:

```text
writable/terminology/
```

Nama file yang didukung:

```text
icd10.jsonl / icd10.json / icd10.csv
snomed.jsonl / snomed.json / snomed.csv
loinc.jsonl / loinc.json / loinc.csv
kptl.jsonl / kptl.json / kptl.csv
```

Format paling sederhana:

```json
{"code":"J40","display":"Bronchitis, not specified as acute or chronic"}
```

Untuk CSV gunakan header minimal:

```csv
code,display
J40,"Bronchitis, not specified as acute or chronic"
```

Beberapa nama kolom umum seperti `kode`, `description`, `deskripsi`, `concept_id`, `LoincNumber`, dan `LongCommonName` juga dikenali.

Dataset di `writable/terminology` di-ignore oleh Git agar data berlisensi atau file besar tidak tidak sengaja dipublikasikan.

## Endpoint resmi

Sandbox:
- OAuth: `https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1`
- FHIR R4: `https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1`
- KFA v2: `https://api-satusehat-stg.dto.kemkes.go.id/kfa-v2`

Production:
- OAuth: `https://api-satusehat.kemkes.go.id/oauth2/v1`
- FHIR R4: `https://api-satusehat.kemkes.go.id/fhir-r4/v1`
- KFA v2: `https://api-satusehat.kemkes.go.id/kfa-v2`

## Keamanan

Jangan commit `.env`, Client Secret, Access Token, maupun dataset terminologi berlisensi. Project sudah mengabaikannya melalui `.gitignore`.
