<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSettingSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['key' => 'bca',  'label' => 'Transfer BCA',  'account_number' => '8620-XXXX-XXXX', 'account_name' => 'Murazon Cookware', 'is_active' => true],
            ['key' => 'dana', 'label' => 'Akun DANA',     'account_number' => '0822-XXXX-XXXX', 'account_name' => 'Murazon Cookware', 'is_active' => true],
            ['key' => 'qris', 'label' => 'QRIS',          'account_number' => null,              'account_name' => 'Murazon Cookware', 'is_active' => true],
            ['key' => 'cod',  'label' => 'COD',           'account_number' => 'Bayar di tempat', 'account_name' => null,               'is_active' => true],
        ];

        foreach ($methods as $m) {
            DB::table('payment_settings')->updateOrInsert(['key' => $m['key']], array_merge($m, [
                'created_at' => now(), 'updated_at' => now()
            ]));
        }
    }
}