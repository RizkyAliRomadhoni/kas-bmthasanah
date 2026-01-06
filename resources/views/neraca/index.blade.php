<x-app-layout>
    <!-- Google Font: Inter & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <!-- Chart.js & FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Modern UI Variable & Base */
        :root {
            --primary-blue: #3b82f6;
            --dark-slate: #1e293b;
            --success-green: #10b981;
            --danger-red: #ef4444;
            --bg-body: #f8fafc;
        }

        body, .main-content { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-body); 
            color: #334155;
        }

        .main-content { padding-bottom: 50px; }

        /* Card Professional Styling */
        .card-modern { 
            border: none; 
            border-radius: 1rem; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); 
            background: #fff; 
        }
        
        /* KPI Stats Card */
        .kpi-card { 
            padding: 1.25rem; 
            border-radius: 1rem; 
            border: 1px solid #e2e8f0; 
            background: white; 
            transition: transform 0.3s ease; 
        }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

        /* Navigasi Button Pro */
        .btn-nav {
            background: #ffffff; color: #475569; font-size: 11px; font-weight: 700; 
            padding: 10px 18px; border-radius: 12px; border: 1px solid #e2e8f0;
            display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;
            text-transform: uppercase; text-decoration: none !important;
        }
        .btn-nav:hover { 
            background: var(--primary-blue); color: #ffffff !important; 
            border-color: var(--primary-blue); transform: translateY(-2px); 
        }

        /* Sticky Table Configuration - Anti-Amburadul */
        .table-wrapper { 
            position: relative; 
            max-height: 650px; 
            overflow: auto; 
            border-radius: 12px; 
            border: 1px solid #e2e8f0;
            background: white;
        }
        .table-neraca { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-neraca thead th { 
            position: sticky; top: 0; z-index: 40; 
            background: var(--dark-slate) !important; color: #fff !important; 
            font-size: 11px; padding: 16px; text-transform: uppercase; 
            border: 1px solid #334155;
            letter-spacing: 0.5px;
        }
        .sticky-column { 
            position: sticky; left: 0; z-index: 30; 
            background: #ffffff !important; border-right: 2px solid #f1f5f9 !important; 
            font-weight: 700; color: var(--dark-slate);
        }
        .table-neraca thead th:first-child { z-index: 50; left: 0; }
        .table-neraca tbody td { padding: 14px; font-size: 13px; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }

        /* Typography & Coloring */
        .header-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; letter-spacing: -1px; }
        .bg-section-aktiva { background-color: #f0f7ff !important; color: #2563eb; font-weight: 800; text-align: left !important; }
        .bg-section-pasiva { background-color: #fff5f5 !important; color: #dc2626; font-weight: 800; text-align: left !important; }
        .row-total { background-color: #f8fafc; font-weight: 800; color: var(--dark-slate); }
        .text-money { font-family: 'Inter', sans-serif; font-weight: 600; }

        /* Chart Container Fix */
        .chart-container { position: relative; height: 300px; width: 100%; }

        /* Audit Badge */
        .badge-balance { padding: 6px 14px; border-radius: 50px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: white; }
    </style>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- HEADER UTAMA -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                <div class="text-start">
                    <h3 class="header-title text-dark mb-0">NERACA KEUANGAN</h3>
                    <p class="text-secondary mb-0" style="font-size: 14px;">Hasanah Farm • Rekapitulasi Riil & Akumulasi Otomatis</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.location.reload()" class="btn btn-nav shadow-sm bg-white">
                        <i class="fas fa-sync-alt"></i> Refresh Data
                    </button>
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn px-4 py-2 text-white shadow-sm" style="background: var(--primary-blue); border-radius: 50px; font-weight: 700; font-size: 12px; text-decoration: none;">
                        <i class="fas fa-chart-line me-2"></i> DATA LABA RUGI
                    </a>
                </div>
            </div>

            <!-- KPI SUMMARY CARDS -->
            <div class="row g-4 mb-5 text-start">
                @php
                    $latestMonth = $bulanList->last();
                    $ta_now = 0; foreach($akunAktiva as $a) { $ta_now += $saldo[$a][$latestMonth] ?? 0; }
                    $lr_now = $labaRugiKumulatif[$latestMonth] ?? 0;
                    $kas_now = $saldo['Kas'][$latestMonth] ?? 0;
                    $hutang_now = $saldo['Hutang'][$latestMonth] ?? 0;
                @endphp
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid var(--primary-blue);">
                        <span class="text-xs font-bold text-secondary uppercase">Total Aset (Aktiva)</span>
                        <h4 class="fw-bold mb-0 mt-1">Rp {{ number_format($ta_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid var(--success-green);">
                        <span class="text-xs font-bold text-secondary uppercase">Laba Akumulasi</span>
                        <h4 class="fw-bold mb-0 mt-1 text-success">Rp {{ number_format($lr_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid #f59e0b;">
                        <span class="text-xs font-bold text-secondary uppercase">Kas Tersedia</span>
                        <h4 class="fw-bold mb-0 mt-1">Rp {{ number_format($kas_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="kpi-card shadow-sm" style="border-left: 5px solid #6366f1;">
                        <span class="text-xs font-bold text-secondary uppercase">Bulan Laporan</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary">{{ \Carbon\Carbon::parse($latestMonth)->translatedFormat('M Y') }}</h4>
                    </div>
                </div>
            </div>

            <!-- GRAFIK ANALISIS (DIPERBAIKI) -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <div class="card card-modern p-4 h-100 shadow-sm text-start">
                        <h6 class="fw-bold text-dark mb-4 uppercase" style="font-size: 12px; letter-spacing: 1px;">Tren Pertumbuhan Keuangan</h6>
                        <div class="chart-container">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-modern p-4 h-100 shadow-sm text-start">
                        <h6 class="fw-bold text-dark mb-4 uppercase" style="font-size: 12px; letter-spacing: 1px;">Komposisi Aset</h6>
                        <div class="chart-container">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NAVIGASI KELOLA AKUN -->
            <div class="mb-4 d-flex flex-wrap gap-2">
                <a href="{{ route('kambing-akun.index') }}" class="btn-nav shadow-sm"><i class="fas fa-sheep"></i> STOK</a>
                <a href="{{ route('rincian-hpp.index') }}" class="btn-nav shadow-sm text-primary border-primary"><i class="fas fa-file-invoice-dollar"></i> RINCIAN HPP</a>
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

            <!-- TABEL NERACA -->
            <div class="card card-modern shadow-sm border-0">
                <div class="table-wrapper">
                    <table class="table-neraca align-middle mb-0 text-center">
                        <thead>
                            <tr>
                                <th class="sticky-column text-start ps-4">Klasifikasi Akun Laporan</th>
                                <th style="min-width: 130px;">Awal Periode</th>
                                @foreach ($bulanList as $bulan)
                                    <th style="min-width: 150px;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M Y') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- SEKSI I: AKTIVA -->
                            <tr class="bg-section-aktiva">
                                <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3">I. AKTIVA (ASET & INVENTARIS)</td>
                            </tr>
                            @foreach ($akunAktiva as $akun)
                                <tr>
                                    <td class="sticky-column text-start ps-5">{{ $akun }}</td>
                                    <td class="text-muted text-money">0</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="fw-bold text-money text-dark">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr class="row-total border-top">
                                <td class="sticky-column text-start ps-4">TOTAL AKTIVA (A)</td>
                                <td class="fw-bold text-money">0</td>
                                @foreach ($bulanList as $bulan)
                                    @php $ta = 0; foreach($akunAktiva as $a) { $ta += $saldo[$a][$bulan] ?? 0; } @endphp
                                    <td class="text-primary fw-bold text-money">{{ number_format($ta, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- SEKSI II: PASIVA -->
                            <tr class="bg-section-pasiva">
                                <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3 border-top">II. PASIVA (KEWAJIBAN & MODAL)</td>
                            </tr>
                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td class="sticky-column text-start ps-5">{{ $akun }}</td>
                                    <td class="text-muted text-money">0</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-money text-dark">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <td class="sticky-column text-start ps-5 italic text-secondary">Modal Awal Tetap (Investasi)</td>
                                <td class="text-money">200.000.000</td>
                                @foreach ($bulanList as $bulan) <td class="text-money text-dark">200.000.000</td> @endforeach
                            </tr>
                            <tr>
                                <td class="sticky-column text-start fw-bold ps-5">Laba Rugi Tahun Berjalan (Akumulasi)</td>
                                <td class="text-money">-</td>
                                @foreach ($bulanList as $bulan)
                                    @php $lr_k = $labaRugiKumulatif[$bulan] ?? 0; @endphp
                                    <td class="fw-bold text-money {{ $lr_k < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $lr_k < 0 ? '-' : '' }}{{ number_format(abs($lr_k), 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="row-total border-top">
                                <td class="sticky-column text-start ps-4">TOTAL PASIVA (B)</td>
                                <td class="fw-bold text-money">0</td>
                                @foreach ($bulanList as $bulan)
                                    @php
                                        $tp = 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                        foreach($akunPasiva as $p) { $tp += $saldo[$p][$bulan] ?? 0; }
                                    @endphp
                                    <td class="text-dark fw-bold text-money">{{ number_format($tp, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- AUDIT VERIFICATION -->
                            <tr class="bg-white">
                                <td class="sticky-column text-start text-xxs text-secondary py-4 uppercase">Status Audit Balance</td>
                                <td></td>
                                @foreach ($bulanList as $bulan)
                                    @php
                                        $ta_check = 0; foreach($akunAktiva as $a) { $ta_check += $saldo[$a][$bulan] ?? 0; }
                                        $tp_check = 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                        foreach($akunPasiva as $p) { $tp_check += $saldo[$p][$bulan] ?? 0; }
                                        $diff = abs($ta_check - $tp_check);
                                    @endphp
                                    <td>
                                        @if($diff < 1000)
                                            <span class="badge-balance bg-success">MATCH <i class="fas fa-check-circle ms-1"></i></span>
                                        @else
                                            <span class="badge-balance bg-danger">SELISIH Rp{{ number_format($diff,0) }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOMBOL TAMBAH BARIS -->
            <button class="btn btn-block py-3 mt-4 w-100" data-bs-toggle="modal" data-bs-target="#addAccountModal" style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; color: #64748b; font-weight: 700;">
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
                    <h5 class="fw-bold text-primary mb-3 text-center">Tambah Akun Neraca Baru</h5>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-secondary uppercase">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control rounded-3" placeholder="Contoh: Investasi Saham" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-bold text-secondary uppercase">Tipe Akun</label>
                        <select name="tipe" class="form-select rounded-3" required>
                            <option value="Aktiva">Aktiva (Aset/Harta)</option>
                            <option value="Pasiva">Pasiva (Hutang/Kewajiban)</option>
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

    <!-- JS CHARTS (FIXED CONFIG) -->
    <script>
        // 1. Line Chart: Trend Pertumbuhan
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Aset',
                    data: @json($chartDataAset),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5
                }, {
                    label: 'Laba Akumulasi',
                    data: @json($chartDataLaba),
                    borderColor: '#10b981',
                    borderDash: [5, 5],
                    tension: 0.4,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { font: { weight: 'bold' } } } },
                scales: { 
                    y: { ticks: { callback: (v) => 'Rp ' + v.toLocaleString('id-ID') } }
                }
            }
        });

        // 2. Doughnut Chart: Komposisi
        new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: @json($akunAktiva),
                datasets: [{
                    data: @json(collect($akunAktiva)->map(fn($a) => $saldo[$a][$latestMonth] ?? 0)),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6366f1', '#ef4444', '#06b6d4', '#ec4899', '#64748b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>