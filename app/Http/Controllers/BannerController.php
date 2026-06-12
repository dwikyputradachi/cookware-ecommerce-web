<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
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
            'folder' => 'banners',
            'resource_type' => 'image',
            'transformation' => [
                [
                    'width' => 1920,
                    'height' => 800,
                    'crop' => 'limit',
                    'quality' => 'auto:good',
                ],
            ],
        ]);

        return $result['secure_url'];
    }

    public function index()
    {
        $banners = Banner::orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10);

        $activeCount = Banner::where('is_active', true)->count();

        return view('admin.banners.index', compact('banners', 'activeCount'));
    }

    public function create()
    {
        $activeCount = Banner::where('is_active', true)->count();

        $nextSortOrder = ((int) Banner::max('sort_order')) + 1;

        return view('admin.banners.create', compact('activeCount', 'nextSortOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
            'link'       => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ], [
            'image.required' => 'Gambar banner wajib diupload.',
            'image.mimes'    => 'Format banner harus JPG, JPEG, PNG, atau WEBP.',
            'image.max'      => 'Ukuran banner maksimal 5MB.',
        ]);

        $isActive = $request->boolean('is_active');

        if ($isActive && Banner::where('is_active', true)->count() >= 5) {
            return back()
                ->withInput()
                ->with('error', 'Maksimal 5 banner aktif. Nonaktifkan banner lain terlebih dahulu.');
        }

        try {
            $imageUrl = $this->uploadToCloudinary($request->file('image'));
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Upload banner gagal. Pastikan format JPG/PNG/WEBP dan ukuran maksimal 5MB.');
        }

        Banner::create([
            'title'      => $validated['title'] ?? null,
            'image'      => $imageUrl,
            'link'       => $validated['link'] ?? null,
            'is_active'  => $isActive,
            'sort_order' => $validated['sort_order'] ?? (((int) Banner::max('sort_order')) + 1),
        ]);

        Cache::forget('active_banners');

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan!');
    }

    public function edit(Banner $banner)
    {
        $activeCount = Banner::where('is_active', true)->count();

        $nextSortOrder = $banner->sort_order ?? 0;

        return view('admin.banners.edit', compact('banner', 'activeCount', 'nextSortOrder'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'link'       => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ], [
            'image.mimes' => 'Format banner harus JPG, JPEG, PNG, atau WEBP.',
            'image.max'   => 'Ukuran banner maksimal 5MB.',
        ]);

        $isActive = $request->boolean('is_active');

        if ($isActive && !$banner->is_active && Banner::where('is_active', true)->count() >= 5) {
            return back()
                ->withInput()
                ->with('error', 'Maksimal 5 banner aktif. Nonaktifkan banner lain terlebih dahulu.');
        }

        $data = [
            'title'      => $validated['title'] ?? null,
            'link'       => $validated['link'] ?? null,
            'is_active'  => $isActive,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            try {
                $data['image'] = $this->uploadToCloudinary($request->file('image'));
            } catch (\Throwable $e) {
                report($e);

                return back()
                    ->withInput()
                    ->with('error', 'Upload banner gagal. Pastikan format JPG/PNG/WEBP dan ukuran maksimal 5MB.');
            }
        }

        $banner->update($data);

        Cache::forget('active_banners');

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        Cache::forget('active_banners');

        return back()->with('success', 'Banner berhasil dihapus!');
    }

    public function toggleActive(Banner $banner)
    {
        if (!$banner->is_active && Banner::where('is_active', true)->count() >= 5) {
            return back()->with('error', 'Maksimal 5 banner aktif.');
        }

        $banner->update([
            'is_active' => !$banner->is_active,
        ]);

        Cache::forget('active_banners');

        return back()->with('success', 'Status banner diperbarui!');
    }
}