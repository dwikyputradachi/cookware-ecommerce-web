<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaymentSettingController extends Controller
{
    private function uploadToCloudinary($file): string
    {
        $cloudinaryUrl = config('services.cloudinary.url');

        if (!$cloudinaryUrl) {
            throw new \Exception('CLOUDINARY_URL belum terbaca di server.');
        }

        $parsed = parse_url($cloudinaryUrl);

        if (!isset($parsed['host'], $parsed['user'], $parsed['pass'])) {
            throw new \Exception('Format CLOUDINARY_URL tidak valid.');
        }

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $parsed['host'],
                'api_key'    => $parsed['user'],
                'api_secret' => $parsed['pass'],
            ],
        ]);

        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'payment',
            'resource_type' => 'image',
        ]);

        return $result['secure_url'];
    }

    public function index()
    {
        $payments = PaymentSetting::orderBy('id')->get();

        return view('admin.payments.index', compact('payments'));
    }

    public function edit(PaymentSetting $payment)
    {
        return view('admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, PaymentSetting $payment)
    {
        $validated = $request->validate([
            'label'          => 'required|string|max:100',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:100',
            'qr_image'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'qr_image.mimes' => 'Format QR/gambar pembayaran harus JPG, JPEG, PNG, atau WEBP.',
            'qr_image.max'   => 'Ukuran QR/gambar pembayaran maksimal 5MB.',
        ]);

        $data = [
            'label'          => $validated['label'],
            'account_number' => $validated['account_number'] ?? null,
            'account_name'   => $validated['account_name'] ?? null,
            'is_active'      => $request->boolean('is_active'),
        ];

        if ($request->hasFile('qr_image')) {
            try {
                $data['qr_image'] = $this->uploadToCloudinary($request->file('qr_image'));
            } catch (\Throwable $e) {
                report($e);

                return back()
                    ->withInput()
                    ->with('error', 'Upload QR/gambar pembayaran gagal. Pastikan format JPG/PNG/WEBP dan ukuran maksimal 5MB.');
            }
        }

        $payment->update($data);

        Cache::forget('active_payment_settings');

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui!');
    }

    public function toggleActive(PaymentSetting $payment)
    {
        $payment->update([
            'is_active' => !$payment->is_active,
        ]);

        Cache::forget('active_payment_settings');

        return back()->with('success', 'Status pembayaran diperbarui!');
    }
}