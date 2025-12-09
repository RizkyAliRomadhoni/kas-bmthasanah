{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <main class="min-h-screen bg-gray-50">
        <x-slot name="header"></x-slot>

        {{-- Tailwind + FontAwesome CDN (if your layout already loads them, you can remove these) --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            :root{
                --primary: #2563EB;
                --secondary: #1E3A8A;
                --accent: #D4AF37;
                --surface: #FFFFFF;
                --muted: #6B7280;
                --text: #0F172A;
            }

            /* Glass card */
            .glass {
                background: rgba(255,255,255,0.7);
                box-shadow: 0 10px 30px rgba(2,6,23,0.08);
                border: 1px solid rgba(30,58,138,0.06);
                backdrop-filter: blur(8px);
            }

            /* Hero background decoration */
            .hero-bg {
                background-image:
                    linear-gradient(135deg, rgba(37,99,235,0.12), rgba(30,58,138,0.08)),
                    url('https://images.unsplash.com/photo-1503541497200-3a6b1a3b6d7b?auto=format&fit=crop&w=2000&q=60');
                background-size: cover;
                background-position: center;
            }

            /* CTA glow */
            .cta-glow:hover {
                box-shadow: 0 6px 18px rgba(212,175,55,0.18), 0 2px 6px rgba(37,99,235,0.08);
                transform: translateY(-2px) scale(1.01);
            }

            /* micro hover scale for cards */
            .card-hover:hover { transform: translateY(-6px) scale(1.01); }
            .card-hover { transition: transform .28s cubic-bezier(.2,.9,.2,1), box-shadow .28s; }

            /* small helpers */
            .muted { color: var(--muted); }
            .label { font-size: .82rem; color: var(--muted); }
        </style>

        <div class="container mx-auto px-4 py-8">

            {{-- HERO --}}
            <section class="hero-bg rounded-2xl overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-[rgba(37,99,235,0.15)] to-[rgba(30,58,138,0.06)] mix-blend-multiply pointer-events-none"></div>

                <div class="relative z-10 p-6 md:p-8 lg:p-10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="max-w-2xl">
                            <h1 class="text-2xl md:text-3xl font-extrabold text-[var(--text)] leading-tight">
                                Dashboard Ringkas — Smart Farm & Finance
                            </h1>
                            <p class="mt-2 text-sm md:text-base muted max-w-xl">
                                Tampilan ringkasan operasional peternakan & keuangan terintegrasi. 
                                Gunakan dashboard ini untuk memantau pemasukan, pengeluaran, kesehatan hewan, dan konsumsi pakan — semua dalam satu layar.
                            </p>

                            {{-- small metrics row --}}
                            <div class="mt-4 flex flex-wrap gap-3 items-center">
                                <div class="inline-flex items-center gap-3 bg-white/60 glass px-3 py-2 rounded-lg shadow-sm">
                                    <div class="text-sm label">Progress Kinerja Tahun Ini</div>
                                    <div class="text-sm font-semibold text-[var(--secondary)]">— Placeholder</div>
                                </div>

                                <div class="inline-flex items-center gap-3 bg-white/60 glass px-3 py-2 rounded-lg shadow-sm">
                                    <i class="fa-solid fa-bolt text-[var(--accent)]"></i>
                                    <div class="text-sm">
                                        <div class="label">Alert</div>
                                        <div class="text-sm font-medium text-red-600">Tidak ada notifikasi</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 items-center">
                            <a href="{{ route('farm.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background: linear-gradient(90deg,var(--primary),var(--secondary)); box-shadow: 0 8px 24px rgba(37,99,235,0.12);">
                                <i class="fa-solid fa-warehouse"></i> Kelola Farm
                            </a>

                            <a href="{{ route('kas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-[var(--secondary)] bg-white/90 border border-transparent cta-glow">
                                <i class="fa-solid fa-plus"></i> Tambah Transaksi
                            </a>

                            <a href="{{ route('kas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold" style="border:1px solid rgba(212,175,55,0.12); background:transparent; color:var(--text);">
                                <i class="fa-solid fa-file-invoice-dollar"></i> Laporan Keuangan
                            </a>
                        </div>
                    </div>
                </div>

                {{-- subtle decorative SVG lines (gold) --}}
                <div class="absolute right-6 bottom-6 opacity-30">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                {{-- Finance: Pemasukan --}}
                <div class="glass rounded-xl p-4 card-hover transform transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="label">Total Pemasukan</div>
                            <div class="text-xl font-bold text-[var(--primary)] mt-1">Rp {{ number_format($totalKasMasuk ?? ($totalIn ?? 0),0,',','.') }}</div>
                        </div>
                        <div class="text-2xl text-[var(--primary)] opacity-90">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-sm muted">Performa keuangan saat ini</div>
                </div>

                {{-- Finance: Pengeluaran --}}
                <div class="glass rounded-xl p-4 card-hover">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="label">Total Pengeluaran</div>
                            <div class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($totalKasKeluar ?? ($totalOut ?? 0),0,',','.') }}</div>
                        </div>
                        <div class="text-2xl text-red-600 opacity-90">
                            <i class="fa-solid fa-arrow-down-left"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-sm muted">Ringkasan pembayaran & biaya</div>
                </div>

                {{-- Finance: Saldo --}}
                <div class="glass rounded-xl p-4 card-hover">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="label">Saldo Bersih</div>
                            <div class="text-xl font-bold mt-1" style="color: {{ ($saldoAkhir ?? $saldoGlobal ?? 0) >= 0 ? '#059669' : '#dc2626' }};">
                                Rp {{ number_format($saldoAkhir ?? $saldoGlobal ?? (($totalKasMasuk ?? 0) - ($totalKasKeluar ?? 0)),0,',','.') }}
                            </div>
                        </div>
                        <div class="text-2xl" style="color: {{ ($saldoAkhir ?? $saldoGlobal ?? 0) >= 0 ? '#059669' : '#dc2626' }};">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-sm muted">Ringkasan keuangan keseluruhan</div>
                </div>

                {{-- Farm: Total Hewan --}}
                <div class="glass rounded-xl p-4 card-hover">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="label">Total Hewan</div>
                            <div class="text-xl font-bold text-[var(--secondary)] mt-1">{{ $totalHewan ?? $farmCount ?? 0 }}</div>
                        </div>
                        <div class="text-2xl text-[var(--accent)]">
                            <i class="fa-solid fa-tractor"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-sm muted">Jumlah hewan pada seluruh farm</div>
                </div>
            </div>

            {{-- SECOND ROW: MIXED CHARTS & FARM SUMMARY --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
                {{-- Chart area (finance doughnut) --}}
                <div class="lg:col-span-2 glass rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Grafik Ringkasan Keuangan</h3>
                        <div class="label muted">Unit: Rp</div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-center">
                            <canvas id="miniSummaryChart" style="max-height:250px"></canvas>
                        </div>

                        {{-- Farm small stats --}}
                        <div class="flex flex-col gap-3">
                            <div class="p-3 rounded-lg bg-white/60 glass">
                                <div class="label">Rata-rata Berat (kg)</div>
                                <div class="text-xl font-bold mt-1">{{ number_format($rataRataBerat ?? $avgBerat ?? 0,2,',','.') }} kg</div>
                            </div>

                            <div class="p-3 rounded-lg bg-white/60 glass">
                                <div class="label">Total Pakan (g)</div>
                                <div class="text-xl font-bold mt-1">{{ number_format($totalPakan ?? 0,0,',','.') }} g</div>
                            </div>

                            <div class="p-3 rounded-lg bg-white/60 glass">
                                <div class="label">Total Berat Semua (kg)</div>
                                <div class="text-xl font-bold mt-1">{{ number_format($totalBeratSemua ?? 0,2,',','.') }} kg</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent activity / transactions --}}
                <div class="glass rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Transaksi Terbaru</h3>
                        <a href="{{ route('kas.index') }}" class="text-sm text-[var(--secondary)] hover:underline">Lihat semua</a>
                    </div>

                    <ul class="mt-4 space-y-3">
                        @php $cnt=0; @endphp
                        @foreach(($transaksiTerbaru ?? $kas ?? collect()) as $t)
                            @if($cnt++ >= 6) @break @endif
                            <li class="flex items-center justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-[var(--primary)]">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $t->keterangan ?? '—' }}</div>
                                        <div class="text-xs muted">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold" style="color: {{ strtolower($t->jenis_transaksi ?? '') === 'masuk' ? '#059669' : '#dc2626' }}">
                                        Rp {{ number_format($t->jumlah ?? 0,0,',','.') }}
                                    </div>
                                </div>
                            </li>
                        @endforeach

                        @if(($transaksiTerbaru ?? $kas ?? collect())->isEmpty())
                            <li class="text-center muted">Belum ada transaksi</li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- TABLES: Animals preview --}}
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="glass rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold">Preview Animals</h3>
                        <a href="{{ route('farm.index') }}" class="text-sm text-[var(--primary)] hover:underline">Kelola semua</a>
                    </div>

                    <div class="overflow-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="text-xs uppercase text-muted">
                                <tr>
                                    <th class="py-2 pr-4">Nama</th>
                                    <th class="py-2 pr-4">Jenis</th>
                                    <th class="py-2 pr-4">Berat</th>
                                    <th class="py-2 pr-4">Status</th>
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
                                            <span class="px-2 py-1 rounded text-xs" style="background: {{ $a->status == 'Aktif' ? 'rgba(16,185,129,0.08)' : 'rgba(239,68,68,0.06)'}}; color: {{ $a->status == 'Aktif' ? '#059669' : '#dc2626' }};">
                                                {{ $a->status ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach

                                @if((($animals ?? $recentAnimals ?? collect())->isEmpty()))
                                    <tr><td colspan="4" class="py-6 text-center muted">Belum ada data hewan</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Small activity / quick actions --}}
                <div class="glass rounded-xl p-4 flex flex-col gap-3">
                    <h3 class="text-lg font-semibold">Quick Actions & Insights</h3>

                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('farm.create') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/80 border border-transparent cta-glow">
                            <i class="fa-solid fa-plus text-[var(--secondary)]"></i> Tambah Animal Baru
                        </a>

                        <a href="{{ route('kas.create') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/80 border border-transparent cta-glow">
                            <i class="fa-solid fa-file-invoice-dollar text-[var(--secondary)]"></i> Tambah Transaksi
                        </a>

                        <a href="{{ route('kas.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/80 border border-transparent cta-glow">
                            <i class="fa-solid fa-chart-line text-[var(--secondary)]"></i> Laporan Keuangan
                        </a>
                    </div>

                    <div class="mt-3 text-sm muted">
                        <div class="label">Catatan:</div>
                        <div>
                            Dashboard ini menampilkan ringkasan global. Untuk analisa mendalam, buka modul Farm atau Laporan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="text-center muted text-sm mt-8">
                © {{ date('Y') }} Sistem Keuangan Peternakan — Smart Farm Dashboard
            </div>
        </div>

        {{-- SCRIPTS: Charts & small interactions --}}
        <script>
            (function(){
                // Finance doughnut
                const totalIn = Number(@json($totalKasMasuk ?? $totalIn ?? 0));
                const totalOut = Number(@json($totalKasKeluar ?? $totalOut ?? 0));
                const ctx = document.getElementById('miniSummaryChart')?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Pemasukan','Pengeluaran'],
                            datasets:[{
                                data:[totalIn, totalOut],
                                backgroundColor: ['#2563EB', '#ef4444'],
                                hoverOffset: 6,
                                borderWidth:0
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth:12 } },
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
                }

                // Optional: small fade in animation for cards
                document.querySelectorAll('.glass').forEach((el, idx) => {
                    el.style.opacity = 0;
                    el.style.transform = 'translateY(8px)';
                    setTimeout(() => {
                        el.style.transition = 'opacity .45s ease, transform .45s cubic-bezier(.2,.9,.2,1)';
                        el.style.opacity = 1;
                        el.style.transform = 'translateY(0)';
                    }, 80 * idx);
                });

            })();
        </script>
    </main>
</x-app-layout>
