<x-app-layout>
    <main class="main-content">
        <div class="container-fluid py-4">
            
            <!-- SEMUA TABEL DIBUNGKUS FORM SUPAYA BISA DIINPUT -->
            <form action="{{ route('neraca.laba-rugi.store') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0 border-radius-xl">
                    <div class="card-body p-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Laba Rugi</h5>
                                <p class="text-xs text-secondary mb-0">Isi kolom berwarna kuning untuk input manual</p>
                            </div>
                            <div class="d-flex gap-2">
                                <!-- TOMBOL SIMPAN -->
                                <button type="submit" class="btn btn-success btn-sm px-4 mb-0">
                                    <i class="fas fa-save me-1"></i> SIMPAN SEMUA INPUT
                                </button>
                                <a href="{{ route('neraca.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Kembali</a>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success text-white text-xs py-2 mb-4">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-gray-100 text-center text-xs">
                                    <tr>
                                        <th class="text-start ps-4" style="min-width: 250px;">KETERANGAN AKUN</th>
                                        @foreach($bulanList as $bulan)
                                            <th>{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M Y') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- PENDAPATAN (OTOMATIS) -->
                                    <tr class="bg-light fw-bold text-xs"><td colspan="{{ count($bulanList)+1 }}">PENDAPATAN</td></tr>
                                    <tr>
                                        <td class="ps-5 text-xs">Laba Jual Kambing & Pakan</td>
                                        @foreach($bulanList as $bulan)
                                            <td class="text-end text-xs pe-4">{{ number_format($labaRugiData[$bulan]['laba_jual_kambing'] + $labaRugiData[$bulan]['laba_jual_pakan'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                    
                                    <!-- BIAYA (DI SINI TEMPAT INPUTNYA) -->
                                    <tr class="bg-light fw-bold text-xs text-danger"><td colspan="{{ count($bulanList)+1 }}">BIAYA & PENGELUARAN</td></tr>

                                    <!-- BARIS INPUT MANUAL: BEBAN UPAH -->
                                    <tr style="background-color: #fffdec;">
                                        <td class="ps-5 text-xs font-weight-bold">
                                            <i class="fas fa-keyboard text-warning me-2"></i> BEBAN UPAH (Input)
                                        </td>
                                        @foreach($bulanList as $bulan)
                                            <td class="p-0">
                                                <input type="number" 
                                                       name="manual[{{ $bulan }}][beban_upah]" 
                                                       class="form-control-input" 
                                                       value="{{ (int)$labaRugiData[$bulan]['beban_upah'] }}" 
                                                       placeholder="0">
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- BARIS INPUT MANUAL: BIAYA LAIN-LAIN -->
                                    <tr style="background-color: #fffdec;">
                                        <td class="ps-5 text-xs font-weight-bold">
                                            <i class="fas fa-keyboard text-warning me-2"></i> BIAYA LAIN (Input)
                                        </td>
                                        @foreach($bulanList as $bulan)
                                            <td class="p-0">
                                                <input type="number" 
                                                       name="manual[{{ $bulan }}][biaya_lain]" 
                                                       class="form-control-input" 
                                                       value="{{ (int)$labaRugiData[$bulan]['biaya_lain'] }}" 
                                                       placeholder="0">
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- BIAYA OTOMATIS -->
                                    <tr>
                                        <td class="ps-5 text-xs">Beban Kerugian & Mati (Otomatis)</td>
                                        @foreach($bulanList as $bulan)
                                            <td class="text-end text-xs pe-4 text-danger">{{ number_format($labaRugiData[$bulan]['rugi_jual_kambing'] + $labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>

                                    <!-- HASIL AKHIR -->
                                    <tr class="bg-dark text-white fw-bold">
                                        <td class="ps-4 py-3">LABA RUGI BERSIH</td>
                                        @foreach($bulanList as $bulan)
                                            <td class="text-end pe-4 py-3">Rp {{ number_format($labaRugiData[$bulan]['net_laba_rugi'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <style>
        /* Desain kotak input di dalam tabel agar pas */
        .form-control-input {
            width: 100%;
            height: 45px;
            border: 1px solid transparent;
            background: transparent;
            text-align: right;
            padding-right: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            color: #344767;
            transition: 0.3s;
        }
        .form-control-input:focus {
            outline: none;
            background: #fff;
            border: 1px solid #fbcf33;
            box-shadow: inset 0 0 5px rgba(251, 207, 51, 0.5);
        }
        /* Hilangkan panah naik-turun pada input number */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
            -webkit-appearance: none; margin: 0;
        }
        .ps-5 { padding-left: 3rem !important; }
    </style>
</x-app-layout>