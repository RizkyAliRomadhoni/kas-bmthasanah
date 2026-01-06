<x-app-layout>
    <!-- CSS & Font Dashboard Pro -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body, .main-content { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        
        /* Navigasi Modern */
        .btn-nav {
            background: white; color: #475569; font-size: 11px; font-weight: 700; padding: 10px 16px;
            border-radius: 10px; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 8px;
            transition: all 0.2s; text-transform: uppercase; text-decoration: none !important;
        }
        .btn-nav:hover { background: #3b82f6; color: white !important; transform: translateY(-3px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        .btn-nav i { font-size: 13px; }

        /* Card & Stats */
        .card-pro { border: none; border-radius: 1.25rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); background: white; }
        .kpi-card { padding: 1.5rem; border-radius: 1.25rem; border: none; position: relative; overflow: hidden; }
        
        /* Table Sticky Header & Column */
        .table-wrapper { position: relative; max-height: 750px; overflow: auto; border-radius: 1rem; box-shadow: 0 0 0 1px #e2e8f0; }
        .table-neraca { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-neraca thead th { 
            position: sticky; top: 0; z-index: 40; 
            background: #1e293b !important; color: #fff !important; 
            font-size: 11px; padding: 16px; text-transform: uppercase; letter-spacing: 1px;
            border: 1px solid #334155;
        }
        .sticky-column { 
            position: sticky; left: 0; z-index: 30; 
            background: #fff !important; border-right: 2px solid #f1f5f9 !important; 
            font-weight: 700; color: #1e293b; padding-left: 24px !important;
        }
        .table-neraca thead th:first-child { z-index: 50; left: 0; }
        .table-neraca tbody td { padding: 14px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }

        /* Accent Colors */
        .bg-aktiva { background-color: #f0f7ff !important; color: #0061f2; font-weight: 800; }
        .bg-pasiva { background-color: #fff5f5 !important; color: #e74a3b; font-weight: 800; }
        .row-total { background-color: #f8fafc; font-weight: 800; }
        
        .badge-balance { padding: 6px 12px; border-radius: 50px; font-size: 10px; font-weight: 800; }
        .btn-add-row { width: 100%; padding: 14px; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; color: #64748b; font-weight: 700; transition: 0.2s; }
        .btn-add-row:hover { background: #eff6ff; border-color: #3b82f6; color: #3b82f6; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- HEADER UTAMA -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                <div>
                    <h3 class="fw-bolder text-dark mb-0 tracking-tight text-uppercase">Neraca Konsolidasi Hasanah</h3>
                    <p class="text-secondary mb-0">Update Otomatis s/d {{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.location.reload()" class="btn btn-nav shadow-sm bg-white"><i class="fas fa-sync-alt"></i> REFRESH</button>
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-bold" style="background: #3b82f6; border: none;">
                        <i class="fas fa-chart-line me-2"></i> DATA LABA RUGI
                    </a>
                </div>
            </div>

            <!-- KPI CARDS -->
            <div class="row g-4 mb-5 text-start">
                @php
                    $latest = $bulanList->last();
                    $ta_now = 0; foreach($akunAktiva as $a) { $ta_now += $saldo[$a][$latest] ?? 0; }
                    $lr_now = $labaRugiKumulatif[$latest] ?? 0;
                    $kas_now = $saldo['Kas'][$latest] ?? 0;
                @endphp
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-bottom: 4px solid #3b82f6;">
                        <span class="text-xxs font-bold text-secondary uppercase">Total Aset</span>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($ta_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-bottom: 4px solid #10b981;">
                        <span class="text-xxs font-bold text-secondary uppercase">Laba Kumulatif</span>
                        <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($lr_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-bottom: 4px solid #f59e0b;">
                        <span class="text-xxs font-bold text-secondary uppercase">Kas Tersedia</span>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($kas_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-bottom: 4px solid #6366f1;">
                        <span class="text-xxs font-bold text-secondary uppercase">Periode Aktif</span>
                        <h4 class="fw-bold mb-0 text-primary">{{ \Carbon\Carbon::parse($latest)->translatedFormat('M Y') }}</h4>
                    </div>
                </div>
            </div>

            <!-- GRAFIK -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8"><div class="card card-pro p-4 h-100"><canvas id="lineChart"></canvas></div></div>
                <div class="col-lg-4"><div class="card card-pro p-4 h-100"><canvas id="doughnutChart"></canvas></div></div>
            </div>

            <!-- NAVIGASI TOMBOL LENGKAP -->
            <div class="mb-4 d-flex flex-wrap gap-2">
                <a href="{{ route('kambing-akun.index') }}" class="btn-nav shadow-sm"><i class="fas fa-sheep"></i> STOK</a>
                <a href="{{ route('rincian-hpp.index') }}" class="btn-nav shadow-sm text-primary"><i class="fas fa-file-invoice"></i> HPP</a>
                <a href="{{ route('pakan.index') }}" class="btn-nav shadow-sm text-warning"><i class="fas fa-utensils"></i> PAKAN</a>
                <a href="{{ route('kandang.index') }}" class="btn-nav shadow-sm text-info"><i class="fas fa-warehouse"></i> KANDANG</a>
                <a href="{{ route('perlengkapan.index') }}" class="btn-nav shadow-sm text-primary"><i class="fas fa-box"></i> PERLENGKAPAN</a>
                <a href="{{ route('upah.index') }}" class="btn-nav shadow-sm text-secondary"><i class="fas fa-user-tie"></i> UPAH</a>
                <a href="{{ route('operasional.index') }}" class="btn-nav shadow-sm"><i class="fas fa-cogs"></i> OPERASIONAL</a>
                <div class="vr mx-1 d-none d-md-block" style="height: 30px; align-self: center; opacity: 0.1;"></div>
                <a href="{{ route('neraca.penjualan.index') }}" class="btn-nav shadow-sm text-success"><i class="fas fa-shopping-cart"></i> PENJUALAN</a>
                <a href="{{ route('piutang.index') }}" class="btn-nav shadow-sm text-indigo"><i class="fas fa-hand-holding-usd"></i> PIUTANG</a>
                <a href="{{ route('hutang.index') }}" class="btn-nav shadow-sm text-danger"><i class="fas fa-credit-card"></i> HUTANG</a>
            </div>

            <!-- TABEL NERACA UTAMA -->
            <div class="card card-pro overflow-hidden">
                <div class="table-wrapper">
                    <table class="table-neraca text-center mb-0">
                        <thead>
                            <tr>
                                <th class="sticky-column text-start">Klasifikasi Akun</th>
                                <th style="min-width: 130px;">Saldo Awal</th>
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
                                    <td class="text-muted">0</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="font-bold">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr class="row-total text-start border-top">
                                <td class="sticky-column text-start ps-4">TOTAL AKTIVA</td>
                                <td class="fw-bold text-center">0</td>
                                @foreach ($bulanList as $bulan)
                                    @php $ta = 0; foreach($akunAktiva as $a) { $ta += $saldo[$a][$bulan] ?? 0; } @endphp
                                    <td class="text-primary fw-bold text-center">{{ number_format($ta, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- PASIVA -->
                            <tr class="bg-pasiva text-start"><td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3 border-top">II. PASIVA (KEWAJIBAN & MODAL)</td></tr>
                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td class="sticky-column text-start">{{ $akun }}</td>
                                    <td class="text-muted">0</td>
                                    @foreach ($bulanList as $bulan)
                                        <td>{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <td class="sticky-column text-start italic text-secondary ps-5">Modal Awal Tetap</td>
                                <td>200.000.000</td>
                                @foreach ($bulanList as $bulan) <td>200.000.000</td> @endforeach
                            </tr>
                            <tr>
                                <td class="sticky-column text-start fw-bold ps-5">Laba Rugi Tahun Berjalan</td>
                                <td>-</td>
                                @foreach ($bulanList as $bulan)
                                    @php $lr_k = $labaRugiKumulatif[$bulan] ?? 0; @endphp
                                    <td class="fw-bold {{ $lr_k < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $lr_k < 0 ? '-' : '' }}{{ number_format(abs($lr_k), 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="row-total text-start border-top">
                                <td class="sticky-column text-start ps-4">TOTAL PASIVA</td>
                                <td class="fw-bold text-center">0</td>
                                @foreach ($bulanList as $bulan)
                                    @php
                                        $tp = 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                        foreach($akunPasiva as $p) { $tp += $saldo[$p][$bulan] ?? 0; }
                                    @endphp
                                    <td class="text-dark fw-bold text-center">{{ number_format($tp, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- AUDIT BALANCE -->
                            <tr class="bg-white">
                                <td class="sticky-column text-start text-xxs text-secondary py-4 uppercase">Verifikasi Sistem (Audit)</td>
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
                                            <span class="badge badge-balance bg-success text-white">MATCH <i class="fas fa-check-circle ms-1"></i></span>
                                        @else
                                            <span class="badge badge-balance bg-danger text-white">SELISIH Rp{{ number_format($diff,0) }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOMBOL TAMBAH BARIS -->
            <button class="btn-add-row shadow-sm mt-4">
                <i class="fas fa-plus-circle me-2"></i> TAMBAH AKUN / BARIS BARU KE NERACA
            </button>

        </div>
    </main>

    <!-- JS CHARTS -->
    <script>
        // Line Chart
        new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Trend Total Aset',
                    data: @json($chartDataAset),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    fill: true, tension: 0.3
                }, {
                    label: 'Trend Laba Kumulatif',
                    data: @json($chartDataLaba),
                    borderColor: '#10b981',
                    borderDash: [5, 5],
                    tension: 0.3
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });

        // Doughnut Chart
        new Chart(document.getElementById('doughnutChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($akunAktiva),
                datasets: [{
                    data: @json(collect($akunAktiva)->map(fn($a) => $saldo[$a][$latest] ?? 0)),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b']
                }]
            },
            options: { cutout: '75%', plugins: { legend: { display: false } } }
        });
    </script>
</x-app-layout>