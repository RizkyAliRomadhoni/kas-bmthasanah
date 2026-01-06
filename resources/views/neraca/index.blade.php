<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Font & Library Eksternal -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Base Styling - Modern Dashboard */
        body, .main-content { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b; 
        }

        .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: #fff; }
        .kpi-card { padding: 1.25rem; border-radius: 16px; transition: all 0.3s ease; border: 1px solid #f1f5f9; }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }

        /* Navigasi Menu */
        .btn-nav {
            background: #ffffff; color: #475569; font-size: 11px; font-weight: 700; 
            padding: 10px 16px; border-radius: 10px; border: 1px solid #e2e8f0;
            display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;
            text-transform: uppercase; text-decoration: none !important;
        }
        .btn-nav:hover { background: #3b82f6; color: #ffffff !important; transform: translateY(-2px); border-color: #3b82f6; }
        .btn-nav i { font-size: 13px; }

        /* Sticky Table Logic - Anti Amburadul */
        .table-container { 
            position: relative; 
            max-height: 700px; 
            overflow: auto; 
            border-radius: 12px; 
            border: 1px solid #e2e8f0;
            background: white;
        }
        .table-neraca { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-neraca thead th { 
            position: sticky; top: 0; z-index: 40; 
            background: #1e293b !important; color: #fff !important; 
            font-size: 11px; padding: 16px; text-transform: uppercase; 
            border: 1px solid #334155;
            letter-spacing: 0.5px;
        }
        .sticky-column { 
            position: sticky; left: 0; z-index: 30; 
            background: #ffffff !important; border-right: 2px solid #f1f5f9 !important; 
            font-weight: 700; color: #1e293b;
        }
        /* Corner header fix */
        .table-neraca thead th:first-child { z-index: 50; left: 0; }

        .table-neraca tbody td { padding: 14px; font-size: 13px; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }

        /* Row Accents */
        .bg-section-aktiva { background-color: #f0f7ff !important; color: #2563eb; font-weight: 800; text-align: left !important; }
        .bg-section-pasiva { background-color: #fff5f5 !important; color: #dc2626; font-weight: 800; text-align: left !important; }
        .row-total { background-color: #f8fafc; font-weight: 800; color: #1e293b; }
        
        /* Badges */
        .badge-balance { padding: 6px 12px; border-radius: 50px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        
        /* Add Account Button */
        .btn-add-account { 
            width: 100%; padding: 15px; background: #f8fafc; 
            border: 2px dashed #cbd5e1; border-radius: 12px; 
            color: #64748b; font-weight: 700; transition: 0.2s; 
            margin-top: 20px;
        }
        .btn-add-account:hover { background: #eff6ff; border-color: #3b82f6; color: #3b82f6; }

        .text-money { font-family: 'Inter', sans-serif; font-weight: 600; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- HEADER UTAMA -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                <div class="text-start">
                    <h3 class="fw-bold text-dark mb-0 tracking-tight" style="font-weight: 800;">NERACA KEUANGAN</h3>
                    <p class="text-secondary mb-0" style="font-size: 14px;">Hasanah Farm • Laporan Riil & Akumulasi Otomatis</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.location.reload()" class="btn btn-nav shadow-sm bg-white">
                        <i class="fas fa-sync-alt"></i> REFRESH
                    </button>
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-primary shadow-sm border-0 px-4 py-2" style="background: #3b82f6; border-radius: 50px; font-weight: 700; font-size: 12px;">
                        <i class="fas fa-chart-line me-2"></i> DATA LABA RUGI
                    </a>
                </div>
            </div>

            <!-- KPI SUMMARY CARDS -->
            <div class="row g-4 mb-5 text-start">
                @php
                    $latest = $bulanList->last();
                    $ta_now = 0; foreach($akunAktiva as $a) { $ta_now += $saldo[$a][$latest] ?? 0; }
                    $lr_now = $labaRugiKumulatif[$latest] ?? 0;
                    $kas_now = $saldo['Kas'][$latest] ?? 0;
                    $hutang_now = $saldo['Hutang'][$latest] ?? 0;
                @endphp
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-left: 5px solid #3b82f6;">
                        <span class="text-xs font-bold text-secondary uppercase">Total Aset (Aktiva)</span>
                        <h4 class="fw-bold mb-0 mt-1">Rp {{ number_format($ta_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-left: 5px solid #10b981;">
                        <span class="text-xs font-bold text-secondary uppercase">Laba Akumulasi</span>
                        <h4 class="fw-bold mb-0 mt-1 text-success">Rp {{ number_format($lr_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-left: 5px solid #f59e0b;">
                        <span class="text-xs font-bold text-secondary uppercase">Kas Tersedia</span>
                        <h4 class="fw-bold mb-0 mt-1">Rp {{ number_format($kas_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card kpi-card shadow-sm bg-white" style="border-left: 5px solid #ef4444;">
                        <span class="text-xs font-bold text-secondary uppercase">Total Hutang</span>
                        <h4 class="fw-bold mb-0 mt-1 text-danger">Rp {{ number_format($hutang_now, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            <!-- GRAFIK ANALISIS -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <div class="card card-modern p-4 h-100 shadow-sm text-start">
                        <h6 class="fw-bold text-dark mb-4 uppercase" style="font-size: 12px; letter-spacing: 1px;">Tren Pertumbuhan Keuangan</h6>
                        <canvas id="lineChart" height="120"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-modern p-4 h-100 shadow-sm text-start">
                        <h6 class="fw-bold text-dark mb-4 uppercase" style="font-size: 12px; letter-spacing: 1px;">Komposisi Aktiva (Terbaru)</h6>
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- NAVIGASI TOMBOL KELOLA AKUN -->
            <div class="mb-4 d-flex flex-wrap gap-2 justify-content-start">
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
                <div class="table-container">
                    <table class="table table-neraca align-middle mb-0 text-center">
                        <thead>
                            <tr>
                                <th class="sticky-column text-start ps-4">Klasifikasi Akun Laporan</th>
                                <th style="min-width: 130px;">Saldo Awal</th>
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
                                        <td class="fw-bold text-money">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
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
                                        <td class="text-money">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <td class="sticky-column text-start ps-5 italic text-secondary">Modal Awal Tetap (Investasi)</td>
                                <td class="text-money">200.000.000</td>
                                @foreach ($bulanList as $bulan) <td class="text-money">200.000.000</td> @endforeach
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
                                <td class="sticky-column text-start text-xxs text-secondary py-4 uppercase">Verifikasi Balance (A - B)</td>
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
                                            <span class="badge-balance bg-success" style="color: white;">MATCH <i class="fas fa-check-circle ms-1"></i></span>
                                        @else
                                            <span class="badge-balance bg-danger" style="color: white;">SELISIH Rp{{ number_format($diff,0) }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOMBOL TAMBAH BARIS -->
            <button class="btn-add-account shadow-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">
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

    <!-- SCRIPTS CHARTS -->
    <script>
        // 1. Line Chart: Trend Aset vs Laba
        new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Trend Total Aset',
                    data: @json($chartDataAset),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    fill: true, tension: 0.3, pointRadius: 4
                }, {
                    label: 'Trend Laba Akumulasi',
                    data: @json($chartDataLaba),
                    borderColor: '#10b981',
                    borderDash: [5, 5],
                    tension: 0.3, pointRadius: 4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { position: 'top', labels: { font: { family: 'Inter', size: 11, weight: 'bold' } } } },
                scales: { y: { ticks: { font: { size: 10 } } }, x: { ticks: { font: { size: 10 } } } }
            }
        });

        // 2. Doughnut Chart: Komposisi Aktiva Terbaru
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
    </main>
</x-app-layout>