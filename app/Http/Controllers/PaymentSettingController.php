<?php
namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class PaymentSettingController extends Controller
{
    private function uploadToCloudinary($file): string
    {
        $parsed = parse_url(env('CLOUDINARY_URL'));
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $parsed['host'],
                'api_key'    => $parsed['user'],
                'api_secret' => $parsed['pass'],
            ]
        ]);
        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'payment'
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
        $request->validate([
            'label'          => 'required|string|max:100',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:100',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'label'          => $request->label,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'is_active'      => $request->boolean('is_active'),
        ];

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $this->uploadToCloudinary($request->file('qr_image'));
        }

        $payment->update($data);

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Metode pembayaran berhasil diperbarui!');
    }

    public function toggleActive(PaymentSetting $payment)
    {
        $payment->update(['is_active' => !$payment->is_active]);
        return back()->with('success', 'Status pembayaran diperbarui!');
    }
}