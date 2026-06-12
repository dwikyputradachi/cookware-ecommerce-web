<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'is_active',
        'created_by',
        'updated_by',
        'show_in_menu',
        'menu_order',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function defaultPages(): array
    {
        return [
            'about-us' => 'Tentang Kami',
            'garansi' => 'Kebijakan Garansi',
            'return' => 'Ketentuan return barang dan penggantian uang',
            'panduan' => 'Panduan Belanja',
            'penipuan' => 'Waspada Penipuan',
            'bantuan' => 'Bantuan',
        ];
    }

    public static function ensureDefaultPagesExist(): void
    {
        $creatorId = auth('admin')->id() ?? 1;

        foreach (self::defaultPages() as $slug => $title) {
            self::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'content' => '<p class="text-gray-600 leading-relaxed">Konten halaman ini belum tersedia. Silakan edit melalui panel admin.</p>',
                    'is_active' => true,
                ]
            );
        }
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
