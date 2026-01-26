<x-app-layout>
    <!-- Library Eksternal -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* CSS RESET & SCROLL FIX */
        html, body { height: auto !important; overflow-y: auto !important; }
        .main-content { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            min-height: 100vh; 
            padding-bottom: 80px;
        }

        /* CARD STYLE */
        .card-pro { border: none; border-radius: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); background: white; }
        .kpi-card { padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #e2e8f0; background: white; transition: 0.3s; }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

        /* NAVIGATION MENU (LENGKAP) */
        .nav-container { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 25px; }
        .btn-nav { 
            background: white; border: 1px solid #e2e8f0; color: #475569; padding: 10px 16px; 
            border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase;
            display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; text-decoration: none !important;
        }
        .btn-nav:hover { background: #3b82f6; color: white !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); border-color: #3b82f6; }
        .btn-nav i { font-size: 14px; }
        .btn-nav.active { background: #1e293b; color: white; border-color: #1e293b; }

        /* STICKY TABLE CONFIGURATION (SOLID & ACCURATE) */
        .table-wrapper { position: relative; max-height: 600px; overflow: auto; border-radius: 12px; border: 1px solid #e2e8f0; background: white; }
        .table-neraca { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-neraca thead th { 
            position: sticky; top: 0; z-index: 100; 
            background: #1e293b !important; color: #fff !important; 
            font-size: 11px; padding: 16px; text-transform: uppercase; border: 1px solid #334155;
        }
        .sticky-column { 
            position: sticky; left: 0; z-index: 90; 
            background: #ffffff !important; border-right: 2px solid #f1f5f9 !important; 
            font-weight: 700; color: #1e293b; box-shadow: 2px 0 5px rgba(0,0,0,0.02);
        }
        .table-neraca thead th:first-child { z-index: 110; left: 0; }
        .table-neraca tbody td { padding: 14px; font-size: 13px; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }

        /* COLORS & TYPOGRAPHY */
        .bg-aktiva { background-color: #f0f7ff !important; color: #2563eb; font-weight: 800; }
        .bg-pasiva { background-color: #fff5f5 !important; color: #dc2626; font-weight: 800; }
        .row-total { background-color: #f8fafc; font-weight: 800; color: #1e293b; }
        .chart-box { position: relative; height: 320px; width: 100%; }

        /* ADD ROW BUTTON */
        .btn-add-row { 
            width: 100%; padding: 16px; background: #ffffff; border: 2px dashed #cbd5e1; 
            border-radius: 15px; color: #64748b; font-weight: 700; transition: 0.2s; cursor: pointer;
        }
        .btn-add-row:hover { background: #f0f7ff; border-color: #3b82f6; color: #3b82f6; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- 1. HEADER UTAMA -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                <div class="text-start">
                    <h3 class="fw-bolder text-dark mb-0 uppercase tracking-tighter" style="font-family: 'Plus Jakarta Sans'; font-size: 24px;">Neraca Konsolidasi Hasanah</h3>
                    <p class="text-secondary mb-0" style="font-size: 14px;">Update Real-time: {{ now()->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div class="d-flex gap-2 text-start">
                    <button onclick="window.location.reload()" class="btn btn-nav shadow-sm bg-white"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn px-4 py-2 text-white shadow-sm" style="background: #3b82f6; border-radius: 50px; font-weight: 700; font-size: 12px; text-decoration: none;">
                        <i class="fas fa-chart-line me-2"></i> DATA LABA RUGI
                    </a>
                </div>
            </div>

            <!-- 2. KPI STATS CARDS -->
            <div class="row g-4 mb-5 text-start">
                @php
                    $latest = $bulanList->last();
                    $ta_now = 0; foreach($akunAktiva as $a) { $ta_now += $saldo[$a][$latest] ?? 0; }
                    $lr_now = $labaRugiKumulatif[$latest] ?? 0;
                    $kas_now = $saldo['Kas'][$latest] ?? 0;
                @endphp
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid #3b82f6;">
                        <span class="text-xxs font-bold text-secondary uppercase">Total Aset (Aktiva)</span>
                        <h4 class="fw-bold mb-0 mt-1">Rp {{ number_format($ta_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid #10b981;">
                        <span class="text-xxs font-bold text-secondary uppercase">Laba Akumulasi</span>
                        <h4 class="fw-bold mb-0 mt-1 text-success">Rp {{ number_format($lr_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid #f59e0b;">
                        <span class="text-xxs font-bold text-secondary uppercase">Saldo Kas Utama</span>
                        <h4 class="fw-bold mb-0 mt-1">Rp {{ number_format($kas_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid #6366f1;">
                        <span class="text-xxs font-bold text-secondary uppercase">Periode Aktif</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary">{{ \Carbon\Carbon::parse($latest)->translatedFormat('M Y') }}</h4>
                    </div>
                </div>
            </div>

            <!-- 3. GRAFIK ANALISIS -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <div class="card card-pro p-4 h-100 shadow-sm text-start text-start">
                        <h6 class="fw-bold text-dark mb-4 text-uppercase" style="font-size: 12px; letter-spacing: 1px;">Tren Pertumbuhan Keuangan</h6>
                        <div class="chart-box"><canvas id="lineChart"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-pro p-4 h-100 shadow-sm text-start text-start">
                        <h6 class="fw-bold text-dark mb-4 text-uppercase" style="font-size: 12px; letter-spacing: 1px;">Komposisi Aset</h6>
                        <div class="chart-box"><canvas id="doughnutChart"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- 4. MENU NAVIGASI (LENGKAP SEMUA MODUL) -->
            <div class="nav-container text-start">
                 <a href="{{ route('rincian-kambing.index') }}" class="btn-nav shadow-sm"><i class="fas fa-sheep text-dark"></i> rincian kambing</a>
                <a href="{{ route('rincian-hpp.index') }}" class="btn-nav shadow-sm text-primary"><i class="fas fa-calculator"></i> detail transaksi kambing</a>
                <a href="{{ route('pakan.index') }}" class="btn-nav shadow-sm text-warning"><i class="fas fa-utensils"></i> Pakan</a>
                <a href="{{ route('kandang.index') }}" class="btn-nav shadow-sm text-info"><i class="fas fa-warehouse"></i> Kandang</a>
                <a href="{{ route('perlengkapan.index') }}" class="btn-nav shadow-sm text-primary"><i class="fas fa-box"></i> Perlengkapan</a>
                <a href="{{ route('upah.index') }}" class="btn-nav shadow-sm text-secondary"><i class="fas fa-user-tie"></i> Upah</a>
                <a href="{{ route('operasional.index') }}" class="btn-nav shadow-sm"><i class="fas fa-cogs text-secondary"></i> Operasional</a>
                <a href="{{ route('neraca.penjualan.index') }}" class="btn-nav shadow-sm text-success"><i class="fas fa-shopping-cart"></i> Penjualan</a>
                <a href="{{ route('piutang.index') }}" class="btn-nav shadow-sm text-indigo"><i class="fas fa-hand-holding-usd"></i> Piutang</a>
                <a href="{{ route('hutang.index') }}" class="btn-nav shadow-sm text-danger"><i class="fas fa-credit-card"></i> Hutang</a>
            </div>


            <!-- 5. TABEL NERACA (STIKCY & PROFESIONAL) -->

            <div class="card card-pro overflow-hidden border-0 shadow-sm">
                <div class="table-wrapper">
                    <table class="table-neraca text-center mb-0">
                        
             
                        <thead>

                            <tr>
                                <th class="sticky-column text-start ps-4">Klasifikasi Akun Laporan</th>
                                <th style="min-width: 120px;">Saldo Awal</th>
                                @foreach ($bulanList as $bulan)
                                    <th style="min-width: 150px;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M Y') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- AKTIVA -->
                            <tr class="bg-aktiva text-start"><td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3">I. AKTIVA (ASET)</td></tr>
                            @foreach ($akunAktiva as $akun)
                                <tr>
                                    <td class="sticky-column text-start">{{ $akun }}</td>
                                    <td class="text-muted text-xs">0</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="fw-bold text-dark">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr class="row-total border-top">
                                <td class="sticky-column text-start ps-4">TOTAL AKTIVA (A)</td>
                                <td class="fw-bold">0</td>
                                @foreach ($bulanList as $bulan)
                                    @php $ta = 0; foreach($akunAktiva as $a) { $ta += $saldo[$a][$bulan] ?? 0; } @endphp
                                    <td class="text-primary fw-bold">{{ number_format($ta, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- PASIVA -->
                            <tr class="bg-pasiva text-start"><td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3 border-top">II. PASIVA (KEWAJIBAN & MODAL)</td></tr>
                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td class="sticky-column text-start">{{ $akun }}</td>
                                    <td class="text-muted text-xs">0</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-dark">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <td class="sticky-column text-start italic text-secondary ps-5">Modal Awal Tetap</td>
                                <td class="text-muted">200.000.000</td>
                                @foreach ($bulanList as $bulan) <td class="text-dark">200.000.000</td> @endforeach
                            </tr>
                            <tr>
                                <td class="sticky-column text-start fw-bold ps-5">Laba Rugi Tahun Berjalan</td>
                                <td class="text-muted">-</td>
                                @foreach ($bulanList as $bulan)
                                    @php $lr_k = $labaRugiKumulatif[$bulan] ?? 0; @endphp
                                    <td class="fw-bold {{ $lr_k < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $lr_k < 0 ? '-' : '' }}{{ number_format(abs($lr_k), 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="row-total border-top">
                                <td class="sticky-column text-start ps-4">TOTAL PASIVA (B)</td>
                                <td class="fw-bold">0</td>
                                @foreach ($bulanList as $bulan)
                                    @php
                                        $tp = 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                        foreach($akunPasiva as $p) { $tp += $saldo[$p][$bulan] ?? 0; }
                                    @endphp
                                    <td class="text-dark fw-bold">{{ number_format($tp, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- AUDIT VERIFICATION -->
                            <tr class="bg-white">
                                <td class="sticky-column text-start text-xxs text-secondary py-4 uppercase">Status Verifikasi Sistem</td>
                                <td></td>
                                @foreach ($bulanList as $bulan)
                                    @php
                                        $ta_b = 0; foreach($akunAktiva as $a) { $ta_b += $saldo[$a][$bulan] ?? 0; }
                                        $tp_b = 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                        foreach($akunPasiva as $p) { $tp_b += $saldo[$p][$bulan] ?? 0; }
                                        $diff = abs($ta_b - $tp_b);
                                    @endphp
                                    <td>
                                        @if($diff < 1000)
                                            <span class="badge bg-success rounded-pill px-3 py-2 text-xxs">BALANCE <i class="fas fa-check-circle ms-1"></i></span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-3 py-2 text-xxs">SELISIH Rp{{ number_format($diff,0) }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 6. TOMBOL TAMBAH BARIS -->
            <button class="btn-add-row shadow-sm mt-4 mb-5" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                <i class="fas fa-plus-circle me-2"></i> TAMBAH AKUN / BARIS BARU KE NERACA
            </button>

        </div>
    </main>

    <!-- MODAL TAMBAH AKUN -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('neraca.add-account') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                @csrf
                <div class="modal-body p-4 text-start">
                    <h5 class="fw-bold text-primary mb-3 text-center uppercase">Tambah Akun Baru</h5>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-secondary uppercase">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control rounded-3" placeholder="Contoh: Tabungan" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-secondary uppercase">Tipe</label>
                        <select name="tipe" class="form-select rounded-3" required>
                            <option value="Aktiva">Aktiva (Aset)</option>
                            <option value="Pasiva">Pasiva (Kewajiban)</option>
                        </select>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow font-bold border-0">SIMPAN AKUN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS CHARTS -->
    <script>
        // 1. Line Chart
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Trend Total Aset',
                    data: @json($chartDataAset),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                    fill: true, tension: 0.4, pointRadius: 5
                }, {
                    label: 'Laba Akumulasi',
                    data: @json($chartDataLaba),
                    borderColor: '#10b981',
                    borderDash: [5, 5],
                    tension: 0.4, pointRadius: 5
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { font: { weight: 'bold', size: 11 } } } },
                scales: { y: { ticks: { callback: (v) => 'Rp ' + v.toLocaleString('id-ID') } } }
            }
        });

        // 2. Pie Chart
        new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: @json($akunAktiva),
                datasets: [{
                    data: @json(collect($akunAktiva)->map(fn($a) => $saldo[$a][$latest] ?? 0)),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6366f1', '#ef4444', '#06b6d4', '#ec4899', '#64748b']
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>