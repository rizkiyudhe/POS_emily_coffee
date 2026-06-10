<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('Pengaturan Toko') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
                class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 space-y-8">
                @csrf

                <!-- SECTION 1: Informasi Toko -->
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-slate-800">Informasi Dasar Toko</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Atur detail identitas outlet usaha Emily Coffe Anda.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Nama Toko</label>
                            <input type="text" name="store_name"
                                value="{{ old('store_name', $settings['store_name']) }}"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm"
                                required>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Logo Outlet</label>
                            <div class="flex items-center gap-4 border border-slate-200 rounded-xl p-2 bg-slate-50/50">
                                <input type="file" name="logo"
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                @if ($settings['logo'])
                                    <div
                                        class="h-12 w-12 rounded-xl border border-slate-200 overflow-hidden bg-white flex items-center justify-center shrink-0 shadow-sm">
                                        <img src="{{ Storage::url($settings['logo']) }}" alt="Logo"
                                            class="object-contain h-full w-full">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Alamat Lengkap</label>
                            <textarea name="address" rows="2"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition p-3 text-sm"
                                required>{{ old('address', $settings['address']) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">No. Telepon /
                                WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $settings['phone']) }}"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm"
                                required>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Email Bisnis</label>
                            <input type="email" name="email" value="{{ old('email', $settings['email']) }}"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Alamat Website URL</label>
                            <input type="url" name="website" value="{{ old('website', $settings['website']) }}"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm"
                                placeholder="https://example.com">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Header/Footer Struk -->
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-slate-800">Template Kertas Struk</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Catatan teks tambahan yang akan muncul di bagian atas
                            dan bawah struk belanja kasir.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Header Struk</label>
                            <textarea name="receipt_header" rows="2" placeholder="Pesan pembuka..."
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition p-3 text-sm font-mono">{{ old('receipt_header', $settings['receipt_header']) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Footer Struk</label>
                            <textarea name="receipt_footer" rows="2" placeholder="Terima kasih, silakan datang kembali..."
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition p-3 text-sm font-mono">{{ old('receipt_footer', $settings['receipt_footer']) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Pajak & Service Charge -->
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-slate-800">Pajak & Biaya Layanan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Atur kalkulasi Pajak Pertambahan Nilai (PPN) serta
                            Service Charge kasir toko.</p>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 border border-slate-100 p-5 rounded-2xl">
                        <!-- Konfigurasi Pajak -->
                        <div class="space-y-3">
                            <label class="inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="enable_tax" value="1"
                                    class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-200 h-4 w-4 transition"
                                    {{ $settings['enable_tax'] ? 'checked' : '' }}>
                                <span class="ml-2 text-sm font-bold text-slate-700">Aktifkan Pajak (PPN)</span>
                            </label>
                            <div>
                                <label class="block text-slate-500 text-xs font-semibold mb-1">Persentase Pajak
                                    (%)</label>
                                <input type="number" step="0.01" name="tax_percentage"
                                    value="{{ old('tax_percentage', $settings['tax_percentage']) }}"
                                    class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2 text-sm">
                            </div>
                        </div>

                        <!-- Konfigurasi Service Charge -->
                        <div class="space-y-3">
                            <label class="inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="enable_service" value="1"
                                    class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-200 h-4 w-4 transition"
                                    {{ $settings['enable_service'] ? 'checked' : '' }}>
                                <span class="ml-2 text-sm font-bold text-slate-700">Aktifkan Service Charge</span>
                            </label>
                            <div>
                                <label class="block text-slate-500 text-xs font-semibold mb-1">Persentase Service
                                    (%)</label>
                                <input type="number" step="0.01" name="service_percentage"
                                    value="{{ old('service_percentage', $settings['service_percentage']) }}"
                                    class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Mode Printer KOT -->
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-slate-800">Konfigurasi Output Printer</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Tentukan jenis keluaran dokumen cetak otomatis saat
                            tombol bayar ditekan.</p>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-1.5">Mode Jalur Cetak</label>
                        <select name="printer_mode"
                            class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm">
                            <option value="both" {{ $settings['printer_mode'] == 'both' ? 'selected' : '' }}>🖨️
                                Struk Kasir + Antrean Dapur (KOT)</option>
                            <option value="receipt_only"
                                {{ $settings['printer_mode'] == 'receipt_only' ? 'selected' : '' }}>🧾 Hanya Struk
                                Konsumen</option>
                            <option value="kot_only" {{ $settings['printer_mode'] == 'kot_only' ? 'selected' : '' }}>
                                🍳 Hanya Lembar Antrean Dapur</option>
                        </select>
                    </div>
                </div>

                <!-- Action Simpan Button -->
                <div class="flex justify-end pt-5 border-t border-slate-100">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition duration-200 hover:-translate-y-0.5 w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Simpan Semua Pengaturan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
