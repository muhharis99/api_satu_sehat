<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SATUSEHAT Simulator - CI4</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">S</div>
            <div><strong>SATUSEHAT</strong><span>FHIR Simulator CI4</span></div>
        </div>
        <div class="nav-label">Workspace</div>
        <button class="nav-item active" data-section="workspace">FHIR Explorer</button>
        <button class="nav-item" data-section="connection">Konfigurasi API</button>
        <button class="nav-item" data-section="history">Riwayat Request</button>
        <div class="nav-label">Resource cepat</div>
        <div id="resourceNav"></div>
        <div class="sidebar-footer">CodeIgniter 4 · FHIR R4</div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div>
                <h1>SATUSEHAT Simulator</h1>
                <p>Alat bantu testing FHIR untuk fasilitas kesehatan.</p>
            </div>
            <div class="top-actions">
                <span class="pill <?= $environment === 'production' ? 'danger' : '' ?>" id="envBadge"><?= esc(strtoupper($environment)) ?></span>
                <span class="pill neutral"><?= $credentialReady ? 'Credential siap' : 'Credential belum diisi' ?></span>
            </div>
        </header>

        <section class="content-section" id="section-connection" hidden>
            <div class="section-head"><div><h2>Konfigurasi API</h2><p>Credential hanya digunakan saat request dan tidak disimpan ke repository.</p></div></div>
            <div class="grid two">
                <div class="card">
                    <label>Environment</label>
                    <select id="environment"><option value="sandbox" <?= $environment === 'sandbox' ? 'selected' : '' ?>>Sandbox / Staging</option><option value="production" <?= $environment === 'production' ? 'selected' : '' ?>>Production</option></select>
                    <label>Organization ID</label><input id="organizationId" value="<?= esc($organizationId) ?>" placeholder="Contoh: 10083837">
                    <label>Client ID</label><input id="clientId" autocomplete="off" placeholder="Kosongkan jika sudah ada di .env">
                    <label>Client Secret</label><input id="clientSecret" type="password" autocomplete="new-password" placeholder="Kosongkan jika sudah ada di .env">
                    <div class="hint">Disarankan isi credential permanen di file <code>.env</code> lokal.</div>
                </div>
                <div class="card endpoint-card">
                    <h3>Endpoint aktif</h3>
                    <dl><dt>OAuth</dt><dd id="oauthUrl"></dd><dt>FHIR R4</dt><dd id="fhirUrl"></dd></dl>
                    <button class="btn primary" id="btnToken">Ambil Access Token</button>
                    <label class="mt">Access Token</label>
                    <textarea id="accessToken" rows="6" placeholder="Token akan tampil di sini"></textarea>
                </div>
            </div>
        </section>

        <section class="content-section" id="section-workspace">
            <div class="section-head">
                <div><h2>FHIR Explorer</h2><p>GET, POST, PUT, PATCH, dan DELETE resource SATUSEHAT.</p></div>
                <button class="btn" id="btnTokenSmall">Refresh Token</button>
            </div>
            <div class="request-bar card">
                <select id="method" class="method"><option>GET</option><option>POST</option><option>PUT</option><option>PATCH</option><option>DELETE</option></select>
                <select id="resource"></select>
                <input id="resourceId" placeholder="Resource ID (opsional)">
                <button class="btn primary" id="btnSend">Send</button>
            </div>

            <div class="grid two editor-grid">
                <div class="card">
                    <div class="card-title"><h3>Request</h3><button class="link-btn" id="btnExample">Muat contoh</button></div>
                    <label>Query Params</label><input id="query" placeholder="identifier=https://...|123 atau subject=...">
                    <label>JSON Body</label><textarea class="code-editor" id="requestBody" spellcheck="false"></textarea>
                    <div class="hint" id="requestHint">Untuk GET, body dapat dikosongkan.</div>
                </div>
                <div class="card response-card">
                    <div class="card-title"><h3>Response</h3><span class="status" id="responseStatus">Belum ada request</span></div>
                    <pre id="responseBody">{
  "message": "Klik Send untuk mengirim request ke SATUSEHAT"
}</pre>
                </div>
            </div>
        </section>

        <section class="content-section" id="section-history" hidden>
            <div class="section-head"><div><h2>Riwayat Request</h2><p>Hanya metadata request lokal; token dan payload pasien tidak disimpan.</p></div><button class="btn" id="btnHistory">Muat ulang</button></div>
            <div class="card table-card"><table><thead><tr><th>Waktu</th><th>Method</th><th>Target</th><th>Status</th></tr></thead><tbody id="historyBody"><tr><td colspan="4" class="empty">Belum ada data.</td></tr></tbody></table></div>
        </section>
    </main>
</div>
<script>window.APP_BASE = <?= json_encode(rtrim(base_url(), '/')) ?>;</script>
<script src="<?= base_url('assets/app.js') ?>"></script>
</body>
</html>
