<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class AdminSecurityController extends Controller
{
    private function google2fa(): Google2FA
    {
        return new Google2FA();
    }

    private function generateQrSvg(string $qrUrl): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(220),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($qrUrl);
    }

    public function index()
    {
        $admin = Admin::findOrFail(auth('admin')->id());

        $google2fa = $this->google2fa();

        $authenticatorEnabled = $admin->hasAuthenticatorEnabled();

        $setupSecret = null;
        $qrSvg = null;

        if (!$authenticatorEnabled) {
            $setupSecret = session('authenticator_setup_secret');

            if (!$setupSecret) {
                $setupSecret = $google2fa->generateSecretKey();
                session(['authenticator_setup_secret' => $setupSecret]);
            }

            $qrUrl = $google2fa->getQRCodeUrl(
                'Murazon Admin',
                $admin->email,
                $setupSecret
            );

            $qrSvg = $this->generateQrSvg($qrUrl);
        }

        return view('admin.security.index', compact(
            'admin',
            'authenticatorEnabled',
            'setupSecret',
            'qrSvg'
        ));
    }

    public function setupAuthenticator(Request $request)
    {
        $admin = Admin::findOrFail(auth('admin')->id());

        if ($admin->hasAuthenticatorEnabled()) {
            return back()->with('error', 'Authenticator sudah aktif.');
        }

        $validated = $request->validate([
            'authenticator_code' => 'required|digits:6',
        ], [
            'authenticator_code.required' => 'Kode Authenticator wajib diisi.',
            'authenticator_code.digits' => 'Kode Authenticator harus 6 digit.',
        ]);

        $setupSecret = session('authenticator_setup_secret');

        if (!$setupSecret) {
            return redirect()
                ->route('admin.security.index')
                ->with('error', 'Secret Authenticator tidak ditemukan. Silakan refresh halaman dan scan ulang QR.');
        }

        $valid = $this->google2fa()->verifyKey(
            $setupSecret,
            $validated['authenticator_code']
        );

        if (!$valid) {
            return back()->with('error', 'Kode Authenticator salah. Pastikan kode masih aktif.');
        }

        $admin->update([
            'authenticator_secret' => $setupSecret,
            'authenticator_enabled_at' => now(),
        ]);

        session()->forget('authenticator_setup_secret');

        return redirect()
            ->route('admin.security.index')
            ->with('success', 'Authenticator berhasil diaktifkan.');
    }

    public function resetAuthenticator(Request $request)
    {
        $admin = Admin::findOrFail(auth('admin')->id());

        $validated = $request->validate([
            'authenticator_code' => 'required|digits:6',
        ], [
            'authenticator_code.required' => 'Kode Authenticator wajib diisi.',
            'authenticator_code.digits' => 'Kode Authenticator harus 6 digit.',
        ]);

        if (!$admin->hasAuthenticatorEnabled()) {
            return back()->with('error', 'Authenticator belum aktif.');
        }

        $valid = $this->google2fa()->verifyKey(
            $admin->authenticator_secret,
            $validated['authenticator_code']
        );

        if (!$valid) {
            return back()->with('error', 'Kode Authenticator salah.');
        }

        $admin->update([
            'authenticator_secret' => null,
            'authenticator_enabled_at' => null,
        ]);

        session()->forget('authenticator_setup_secret');

        return redirect()
            ->route('admin.security.index')
            ->with('success', 'Authenticator berhasil direset. Silakan setup ulang.');
    }

    public function updateAccount(Request $request)
    {
        $admin = Admin::findOrFail(auth('admin')->id());

        if (!$admin->hasAuthenticatorEnabled()) {
            return back()->with('error', 'Aktifkan Authenticator terlebih dahulu sebelum mengubah email/password.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:8|confirmed',
            'authenticator_code' => 'required|digits:6',
        ], [
            'email.required' => 'Email admin wajib diisi.',
            'email.email' => 'Format email admin tidak valid.',
            'email.unique' => 'Email ini sudah digunakan admin lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'authenticator_code.required' => 'Kode Authenticator wajib diisi.',
            'authenticator_code.digits' => 'Kode Authenticator harus 6 digit.',
        ]);

        $valid = $this->google2fa()->verifyKey(
            $admin->authenticator_secret,
            $validated['authenticator_code']
        );

        if (!$valid) {
            return back()->withInput()->with('error', 'Kode Authenticator salah atau sudah tidak berlaku.');
        }

        $admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()
            ->route('admin.security.index')
            ->with('success', 'Email/password admin berhasil diperbarui.');
    }
}