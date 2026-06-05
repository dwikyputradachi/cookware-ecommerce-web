<?php

    namespace App\Http\Controllers;

    use App\Mail\AdminOtpMail;
    use App\Models\AdminOtp;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Mail;

    class AdminSecurityController extends Controller
    {
        private string $otpEmail = 'murazon45@gmail.com';

        public function index()
        {
            $admin = auth('admin')->user();

            return view('admin.security.index', compact('admin'));
        }

        public function sendOtp(Request $request)
        {
            $admin = auth('admin')->user();

            $otpCode = (string) random_int(100000, 999999);

            AdminOtp::where('admin_id', $admin->id)
                ->where('is_used', false)
                ->update(['is_used' => true]);

            AdminOtp::create([
                'admin_id' => $admin->id,
                'otp_code' => $otpCode,
                'expires_at' => now()->addMinutes(5),
                'is_used' => false,
            ]);

            Mail::to($this->otpEmail)->send(new AdminOtpMail($otpCode));

            return back()->with('success', 'Kode OTP berhasil dikirim ke email utama.');
        }

        public function updateAccount(Request $request)
        {
            $admin = \App\Models\Admin::findOrFail(auth('admin')->id());

            $validated = $request->validate([
                'email' => 'required|email|unique:admins,email,' . $admin->id,
                'password' => 'nullable|min:8|confirmed',
                'otp_code' => 'required|digits:6',
            ]);

            $otp = AdminOtp::where('admin_id', $admin->id)
                ->where('otp_code', $validated['otp_code'])
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (!$otp) {
                return back()->with('error', 'OTP salah, sudah expired, atau sudah pernah digunakan.');
            }

            $admin->email = $validated['email'];

            if (!empty($validated['password'])) {
                $admin->password = Hash::make($validated['password']);
            }

            $admin->save();

            $otp->update(['is_used' => true]);

            return redirect()
                ->route('admin.security.index')
                ->with('success', 'Email/password admin berhasil diperbarui.');
        }
    }