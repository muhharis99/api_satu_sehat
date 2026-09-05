# API Satu Sehat — CodeIgniter 4

Simulator / FHIR explorer SATUSEHAT berbasis **CodeIgniter 4**. Project dibuat untuk membantu tim RME/faskes menguji OAuth2 dan resource FHIR SATUSEHAT dari browser tanpa harus menyimpan credential di source code.

## Fitur

- CodeIgniter 4.7, PHP 8.2+
- Sandbox dan Production SATUSEHAT
- OAuth2 `client_credentials`
- FHIR Explorer: GET, POST, PUT, PATCH, DELETE
- Preset Patient, Practitioner, Organization, Location, Encounter, Condition, Observation, Procedure, ServiceRequest, Specimen, DiagnosticReport, MedicationRequest, MedicationDispense, Composition, Bundle
- JSON request editor dan response viewer
- Riwayat request lokal tanpa menyimpan token/payload pasien
- Credential dari `.env` atau input sementara di browser

## Instalasi

```bash
git clone https://github.com/muhharis99/api_satu_sehat.git
cd api_satu_sehat
composer install
cp env .env
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
C:\\laragon\\www\\api_satu_sehat\\public
```

## Endpoint resmi

Sandbox:
- OAuth: `https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1`
- FHIR R4: `https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1`

Production:
- OAuth: `https://api-satusehat.kemkes.go.id/oauth2/v1`
- FHIR R4: `https://api-satusehat.kemkes.go.id/fhir-r4/v1`

## Keamanan

Jangan commit `.env`, Client Secret, maupun Access Token. Project sudah mengabaikan `.env` melalui `.gitignore`.
