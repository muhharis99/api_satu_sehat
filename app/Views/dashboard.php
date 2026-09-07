<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#102a2a"><title>API Kesehatan — SATUSEHAT</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body>
<div class="app-shell">
<aside class="sidebar">
<div class="brand"><div class="brand-mark">A</div><div><strong>API KESEHATAN</strong><span>SATUSEHAT Integration</span></div></div>
<div class="nav-label">Workspace</div>
<button class="nav-item active" data-section="dashboard">Dashboard</button>
<button class="nav-item" data-section="request">API Explorer</button>
<button class="nav-item" data-section="playbook">Playbook</button>
<button class="nav-item" data-section="terminology">Kode Medis</button>
<div class="nav-label">Sistem</div>
<button class="nav-item" data-section="connection">Konfigurasi API</button>
<button class="nav-item" data-section="history">Riwayat Request</button>
<div class="nav-label">Resource</div><div id="resourceNav"></div>
<div class="sidebar-footer">SATUSEHAT Public · FHIR R4</div>
</aside>
<main class="main">
<header class="topbar"><div><h1>SATUSEHAT API Explorer</h1><p>Workspace berbasis katalog Postman Public SATUSEHAT: resource FHIR, use case, request contoh, token OAuth2, dan coding.</p></div><div class="top-actions"><span class="pill" id="envBadge">SANDBOX</span></div></header>

<section class="content-section" id="section-dashboard">
<div class="hero-card"><div><span class="eyebrow">SATUSEHAT PUBLIC</span><h2>Gunakan pola request yang sama seperti koleksi Postman resmi.</h2><p>API Explorer sekarang dipusatkan pada request nyata: method, URL, query parameter, path ID, bearer token, dan payload FHIR.</p><div class="hero-actions"><button class="btn primary" data-go="request">Buka API Explorer</button><button class="btn" data-go="playbook">Lihat Playbook</button></div></div><div class="hero-status"><span class="status-dot"></span><strong id="dashboardEnv">Sandbox</strong><small>Environment aktif</small></div></div>
<div class="stats-grid"><button class="stat-card" data-go="request"><span>FHIR Resource</span><strong id="resourceCount">0</strong><small>Resource yang bisa dieksekusi</small></button><button class="stat-card" data-go="playbook"><span>Collection</span><strong id="collectionCount">0</strong><small>Koleksi/use case SATUSEHAT</small></button><button class="stat-card" data-go="terminology"><span>Kode Medis</span><strong>5</strong><small>ICD-10 · SNOMED · LOINC · KFA · KPTL</small></button><button class="stat-card" data-go="history"><span>Request History</span><strong id="dashboardHistoryCount">0</strong><small>Metadata request lokal</small></button></div>
<div class="dashboard-grid"><div class="card"><div class="card-title"><h3>Quick Start</h3></div><div class="quick-list"><button data-go="connection"><b>01</b><span><strong>Konfigurasi credential</strong><small>Organization ID, Client ID, Client Secret</small></span><i>→</i></button><button data-go="request"><b>02</b><span><strong>Generate Token</strong><small>OAuth2 client_credentials</small></span><i>→</i></button><button data-go="request"><b>03</b><span><strong>Jalankan request</strong><small>GET / POST / PUT / PATCH / DELETE</small></span><i>→</i></button><button data-go="playbook"><b>04</b><span><strong>Pilih use case</strong><small>Rawat jalan, IGD, farmasi, rujukan, klaim, dan lainnya</small></span><i>→</i></button></div></div><div class="card endpoint-summary"><div class="card-title"><h3>Endpoint Aktif</h3><button class="link-btn" data-go="connection">Detail</button></div><div class="endpoint-mini"><span>OAuth</span><code id="dashboardOauth"></code></div><div class="endpoint-mini"><span>FHIR</span><code id="dashboardFhir"></code></div><div class="endpoint-mini"><span>KFA v2</span><code id="dashboardKfa"></code></div><div class="endpoint-mini"><span>KFA v3</span><code id="dashboardKfaAlkes"></code></div></div></div>
</section>

<section class="content-section" id="section-connection" hidden>
<div class="section-head"><div><h2>Konfigurasi API</h2><p>Credential dikirim dari browser ke backend untuk request dan tidak ditulis ke repository.</p></div></div>
<div class="grid two"><div class="card"><label>Environment</label><select id="environment"><option value="sandbox" <?= $environment === 'sandbox' ? 'selected' : '' ?>>Sandbox / Staging</option><option value="production" <?= $environment === 'production' ? 'selected' : '' ?>>Production</option></select><label>Organization ID</label><input id="organizationId" value="<?= esc($organizationId) ?>" placeholder="1008xxxx"><label>Client ID</label><input id="clientId" autocomplete="off"><label>Client Secret</label><input id="clientSecret" type="password" autocomplete="new-password"><div class="hint">Bisa dikosongkan bila sudah tersedia di <code>.env</code>.</div></div><div class="card endpoint-card"><h3>Endpoint</h3><dl><dt>OAuth</dt><dd id="oauthUrl"></dd><dt>FHIR R4</dt><dd id="fhirUrl"></dd><dt>KFA v2</dt><dd id="kfaUrl"></dd><dt>KFA Alkes v3</dt><dd id="kfaAlkesUrl"></dd></dl><button class="btn primary" id="btnToken">Generate Token</button><label class="mt">Access Token</label><textarea id="accessToken" rows="8" spellcheck="false"></textarea></div></div>
</section>

<section class="content-section" id="section-request" hidden>
<div class="section-head"><div><h2>API Explorer</h2><p>Request disusun mengikuti pola request pada SATUSEHAT Public Postman.</p></div><div class="hero-actions"><button class="btn" id="btnTokenSmall">Refresh Token</button><button class="btn" id="btnValidate">Validasi JSON</button><button class="btn" id="btnCurl">Copy cURL</button></div></div>
<div class="card request-builder"><div class="request-row"><select id="method" class="method"><option>GET</option><option>POST</option><option>PUT</option><option>PATCH</option><option>DELETE</option></select><select id="resource"></select><input id="resourceId" placeholder="Resource ID / path parameter"><button class="btn primary" id="btnSend">Send</button></div><div class="request-url"><span id="requestUrlPreview"></span></div></div>
<div class="grid two editor-grid"><div class="card"><div class="card-title"><h3>Request</h3><button class="link-btn" id="btnExample">Load Example</button></div><label>Query Params</label><textarea id="query" rows="3" placeholder="identifier=https://fhir.kemkes.go.id/id/nik|NIK"></textarea><label>Path ID</label><input id="resourcePath" placeholder="Opsional, contoh: {{patient_ihs}}"><label>JSON Body</label><textarea class="code-editor" id="requestBody" spellcheck="false"></textarea><div class="hint" id="requestHint"></div></div><div class="card response-card"><div class="card-title"><h3>Response</h3><span class="status" id="responseStatus">Belum ada request</span></div><pre id="responseBody">{}</pre></div></div>
</section>

<section class="content-section" id="section-playbook" hidden><div class="section-head"><div><h2>Playbook & Collection</h2><p>Katalog koleksi publik SATUSEHAT dan alur implementasinya.</p></div><a class="btn" href="https://www.postman.com/satusehat/satusehat-public/overview" target="_blank" rel="noopener">Postman Public</a></div><div class="card"><label>Collection</label><select id="collectionSelect"></select><div class="hint" id="collectionHint"></div><div id="playbookSteps" class="quick-list"></div></div></section>

<section class="content-section" id="section-terminology" hidden><div class="section-head"><div><h2>Kode Medis</h2><p>Pencarian coding yang dapat dipakai pada payload FHIR.</p></div><a class="btn" href="https://kodemedis.my.id/" target="_blank" rel="noopener">kodemedis.my.id</a></div><div class="card"><div class="terminology-controls"><div><label>Terminologi</label><select id="terminologyType"><option value="icd10">ICD-10</option><option value="snomed">SNOMED CT</option><option value="loinc">LOINC</option><option value="kfa">KFA</option><option value="kptl">KPTL</option></select></div><div id="kfaProductWrap" hidden><label>Produk KFA</label><select id="kfaProductType"><option value="farmasi">Farmasi</option><option value="alkes">Alat Kesehatan</option></select></div><div class="terminology-query"><label>Kode / istilah</label><input id="terminologyQuery" placeholder="contoh: J40, abdominal pain, EKG, amoxicillin"></div><div><button class="btn primary" id="btnTerminologySearch">Cari</button></div></div><div class="hint" id="terminologyNotice"></div></div><div class="grid two"><div class="card table-card"><table><thead><tr><th>Terminologi</th><th>Kode</th><th>Deskripsi</th><th></th></tr></thead><tbody id="terminologyBody"><tr><td colspan="4" class="empty">Belum ada hasil.</td></tr></tbody></table></div><div class="card coding-card"><div class="card-title"><h3>FHIR Coding</h3><span id="codingStatus" class="status">Belum dipilih</span></div><textarea class="code-editor" id="codingPreview" readonly rows="12"></textarea><div class="hero-actions"><button class="btn" id="btnCopyCoding" disabled>Salin</button><button class="btn primary" id="btnInsertCoding" disabled>Masukkan ke Request</button></div></div></div></section>

<section class="content-section" id="section-history" hidden><div class="section-head"><div><h2>Riwayat Request</h2><p>Hanya metadata request lokal.</p></div><button class="btn" id="btnHistory">Refresh</button></div><div class="card table-card"><table><thead><tr><th>Waktu</th><th>Method</th><th>Target</th><th>Status</th></tr></thead><tbody id="historyBody"><tr><td colspan="4" class="empty">Belum ada data.</td></tr></tbody></table></div></section>
</main></div>
<script>window.APP_BASE=<?= json_encode(rtrim(base_url(), '/')) ?>;</script><script src="<?= base_url('assets/app.js') ?>"></script>
</body></html>
