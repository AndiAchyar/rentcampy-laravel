<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $products = Product::query()
            ->when($search !== '', fn ($q) => $q->where('name', 'ILIKE', '%'.addcslashes($search, '%_\\').'%'))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $editing = $request->filled('edit') ? Product::find($request->query('edit')) : null;

        return view('admin.products', compact('products', 'search', 'editing'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['image'] = $this->imageToDataUri($request->file('image'));
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, false);

        $error = DB::transaction(function () use ($request, $product, $data) {
            $product = Product::whereKey($product->id)->lockForUpdate()->first();
            $maxReserved = $product->maxReservedStock();
            if ($data['stock'] < $maxReserved) {
                return "Stok tidak dapat dikurangi di bawah {$maxReserved} unit yang sudah dipesan pada tanggal yang sama.";
            }

            // Tanpa gambar baru, gambar lama dipertahankan.
            if ($request->hasFile('image')) {
                $oldImage = $product->image;
                $data['image'] = $this->imageToDataUri($request->file('image'));
                $this->deleteStoredImage($oldImage);
            }
            $product->update($data);

            return null;
        });

        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        return redirect()->route('admin.products.index', $request->only('search', 'page'))
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $error = DB::transaction(function () use ($product) {
            $product = Product::whereKey($product->id)->lockForUpdate()->first();
            if ($product->hasActiveOrders()) {
                return 'Produk masih memiliki peminjaman aktif dan tidak dapat dihapus.';
            }
            $image = $product->image;
            $product->delete();
            $this->deleteStoredImage($image);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Produk berhasil dihapus!');
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0', 'max:2000000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:2048'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori wajib diisi.',
            'category.in' => 'Kategori tidak valid.',
            'description.required' => 'Deskripsi wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus bilangan bulat >= 0.',
            'price.min' => 'Harga harus bilangan bulat >= 0.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus bilangan bulat >= 0.',
            'stock.min' => 'Stok harus bilangan bulat >= 0.',
            'image.required' => 'Silakan pilih gambar untuk produk.',
            'image.image' => 'File yang dipilih bukan gambar.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);
    }

    /**
     * Simpan gambar sebagai data URI di database. Disk Vercel tidak permanen,
     * jadi file upload tidak bisa disimpan di storage/.
     */
    private function imageToDataUri(UploadedFile $file): string
    {
        return 'data:'.$file->getMimeType().';base64,'.base64_encode($file->get());
    }

    private function deleteStoredImage(?string $image): void
    {
        if ($image && ! str_starts_with($image, 'data:') && ! str_starts_with($image, 'http')) {
            Storage::disk('public')->delete($image);
        }
    }
}
