<x-app-layout>
    <style>
        /* Modern UI Enhancements */
        :root {
            --primary-soft: #f0f5ff;
            --danger-soft: #fff5f5;
            --success-soft: #f0fff4;
            --dark-text: #344767;
        }

        .main-content { background-color: #f8f9fe; min-height: 100vh; }
        
        /* Card Styling */
        .card-neraca {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 25px rgba(0,0,0,0.05);
            background: white;
            overflow: hidden;
        }

        /* Navigation Buttons */
        .btn-nav {
            background-color: white;
            color: var(--dark-text);
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            border-radius: 0.75rem;
            border: 1px solid #e9ecef;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .btn-nav:hover {
            background-color: #ffffff;
            border-color: #5e72e4;
            color: #5e72e4 !important;
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1);
        }
        .btn-nav i { margin-right: 8px; font-size: 0.9rem; }

        /* Table Design */
        .table thead th {
            background-color: #f6f9fc;
            text-transform: uppercase;
            font-size: 0.65rem;
            font-weight: 700;
            color: #8392ab;
            letter-spacing: 1px;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        .table tbody td {
            padding: 1rem;
            font-size: 0.85rem;
            color: var(--dark-text);
            border-bottom: 1px solid #f2f4f6;
        }
        .font-weight-bold { font-weight: 700 !important; }

        /* Section Accents */
        .bg-aktiva-header { background-color: #f0f5ff !important; color: #2dce89; }
        .bg-pasiva-header { background-color: #fff5f5 !important; color: #f5365c; }
        .row-total { background-color: #f8f9fe; }
        
        .sticky-col {
            position: sticky;
            left: 0;
            background: white;
            z-index: 1;
            font-weight: 700;
        }

        .badge-balance {
            padding: 0.5em 0.8em;
            border-radius: 0.5rem;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
        }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- HEADER UTAMA -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-0 text-primary uppercase letter-spacing-1">Neraca Keuangan</h4>
                    <p class="text-sm text-secondary mb-0">Hasanah Farm • Laporan Riil Aset & Kewajiban</p>
                </div>
                <div>
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-primary shadow-sm rounded-pill px-4 btn-sm fw-bold">
                        <i class="fas fa-chart-line me-2"></i> Laporan Laba Rugi
                    </a>
                </div>
            </div>

            <!-- NAVIGASI TOMBOL KELOLA AKUN -->
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('kambing-akun.index') }}" class="btn-nav"><i class="fas fa-sheep"></i> Stok Kambing</a>
                    <a href="{{ route('rincian-hpp.index') }}" class="btn-nav text-primary"><i class="fas fa-file-invoice-dollar"></i> Rincian HPP & Stok</a>
                    <a href="{{ route('pakan.index') }}" class="btn-nav"><i class="fas fa-utensils text-warning"></i> Pakan</a>
                    <a href="{{ route('kandang.index') }}" class="btn-nav"><i class="fas fa-tools text-info"></i> Kandang</a>
                    <a href="{{ route('perlengkapan.index') }}" class="btn-nav"><i class="fas fa-box text-primary"></i> Perlengkapan</a>
                    <a href="{{ route('upah.index') }}" class="btn-nav"><i class="fas fa-user-tie"></i> Upah</a>
                    <a href="{{ route('operasional.index') }}" class="btn-nav"><i class="fas fa-cogs"></i> Operasional</a>
                    <div class="vr mx-1 d-none d-md-block" style="height: 30px; align-self: center; opacity: 0.1;"></div>
                    <a href="{{ route('neraca.penjualan.index') }}" class="btn-nav"><i class="fas fa-shopping-cart text-success"></i> Penjualan</a>
                    <a href="{{ route('piutang.index') }}" class="btn-nav"><i class="fas fa-hand-holding-usd text-warning"></i> Piutang</a>
                    <a href="{{ route('hutang.index') }}" class="btn-nav"><i class="fas fa-credit-card text-danger"></i> Hutang</a>
                </div>
            </div>

            <!-- TABEL NERACA -->
            <div class="card card-neraca shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 py-3" style="min-width: 250px;">KATEGORI & AKUN</th>
                                    <th class="text-center">SALDO AWAL</th>
                                    @foreach ($bulanList as $bulan)
                                        <th class="text-center text-dark font-weight-bold">
                                            {{ \Carbon\Carbon::parse($bulan)->translatedFormat('M Y') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                {{-- SEKSI AKTIVA --}}
                                <tr class="bg-aktiva-header">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3">
                                        <span class="text-xs fw-bold text-uppercase letter-spacing-1">
                                            <i class="fas fa-plus-circle me-2"></i> Aktiva (Aset & Inventaris)
                                        </span>
                                    </td>
                                </tr>
                                @foreach ($akunAktiva as $akun)
                                    <tr>
                                        <td class="ps-5 py-2 font-weight-bold">{{ $akun }}</td>
                                        <td class="text-center text-muted text-xs">0</td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center font-weight-bold">
                                                {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="row-total border-top">
                                    <td class="ps-4 text-sm font-weight-bold py-3 text-dark text-uppercase">TOTAL AKTIVA</td>
                                    <td class="text-center font-weight-bold">0</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm text-primary font-weight-bold">
                                            @php $ta = 0; foreach($akunAktiva as $a) { $ta += $saldo[$a][$bulan] ?? 0; } @endphp
                                            {{ number_format($ta, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                {{-- SEKSI PASIVA --}}
                                <tr class="bg-pasiva-header">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-3 border-top">
                                        <span class="text-xs fw-bold text-uppercase letter-spacing-1">
                                            <i class="fas fa-minus-circle me-2"></i> Pasiva (Kewajiban & Modal)
                                        </span>
                                    </td>
                                </tr>
                                @foreach ($akunPasiva as $akun)
                                    <tr>
                                        <td class="ps-5 py-2 font-weight-bold">{{ $akun }}</td>
                                        <td class="text-center text-muted text-xs">0</td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center">
                                                {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="italic">
                                    <td class="ps-5 py-2 text-secondary">Modal Awal Tetap</td>
                                    <td class="text-center text-muted text-xs">200.000.000</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center font-weight-bold">200.000.000</td>
                                    @endforeach
                                </tr>

                                {{-- LABA RUGI AKUMULASI --}}
                                <tr>
                                    <td class="ps-5 py-3 font-weight-bold">Laba Rugi Tahun Berjalan</td>
                                    <td class="text-center text-muted text-xs">-</td>
                                    @foreach ($bulanList as $bulan)
                                        @php $lr_kum = $labaRugiKumulatif[$bulan] ?? 0; @endphp
                                        <td class="text-center font-weight-bold {{ $lr_kum < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $lr_kum < 0 ? '-' : '' }} {{ number_format(abs($lr_kum), 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="row-total border-top">
                                    <td class="ps-4 text-sm font-weight-bold py-3 text-dark text-uppercase">TOTAL PASIVA</td>
                                    <td class="text-center">-</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm font-weight-bold">
                                            @php
                                                $tp_hut = 0; foreach($akunPasiva as $p) { $tp_hut += $saldo[$p][$bulan] ?? 0; }
                                                $tp_total = $tp_hut + 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                            @endphp
                                            {{ number_format($tp_total, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                {{-- STATUS BALANCE --}}
                                <tr class="bg-white border-top">
                                    <td class="ps-4 text-xxs font-weight-bold py-4 text-secondary text-uppercase tracking-wider">Status Laporan</td>
                                    <td></td>
                                    @foreach ($bulanList as $bulan)
                                        @php
                                            $ta_bal = 0; foreach($akunAktiva as $a) { $ta_bal += $saldo[$a][$bulan] ?? 0; }
                                            $tp_bal = 0; foreach($akunPasiva as $p) { $tp_bal += $saldo[$p][$bulan] ?? 0; }
                                            $tp_bal += 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                            $diff = abs($ta_bal - $tp_bal);
                                            $isBalanced = $diff < 500;
                                        @endphp
                                        <td class="text-center">
                                            @if($isBalanced)
                                                <span class="badge-balance bg-gradient-success text-white">
                                                    <i class="fas fa-check-circle me-1"></i> Balance
                                                </span>
                                            @else
                                                <span class="badge-balance bg-gradient-danger text-white">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> Selisih Rp {{ number_format($diff, 0, ',', '.') }}
                                                </span>
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
</x-app-layout>