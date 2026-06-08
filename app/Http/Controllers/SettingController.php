<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage settings');
    }

    public function index()
    {
        $settings = [
            'store_name' => StoreSetting::get('store_name', 'Coffee Shop POS'),
            'logo' => StoreSetting::get('logo'),
            'address' => StoreSetting::get('address'),
            'phone' => StoreSetting::get('phone'),
            'email' => StoreSetting::get('email'),
            'website' => StoreSetting::get('website'),
            'receipt_header' => StoreSetting::get('receipt_header'),
            'receipt_footer' => StoreSetting::get('receipt_footer'),
            'enable_tax' => StoreSetting::get('enable_tax', false),
            'tax_percentage' => StoreSetting::get('tax_percentage', 11),
            'enable_service' => StoreSetting::get('enable_service', false),
            'service_percentage' => StoreSetting::get('service_percentage', 5),
            'printer_mode' => StoreSetting::get('printer_mode', 'both'),
        ];
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Set default untuk checkbox yang tidak dicentang (tidak dikirim oleh browser)
        $data = $request->all();
        $data['enable_tax'] = $request->has('enable_tax') ? 1 : 0;
        $data['enable_service'] = $request->has('enable_service') ? 1 : 0;

        $validated = validator($data, [
            'store_name' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'receipt_header' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
            'enable_tax' => 'boolean',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'enable_service' => 'boolean',
            'service_percentage' => 'required|numeric|min:0|max:100',
            'printer_mode' => 'required|in:both,receipt_only,kot_only',
        ])->validate();

        // Simpan atau update setting
        foreach ($validated as $key => $value) {
            if ($key === 'logo' && $request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                StoreSetting::set('logo', $path);
                // Hapus logo lama jika ada
                if ($old = StoreSetting::get('logo')) {
                    Storage::disk('public')->delete($old);
                }
            } else {
                StoreSetting::set($key, $value);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
