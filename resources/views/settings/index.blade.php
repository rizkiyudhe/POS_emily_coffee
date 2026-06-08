<x-app-layout>
    <x-slot name="header">Pengaturan Toko</x-slot>
    <div class="max-w-4xl mx-auto py-6">
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
            class="bg-white p-6 rounded shadow">
            @csrf
            <!-- Informasi Toko -->
            <h3 class="font-bold text-lg mb-4">Informasi Toko</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label>Nama Toko</label><input type="text" name="store_name"
                        value="{{ old('store_name', $settings['store_name']) }}" class="w-full border rounded"></div>
                <div><label>Logo</label><input type="file" name="logo" class="w-full"><br>
                    @if ($settings['logo'])
                        <img src="{{ Storage::url($settings['logo']) }}" width="100">
                    @endif
                </div>
                <div><label>Alamat</label>
                    <textarea name="address" rows="2" class="w-full border rounded">{{ old('address', $settings['address']) }}</textarea>
                </div>
                <div><label>Telepon</label><input type="text" name="phone"
                        value="{{ old('phone', $settings['phone']) }}" class="w-full border rounded"></div>
                <div><label>Email</label><input type="email" name="email"
                        value="{{ old('email', $settings['email']) }}" class="w-full border rounded"></div>
                <div><label>Website</label><input type="url" name="website"
                        value="{{ old('website', $settings['website']) }}" class="w-full border rounded"></div>
            </div>

            <!-- Header/Footer Struk -->
            <h3 class="font-bold text-lg mt-6 mb-4">Struk</h3>
            <div><label>Header Struk</label>
                <textarea name="receipt_header" rows="2" class="w-full border rounded">{{ old('receipt_header', $settings['receipt_header']) }}</textarea>
            </div>
            <div class="mt-2"><label>Footer Struk</label>
                <textarea name="receipt_footer" rows="2" class="w-full border rounded">{{ old('receipt_footer', $settings['receipt_footer']) }}</textarea>
            </div>

            <!-- Pajak & Service -->
            <h3 class="font-bold text-lg mt-6 mb-4">Pajak & Service</h3>
            <div><label><input type="checkbox" name="enable_tax" value="1"
                        {{ $settings['enable_tax'] ? 'checked' : '' }}> Aktifkan Pajak</label></div>
            <div><label>Persentase Pajak (%)</label><input type="number" step="0.01" name="tax_percentage"
                    value="{{ old('tax_percentage', $settings['tax_percentage']) }}" class="w-full border rounded">
            </div>
            <div class="mt-2"><label><input type="checkbox" name="enable_service" value="1"
                        {{ $settings['enable_service'] ? 'checked' : '' }}> Aktifkan Service Charge</label></div>
            <div><label>Persentase Service (%)</label><input type="number" step="0.01" name="service_percentage"
                    value="{{ old('service_percentage', $settings['service_percentage']) }}"
                    class="w-full border rounded"></div>

            <!-- Mode Printer -->
            <h3 class="font-bold text-lg mt-6 mb-4">Printer</h3>
            <div><label>Mode Cetak</label>
                <select name="printer_mode" class="w-full border rounded">
                    <option value="both" {{ $settings['printer_mode'] == 'both' ? 'selected' : '' }}>Struk + KOT
                    </option>
                    <option value="receipt_only" {{ $settings['printer_mode'] == 'receipt_only' ? 'selected' : '' }}>
                        Struk Only</option>
                    <option value="kot_only" {{ $settings['printer_mode'] == 'kot_only' ? 'selected' : '' }}>KOT Only
                    </option>
                </select>
            </div>

            <button type="submit" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded">Simpan Pengaturan</button>
        </form>
    </div>
</x-app-layout>
