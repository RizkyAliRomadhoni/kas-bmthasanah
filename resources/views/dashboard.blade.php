{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <main class="min-h-screen bg-gray-50">

        <x-slot name="header"></x-slot>

        {{-- (Optional) External libs - remove if already loaded in layout --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
        <script src="https://cdn.tailwindcss.com"></script>

        {{-- Small additional CSS for visual polish (kept minimal) --}}
        <style>
            :root {
                --primary: #2563EB;
                --secondary: #1E3A8A;
                --accent: #D4AF37;
                --muted: #6B7280;
                --text: #0F172A;
            }

            /* glass effect kept subtle and fast on mobile */
            .glass {
                background: rgba(255,255,255,0.72);
                backdrop-filter: blur(6px);
                border: 1px solid rgba(30,58,138,0.06);
                box-shadow: 0 6px 20px rgba(2,6,23,0.06);
            }

            /* hero background fallback */
            .hero-bg {
                background-image:
                    linear-gradient(135deg, rgba(37,99,235,0.12), rgba(30,58,138,0.06)),
                    url('https://images.unsplash.com/photo-1503541497200-3a6b1a3b6d7b?auto=format&fit=crop&w=1600&q=60');
                background-size: cover;
                background-position: center;
            }

            /* small transitions */
            .smooth {
                transition: all .25s ease;
            }

            /* keep table compact on small screens */
            @media (max-width: 420px) {
                .text-xs-responsive { font-size: .72rem; }
            }
        </style>

        {{-- NAVBAR --}}
        <nav class="bg-white/80 backdrop-blur sticky top-0 z-40 border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" class="text-lg font-bold text-[var(--primary)] tracking-tight">
                            SmartFarm
                        </a>
                        <span class="hidden sm:inline text-sm text-[var(--muted)]">— Dashboard</span>
                    </div>

                    {{-- Desktop links --}}
                    <div class="hidden md:flex items-center gap-4">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-[var(--primary)]">Dashboard</a>
                        <a href="{{ route('farm.index') }}" class="text-sm font-medium text-gray-700 hover:text-[var(--primary)]">Farm</a>
                        <a href="{{ route('kas.index') }}" class="text-sm font-medium text-gray-700 hover:text-[var(--primary)]">Keuangan</a>
                        <a href="{{ route('kas.create') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[var(--primary)] text-white text-sm font-semibold shadow-sm hover:opacity-95">
                            <i class="fa-solid fa-plus mr-2"></i> Transaksi
                        </a>
                    </div>

                    {{-- Mobile: menu button --}}
                    <div class="md:hidden flex items-center">
                        <button id="navToggle" aria-label="Open menu" class="p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile panel (hidden by default) --}}
            <div id="mobilePanel" class="md:hidden transform -translate-y-full origin-top transition-transform duration-300 bg-white border-t">
                <div class="px-4 py-3 space-y-2">
                    <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-medium text-gray-700">Dashboard</a>
                    <a href="{{ route('farm.index') }}" class="block py-2 text-sm font-medium text-gray-700">Farm</a>
                    <a href="{{ route('kas.index') }}" class="block py-2 text-sm font-medium text-gray-700">Keuangan</a>
                    <a href="{{ route('kas.create') }}" class="block py-2 text-sm font-semibold text-white bg-[var(--primary)] rounded-md text-center">+ Tambah Transaksi</a>
                </div>
            </div>
        </nav>

        <script>
            // Mobile panel toggle
            document.addEventListener('DOMContentLoaded', function () {
                const btn = document.getElementById('navToggle');
                const panel = document.getElementById('mobilePanel');
                let open = false;
                btn?.addEventListener('click', function () {
                    open = !open;
                    if (open) panel.style.transform = 'translateY(0)';
                    else panel.style.transform = 'translateY(-100%)';
                });
            });
        </script>

        {{-- PAGE WRAPPER --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- HERO --}}
            <section class="hero-bg rounded-2xl overflow-hidden relative">
                <div class="relative z-10 p-4 sm:p-6 md:p-8 lg:p-10">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="max-w-2xl">
                            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[var(--text)] leading-tight">
                                Dashboard Ringkas — Smart Farm & Finance
                            </h1>
                            <p class="mt-2 text-xs sm:text-sm md:text-base text-[color:var(--muted)] max-w-xl">
                                Monitoring operasional peternakan & keuangan — pemasukan, pengeluaran, kesehatan hewan, dan konsumsi pakan dalam satu layar.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2 items-center">
                                <div class="glass px-3 py-1.5 rounded-lg text-xs sm:text-sm inline-flex items-center gap-2">
                                    <span class="text-[var(--muted)]">Progress Kinerja</span>
                                    <span class="font-semibold text-[var(--secondary)]">— Placeholder</span>
                                </div>

                                <div class="glass px-3 py-1.5 rounded-lg text-xs sm:text-sm inline-flex items-center gap-2">
                                    <i class="fa-solid fa-bolt text-[var(--accent)]"></i>
                                    <div>
                                        <div class="text-[var(--muted)] text-xs">Alert</div>
                                        <div class="text-sm font-medium text-red-600">Tidak ada notifikasi</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 items-center justify-start lg:justify-end mt-2 lg:mt-0">
                            <a href="{{ route('farm.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-[var(--primary)] to-[var(--secondary)] shadow-sm smooth">
                                <i class="fa-solid fa-warehouse mr-2"></i> Kelola Farm
                            </a>

                            <a href="{{ route('kas.create') }}" class="px-3 py-2 rounded-lg text-sm font-semibold bg-white text-[var(--secondary)] border smooth">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Transaksi
                            </a>

                            <a href="{{ route('kas.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold border smooth">
                                <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Laporan
                            </a>
                        </div>
                    </div>
                </div>

                {{-- decorative element bottom-right for large screens --}}
                <div class="hidden lg:block absolute right-6 bottom-6 opacity-30 pointer-events-none">
                    <svg width="160" height="80" viewBox="0 0 160 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0" y="0" width="160" height="80" rx="12" fill="url(#g)"/>
                        <defs>
                            <linearGradient id="g" x1="0" x2="160" y1="0" y2="80">
                                <stop offset="0" stop-color="#2563EB" stop-opacity="0.06"/>
                                <stop offset="0.6" stop-color="#1E3A8A" stop-opacity="0.04"/>
                                <stop offset="1" stop-color="#D4AF37" stop-opacity="0.06"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </section>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="glass p-3 sm:p-4 rounded-xl smooth">
                    <div class="text-xs text-[var(--muted)]">Total Pemasukan</div>
                    <div class="mt-1 text-lg sm:text-xl font-bold text-[var(--primary)]">Rp {{ number_format($totalKasMasuk ?? ($totalIn ?? 0),0,',','.') }}</div>
                    <div class="mt-2 text-xs text-[var(--muted)]">Performa keuangan saat ini</div>
                </div>

                <div class="glass p-3 sm:p-4 rounded-xl smooth">
                    <div class="text-xs text-[var(--muted)]">Total Pengeluaran</div>
                    <div class="mt-1 text-lg sm:text-xl font-bold text-red-600">Rp {{ number_format($totalKasKeluar ?? ($totalOut ?? 0),0,',','.') }}</div>
                    <div class="mt-2 text-xs text-[var(--muted)]">Ringkasan pembayaran & biaya</div>
                </div>

                <div class="glass p-3 sm:p-4 rounded-xl smooth">
                    <div class="text-xs text-[var(--muted)]">Saldo Bersih</div>
                    <div class="mt-1 text-lg sm:text-xl font-bold" style="color: {{ ($saldoAkhir ?? $saldoGlobal ?? 0) >= 0 ? '#059669' : '#dc2626' }};">
                        Rp {{ number_format($saldoAkhir ?? $saldoGlobal ?? (($totalKasMasuk ?? 0) - ($totalKasKeluar ?? 0)),0,',','.') }}
                    </div>
                    <div class="mt-2 text-xs text-[var(--muted)]">Ringkasan keuangan keseluruhan</div>
                </div>

                <div class="glass p-3 sm:p-4 rounded-xl smooth">
                    <div class="text-xs text-[var(--muted)]">Total Hewan</div>
                    <div class="mt-1 text-lg sm:text-xl font-bold text-[var(--secondary)]">{{ $totalHewan ?? $farmCount ?? 0 }}</div>
                    <div class="mt-2 text-xs text-[var(--muted)]">Jumlah hewan pada seluruh farm</div>
                </div>
            </div>

            {{-- MIXED CHART + STATS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 glass rounded-xl p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-md sm:text-lg font-semibold">Grafik Ringkasan Keuangan</h3>
                        <div class="text-xs text-[var(--muted)]">Unit: Rp</div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 items-start">
                        <div class="w-full h-[220px] md:h-[260px] flex items-center justify-center">
                            <canvas id="miniSummaryChart" class="w-full h-full"></canvas>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="glass p-3 rounded-lg text-sm">
                                <div class="text-xs text-[var(--muted)]">Rata-rata Berat (kg)</div>
                                <div class="font-bold">{{ number_format($rataRataBerat ?? $avgBerat ?? 0,2,',','.') }} kg</div>
                            </div>

                            <div class="glass p-3 rounded-lg text-sm">
                                <div class="text-xs text-[var(--muted)]">Total Pakan (g)</div>
                                <div class="font-bold">{{ number_format($totalPakan ?? 0,0,',','.') }} g</div>
                            </div>

                            <div class="glass p-3 rounded-lg text-sm">
                                <div class="text-xs text-[var(--muted)]">Total Berat Semua (kg)</div>
                                <div class="font-bold">{{ number_format($totalBeratSemua ?? 0,2,',','.') }} kg</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent transactions --}}
                <div class="glass rounded-xl p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-md sm:text-lg font-semibold">Transaksi Terbaru</h3>
                        <a href="{{ route('kas.index') }}" class="text-xs text-[var(--secondary)] hover:underline">Lihat semua</a>
                    </div>

                    <ul class="mt-3 space-y-3 text-sm">
                        @php $cnt = 0; @endphp
                        @foreach(($transaksiTerbaru ?? $kas ?? collect()) as $t)
                            @if($cnt++ >= 6) @break @endif
                            <li class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-[var(--primary)]">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-sm">{{ $t->keterangan ?? '—' }}</div>
                                        <div class="text-xs text-[var(--muted)]">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</div>
                                    </div>
                                </div>

                                <div class="text-sm font-semibold {{ strtolower($t->jenis_transaksi ?? '') === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($t->jumlah ?? 0,0,',','.') }}
                                </div>
                            </li>
                        @endforeach

                        @if(($transaksiTerbaru ?? $kas ?? collect())->isEmpty())
                            <li class="text-center text-[var(--muted)]">Belum ada transaksi</li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- ANIMALS TABLE + QUICK ACTIONS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="glass rounded-xl p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-md sm:text-lg font-semibold">Preview Animals</h3>
                        <a href="{{ route('farm.index') }}" class="text-sm text-[var(--primary)] hover:underline">Kelola semua</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-xs text-[var(--muted)] uppercase">
                                <tr>
                                    <th class="py-2 pr-4 text-left">Nama</th>
                                    <th class="py-2 pr-4 text-left">Jenis</th>
                                    <th class="py-2 pr-4 text-left">Berat</th>
                                    <th class="py-2 pr-4 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($animals ?? $recentAnimals ?? collect()) as $i => $a)
                                    @if($i >= 6) @break @endif
                                    <tr class="border-t">
                                        <td class="py-3">{{ $a->nama }}</td>
                                        <td class="py-3">{{ $a->jenis }}</td>
                                        <td class="py-3">{{ number_format($a->berat_terakhir ?? 0,2,',','.') }} kg</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 text-xs rounded {{ $a->status == 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                                {{ $a->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach

                                @if((($animals ?? $recentAnimals ?? collect())->isEmpty()))
                                    <tr><td colspan="4" class="py-6 text-center text-[var(--muted)]">Belum ada data hewan</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="glass rounded-xl p-3 sm:p-4 flex flex-col gap-3">
                    <h3 class="text-md sm:text-lg font-semibold">Quick Actions & Insights</h3>

                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ route('farm.create') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/90 border text-sm font-semibold">
                            <i class="fa-solid fa-plus text-[var(--secondary)]"></i> Tambah Animal Baru
                        </a>

                        <a href="{{ route('kas.create') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/90 border text-sm font-semibold">
                            <i class="fa-solid fa-file-invoice-dollar text-[var(--secondary)]"></i> Tambah Transaksi
                        </a>

                        <a href="{{ route('kas.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/90 border text-sm font-semibold">
                            <i class="fa-solid fa-chart-line text-[var(--secondary)]"></i> Laporan Keuangan
                        </a>
                    </div>

                    <div class="mt-3 text-xs text-[var(--muted)]">
                        Dashboard ini menampilkan ringkasan global. Untuk analisa mendalam, buka modul Farm atau Laporan.
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <footer class="text-center text-xs text-[var(--muted)] py-6">
                © {{ date('Y') }} Sistem Keuangan Peternakan — Smart Farm Dashboard
            </footer>
        </div>

        {{-- CHART INITIALIZATION --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const el = document.getElementById('miniSummaryChart');
                if (!el) return;

                new Chart(el, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pemasukan', 'Pengeluaran'],
                        datasets: [{
                            data: [Number(@json($totalKasMasuk ?? 0)), Number(@json($totalKasKeluar ?? 0))],
                            backgroundColor: ['#2563EB', '#EF4444'],
                            hoverOffset: 6,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12 } },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const val = Number(ctx.raw).toLocaleString('id-ID');
                                        return ctx.label + ': Rp ' + val;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>

    </main>
</x-app-layout>
