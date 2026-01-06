<x-app-layout>
    <!-- Tambahkan library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.95);
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            --success-grad: linear-gradient(135deg, #34d399 0%, #059669 100%);
            --danger-grad: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
        }

        .main-content { background-color: #f1f5f9; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Glassmorphism Effect */
        .card-modern {
            background: var(--glass-bg);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Nav Buttons */
        .btn-nav {
            background: white;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-nav:hover {
            transform: translateY(-2px);
            border-color: #6366f1;
            color: #6366f1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Stats Card */
        .stat-card { padding: 1.5rem; position: relative; overflow: hidden; }
        .stat-card::after {
            content: ''; position: absolute; right: -10%; top: -10%; 
            width: 100px; height: 100px; background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        /* Table Styling */
        .table-neraca thead th {
            background: #f8fafc;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1.25rem 1rem;
        }
        .table-neraca tbody td { padding: 1rem; font-size: 0.875rem; border-bottom: 1px solid #f1f5f9; }
        .sticky-col { position: sticky; left: 0; background: white; z-index: 5; font-weight: 600; }
        
        .bg-primary-soft { background-color: #eef2ff !important; color: #4338ca; }
        .bg-danger-soft { background-color: #fef2f2 !important; color: #b91c1c; }

        /* Animation */
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .refresh-icon:active i { animation: spin 0.8s ease; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- TOP HEADER -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                <div>
                    <h2 class="fw-bold text-slate-900 mb-1 tracking-tight">Neraca Konsolidasi</h2>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success-soft px-3 py-2 rounded-pill">Status: Aktif</span>
                        <span class="text-secondary text-sm">Hasanah Farm • Update Terakhir: {{ now()->format('d M, H:i') }}</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="" class="btn btn-nav refresh-icon shadow-sm" onclick="location.reload(); return false;">
                        <i class="fas fa-sync-alt"></i> Refresh Data
                    </a>
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-primary shadow-sm rounded-pill px-4" style="background: var(--primary-grad); border:none;">
                        <i class="fas fa-chart-line me-2"></i> Laporan Laba Rugi
                    </a>
                </div>
            </div>

            <!-- KPI STATS CARDS -->
            <div class="row g-4 mb-5">
                @php
                    $latestBulan = $bulanList->last();
                    $lastAset = 0;
                    foreach($akunAktiva as $a) { $lastAset += $saldo[$a][$latestBulan] ?? 0; }
                    $lastHutang = 0;
                    foreach($akunPasiva as $p) { $lastHutang += $saldo[$p][$latestBulan] ?? 0; }
                @endphp
                <div class="col-md-3">
                    <div class="card card-modern stat-card text-white" style="background: var(--primary-grad);">
                        <p class="text-sm opacity-8 mb-1 uppercase font-semibold">Total Aset (Aktiva)</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($lastAset, 0, ',', '.') }}</h3>
                        <i class="fas fa-wallet position-absolute end-0 bottom-0 m-3 opacity-2" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-modern stat-card text-white" style="background: var(--danger-grad);">
                        <p class="text-sm opacity-8 mb-1 uppercase font-semibold">Total Kewajiban</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($lastHutang, 0, ',', '.') }}</h3>
                        <i class="fas fa-hand-holding-usd position-absolute end-0 bottom-0 m-3 opacity-2" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-modern stat-card text-white" style="background: var(--success-grad);">
                        <p class="text-sm opacity-8 mb-1 uppercase font-semibold">Laba Akumulasi</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($labaRugiKumulatif[$latestBulan] ?? 0, 0, ',', '.') }}</h3>
                        <i class="fas fa-chart-area position-absolute end-0 bottom-0 m-3 opacity-2" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-modern stat-card" style="background: white;">
                        <p class="text-secondary text-sm mb-1 uppercase font-semibold">Saldo Kas</p>
                        <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($saldo['Kas'][$latestBulan] ?? 0, 0, ',', '.') }}</h3>
                        <i class="fas fa-coins position-absolute end-0 bottom-0 m-3 text-warning opacity-2" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>

            <!-- GRAFIK SECTION -->
            <div class="row mb-5 g-4">
                <div class="col-lg-8">
                    <div class="card card-modern p-4">
                        <h6 class="fw-bold text-dark mb-4 uppercase">Tren Pertumbuhan Keuangan</h6>
                        <canvas id="trendChart" height="100"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-modern p-4">
                        <h6 class="fw-bold text-dark mb-4 uppercase">Komposisi Aktiva</h6>
                        <canvas id="compositionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- MENU NAVIGATION -->
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('kambing-akun.index') }}" class="btn-nav"><i class="fas fa-sheep"></i> Stok Kambing</a>
                    <a href="{{ route('rincian-hpp.index') }}" class="btn-nav text-primary"><i class="fas fa-file-invoice-dollar"></i> Rincian HPP</a>
                    <a href="{{ route('pakan.index') }}" class="btn-nav text-warning"><i class="fas fa-utensils"></i> Pakan</a>
                    <a href="{{ route('kandang.index') }}" class="btn-nav text-info"><i class="fas fa-tools"></i> Kandang</a>
                    <a href="{{ route('neraca.penjualan.index') }}" class="btn-nav text-success"><i class="fas fa-shopping-cart"></i> Penjualan</a>
                    <a href="{{ route('piutang.index') }}" class="btn-nav text-indigo"><i class="fas fa-file-contract"></i> Piutang</a>
                </div>
            </div>

            <!-- TABEL NERACA -->
            <div class="card card-modern overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-neraca align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 sticky-col">Akun Laporan</th>
                                    <th class="text-center">Awal Periode</th>
                                    @foreach ($bulanList as $bulan)
                                        <th class="text-center">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M Y') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-primary-soft">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3 fw-bold text-uppercase">
                                        I. Aktiva (Aset)
                                    </td>
                                </tr>
                                @foreach ($akunAktiva as $akun)
                                    <tr>
                                        <td class="ps-5 sticky-col">{{ $akun }}</td>
                                        <td class="text-center text-muted">0</td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center font-semibold">
                                                {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr class="bg-slate-50 fw-bold border-top-2">
                                    <td class="ps-4 sticky-col">TOTAL AKTIVA</td>
                                    <td class="text-center">0</td>
                                    @foreach ($bulanList as $bulan)
                                        @php $ta = 0; foreach($akunAktiva as $a) { $ta += $saldo[$a][$bulan] ?? 0; } @endphp
                                        <td class="text-center text-primary font-bold">
                                            {{ number_format($ta, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-danger-soft">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3 fw-bold text-uppercase border-top">
                                        II. Pasiva (Kewajiban & Modal)
                                    </td>
                                </tr>
                                @foreach ($akunPasiva as $akun)
                                    <tr>
                                        <td class="ps-5 sticky-col">{{ $akun }}</td>
                                        <td class="text-center text-muted">0</td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="ps-5 sticky-col">Modal Awal Tetap</td>
                                    <td class="text-center">200.000.000</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center">200.000.000</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 sticky-col">Laba Rugi Tahun Berjalan</td>
                                    <td class="text-center">-</td>
                                    @foreach ($bulanList as $bulan)
                                        @php $lr_kum = $labaRugiKumulatif[$bulan] ?? 0; @endphp
                                        <td class="text-center fw-bold {{ $lr_kum < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $lr_kum < 0 ? '-' : '' }} {{ number_format(abs($lr_kum), 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="bg-slate-50 fw-bold border-top">
                                    <td class="ps-4 sticky-col">TOTAL PASIVA</td>
                                    <td class="text-center">0</td>
                                    @foreach ($bulanList as $bulan)
                                        @php
                                            $tp_total = (200000000 + ($labaRugiKumulatif[$bulan] ?? 0));
                                            foreach($akunPasiva as $p) { $tp_total += $saldo[$p][$bulan] ?? 0; }
                                        @endphp
                                        <td class="text-center font-bold">{{ number_format($tp_total, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>

                                <!-- BALANCE CHECK -->
                                <tr class="bg-white">
                                    <td class="ps-4 text-xs text-secondary sticky-col py-4">AUDIT VERIFIKASI</td>
                                    <td></td>
                                    @foreach ($bulanList as $bulan)
                                        @php
                                            $ta_check = 0; foreach($akunAktiva as $a) { $ta_check += $saldo[$a][$bulan] ?? 0; }
                                            $tp_check = 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                            foreach($akunPasiva as $p) { $tp_check += $saldo[$p][$bulan] ?? 0; }
                                            $diff = abs($ta_check - $tp_check);
                                        @endphp
                                        <td class="text-center">
                                            @if($diff < 500)
                                                <span class="badge bg-success rounded-pill px-3" style="font-size: 0.6rem">BALANCE <i class="fas fa-check-double ms-1"></i></span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3" style="font-size: 0.6rem">OUT OF Rp{{ number_format($diff,0) }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // 🔹 Data dari PHP untuk JavaScript
        const labels = {!! json_encode($bulanList->map(fn($b) => \Carbon\Carbon::parse($b)->translatedFormat('M Y'))) !!};
        
        // Data Aktiva
        const aktivaData = @json($bulanList->map(function($bulan) use ($akunAktiva, $saldo) {
            $sum = 0; foreach($akunAktiva as $a) { $sum += $saldo[$a][$bulan] ?? 0; }
            return $sum;
        }));

        // Data Laba Akumulasi
        const labaData = @json($bulanList->map(fn($b) => $labaRugiKumulatif[$b] ?? 0));

        // 📈 Trend Chart
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Aset (Aktiva)',
                    data: aktivaData,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }, {
                    label: 'Laba Akumulasi',
                    data: labaData,
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, 
            scales: { y: { beginAtZero: false, ticks: { callback: (value) => 'Rp ' + value.toLocaleString() } } } }
        });

        // 🍩 Composition Chart (Latest Month)
        const currentAktiva = @json(collect($akunAktiva)->map(fn($a) => $saldo[$a][$latestBulan] ?? 0));
        new Chart(document.getElementById('compositionChart'), {
            type: 'doughnut',
            data: {
                labels: @json($akunAktiva),
                datasets: [{
                    data: currentAktiva,
                    backgroundColor: ['#6366f1', '#34d399', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#64748b']
                }]
            },
            options: { plugins: { legend: { display: false } }, cutout: '70%' }
        });
    </script>
</x-app-layout>