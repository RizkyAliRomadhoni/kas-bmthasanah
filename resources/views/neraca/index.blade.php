<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-light">
        

        <div class="container-fluid py-4">
            

            {{-- ===========================
                 HEADER + FILTER
            ============================ --}}
            <div class="card shadow border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white d-flex flex-wrap justify-content-between align-items-center py-3 px-4">
                    <h5 class="fw-bold text-white mb-0">📊 NERACA KEUANGAN</h5>
                    <form action="{{ route('neraca.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                        <select name="bulan" class="form-select form-select-sm rounded-3 shadow-sm border-0">
                            <option value="">Semua Bulan</option>
                            @foreach ($bulanList as $num => $nama)
                                <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                        <select name="tahun" class="form-select form-select-sm rounded-3 shadow-sm border-0">
                            <option value="">Semua Tahun</option>
                        @foreach ($tahunList as $t)
                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        <select name="akun" class="form-select form-select-sm rounded-3 shadow-sm border-0">
    <option value="">Semua Akun</option>
    @foreach($akunList as $a)
        <option value="{{ $a }}" {{ request('akun') == $a ? 'selected' : '' }}>
            {{ $a }}
        </option>
    @endforeach
</select>

                        <button type="submit" class="btn btn-light text-primary fw-bold btn-sm shadow-sm">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </form>
                </div>
            </div>

            {{-- ===========================
                 RINGKASAN
            ============================ --}}
            <div class="row g-4 mb-4">
                @php
                    $summary = [
                        ['title' => 'Total Pemasukan', 'value' => $pemasukan, 'color' => 'success', 'icon' => 'fa-arrow-up'],
                        ['title' => 'Total Pengeluaran', 'value' => $pengeluaran, 'color' => 'danger', 'icon' => 'fa-arrow-down'],
                        ['title' => 'Saldo Akhir', 'value' => $saldoAkhir, 'color' => 'info', 'icon' => 'fa-wallet'],
                        ['title' => 'Laba Bersih', 'value' => $labaBersih, 'color' => 'warning', 'icon' => 'fa-coins']
                    ];
                @endphp

                @foreach ($summary as $item)
                    <div class="col-lg-3 col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 hover-shadow transition-all bg-gradient-{{ $item['color'] }} text-white">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-sm mb-1">{{ $item['title'] }}</h6>
                                    <h4 class="fw-bold mb-0">Rp {{ number_format($item['value'], 0, ',', '.') }}</h4>
                                </div>
                                <i class="fas {{ $item['icon'] }} fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

           <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">📊 Analisis Efektivitas Usaha Peternakan</h5>
    </div>
    <div class="card-body">

        <div class="row text-center">

            <div class="col-md-3 mb-3">
                <h6 class="mb-1">Modal Kambing</h6>
                <h4 class="fw-bold">Rp {{ number_format($modalKambing, 0, ',', '.') }}</h4>
            </div>

            <div class="col-md-3 mb-3">
                <h6 class="mb-1">Biaya Pakan</h6>
                <h4 class="fw-bold">Rp {{ number_format($pakan, 0, ',', '.') }}</h4>
            </div>

            <div class="col-md-3 mb-3">
                <h6 class="mb-1">Biaya Operasional</h6>
                <h4 class="fw-bold">Rp {{ number_format($operasional, 0, ',', '.') }}</h4>
            </div>

            <div class="col-md-3 mb-3">
                <h6 class="mb-1">Perawatan</h6>
                <h4 class="fw-bold">Rp {{ number_format($perawatan, 0, ',', '.') }}</h4>
            </div>

        </div>

        <hr>

        <div class="text-center mb-4">
            <h6>Total Biaya Usaha</h6>
            <h3 class="fw-bold">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h3>
        </div>

        <div class="text-center mb-4">
            <h6>Pendapatan Penjualan</h6>
            <h3 class="fw-bold text-primary">Rp {{ number_format($penjualan, 0, ',', '.') }}</h3>
        </div>

        <div class="text-center mb-4">
            <h6>Laba / Rugi</h6>
            <h3 class="fw-bold {{ $labaRugi >= 0 ? 'text-success' : 'text-danger' }}">
                Rp {{ number_format($labaRugi, 0, ',', '.') }}
            </h3>
        </div>

        <div class="text-center">
            <h6>Efektivitas Usaha</h6>
            <h2 class="fw-bold {{ $efektivitas >= 100 ? 'text-success' : 'text-danger' }}">
                {{ number_format($efektivitas, 2) }}%
            </h2>
            <p class="text-muted mt-2">
                Efektivitas ≥ 100% = usaha efisien & menguntungkan<br>
                Efektivitas &lt; 100% = biaya lebih besar dari pendapatan
            </p>
        </div>

    </div>
</div>



            {{-- ===========================
                 NERACA (AKTIVA & PASIVA)
            ============================ --}}
            <div class="row mb-4 g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-gradient-success text-white rounded-top-4">
                            <h6 class="mb-0 fw-bold">Aktiva</h6>
                        </div>
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Total Aktiva: <span class="text-success">Rp {{ number_format($aktiva, 0, ',', '.') }}</span></h5>
                            <canvas id="aktivaChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-gradient-danger text-white rounded-top-4">
                            <h6 class="mb-0 fw-bold">Pasiva</h6>
                        </div>
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Total Pasiva: <span class="text-danger">Rp {{ number_format($pasiva, 0, ',', '.') }}</span></h5>
                            <canvas id="pasivaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===========================
                 GRAFIK TREN
            ============================ --}}
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-gradient-primary text-white rounded-top-4">
                            <h6 class="mb-0 fw-bold">📈 Tren Pemasukan & Pengeluaran</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-gradient-success text-white rounded-top-4">
                            <h6 class="mb-0 fw-bold">💰 Perbandingan Pemasukan vs Pengeluaran</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===========================
                 FOOTER
            ============================ --}}
            <div class="text-center mt-4 text-muted small">
                <em>© {{ date('Y') }} Sistem Pengelolaan Keuangan Peternakan</em>
            </div>
        </div>
    </main>

    {{-- ===========================
         CHART.JS
    ============================ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($labels);
        const pemasukan = @json($grafikPemasukan);
        const pengeluaran = @json($grafikPengeluaran);

        // Line Chart - Tren Kas
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: pemasukan,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaran,
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220,38,38,0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Pie Chart - Perbandingan
        new Chart(document.getElementById('pieChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [{{ $pemasukan }}, {{ $pengeluaran }}],
                    backgroundColor: ['#22c55e', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom' } },
                cutout: '70%'
            }
        });

        // Tambahan Bar Chart Aktiva
        new Chart(document.getElementById('aktivaChart'), {
            type: 'bar',
            data: {
                labels: ['Aktiva Tetap', 'Aktiva Lancar'],
                datasets: [{
                    data: [{{ $aktiva * 0.6 }}, {{ $aktiva * 0.4 }}],
                    backgroundColor: ['#16a34a', '#4ade80']
                }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        // Tambahan Bar Chart Pasiva
        new Chart(document.getElementById('pasivaChart'), {
            type: 'bar',
            data: {
                labels: ['Modal', 'Kewajiban'],
                datasets: [{
                    data: [{{ $pasiva * 0.7 }}, {{ $pasiva * 0.3 }}],
                    backgroundColor: ['#ef4444', '#f87171']
                }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    </script>

    {{-- ===========================
         STYLING CUSTOM
    ============================ --}}
    <style>
        .hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .transition-all { transition: all 0.3s ease; }
    </style>
</x-app-layout>
