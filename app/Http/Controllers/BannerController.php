<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class BannerController extends Controller
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
            'folder' => 'banners'
        ]);

        return $result['secure_url'];
    }

    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(10);
        $activeCount = Banner::where('is_active', true)->count();
        return view('admin.banners.index', compact('banners', 'activeCount'));
    }

    public function create()
    {
        $activeCount = Banner::where('is_active', true)->count();
        return view('admin.banners.create', compact('activeCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link'       => 'nullable|url',
            'sort_order' => 'integer|min:0',
        ]);

        $isActive = $request->boolean('is_active');

        // Limit 5 banner aktif
        if ($isActive && Banner::where('is_active', true)->count() >= 5) {
            return back()->withInput()->with('error', 'Maksimal 5 banner aktif. Nonaktifkan banner lain terlebih dahulu.');
        }

        Banner::create([
            'title'      => $request->title,
            'image'      => $this->uploadToCloudinary($request->file('image')),
            'link'       => $request->link,
            'is_active'  => $isActive,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan!');
    }

    public function edit(Banner $banner)
    {
        $activeCount = Banner::where('is_active', true)->count();
        return view('admin.banners.edit', compact('banner', 'activeCount'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link'       => 'nullable|url',
            'sort_order' => 'integer|min:0',
        ]);

        $isActive = $request->boolean('is_active');

        // Cek limit hanya jika banner ini mau diaktifkan dan sebelumnya tidak aktif
        if ($isActive && !$banner->is_active && Banner::where('is_active', true)->count() >= 5) {
            return back()->withInput()->with('error', 'Maksimal 5 banner aktif.');
        }

        $data = [
            'title'      => $request->title,
            'link'       => $request->link,
            'is_active'  => $isActive,
            'sort_order' => $request->sort_order ?? 0,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Banner berhasil dihapus!');
    }

    public function toggleActive(Banner $banner)
    {
        if (!$banner->is_active && Banner::where('is_active', true)->count() >= 5) {
            return back()->with('error', 'Maksimal 5 banner aktif.');
        }

        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Status banner diperbarui!');
    }
}