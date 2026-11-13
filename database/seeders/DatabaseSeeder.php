<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /** [nama, kategori, deskripsi, harga/hari, file foto di public/images/products] */
    private const PRODUCTS = [
        ['Tenda Dome 2 Orang', 'Tenda', 'Tenda dome ringan untuk 2 orang, double layer, anti air dan mudah dipasang. Cocok untuk pendakian.', 50000, '26_Tenda_Dome_2_Orang.png'],
        ['Tenda Dome 4 Orang', 'Tenda', 'Tenda dome kapasitas 4 orang dengan ruang luas dan ventilasi baik. Cocok untuk camping keluarga.', 80000, '27_Tenda_Dome_4_Orang.png'],
        ['Tenda Family 6 Orang', 'Tenda', 'Tenda besar untuk 6 orang dengan dua pintu dan teras. Ideal untuk camping rombongan.', 120000, '28_Tenda_Dome_6_Orang.png'],
        ['Tenda Ultralight 1 Orang', 'Tenda', 'Tenda super ringan untuk solo hiking, berat hanya sekitar 1,5 kg dan ringkas saat dilipat.', 40000, '1_Tenda_Ultralight.png'],
        ['Sleeping Bag Polar', 'Alat Tidur', 'Sleeping bag bahan polar yang hangat dan nyaman, cocok untuk suhu dingin di pegunungan.', 25000, '30_Sleeping_Bag_Polar.png'],
        ['Sleeping Bag Dacron', 'Alat Tidur', 'Sleeping bag dacron ringan dan mudah dicuci, cocok untuk camping di dataran rendah.', 20000, '29_Sleeping_Bag_Dacron.png'],
        ['Matras Gulung', 'Alat Tidur', 'Matras gulung busa tebal sebagai alas tidur, melindungi dari dinginnya tanah.', 10000, '2_Matras_Gulung.png'],
        ['Hammock', 'Alat Tidur', 'Hammock parasut kuat dan ringan, dilengkapi tali pengikat. Beban maksimal 200 kg.', 15000, '3_Hammock.png'],
        ['Kompor Portable', 'Peralatan Masak', 'Kompor gas portable dengan pemantik otomatis, praktis untuk memasak di alam terbuka.', 15000, '4_Kompor_Portable.png'],
        ['Nesting Set', 'Peralatan Masak', 'Satu set panci dan wajan aluminium yang bisa ditumpuk, hemat tempat di dalam tas.', 20000, '5_Nesting_Set.png'],
        ['Gas Kaleng', 'Peralatan Masak', 'Tabung gas butana untuk kompor portable, tersedia untuk sekali pakai selama camping.', 10000, '6_Gas_Kaleng.png'],
        ['Cooler Box', 'Peralatan Masak', 'Kotak pendingin untuk menyimpan makanan dan minuman tetap segar selama perjalanan.', 30000, '7_Cooler_Box.png'],
        ['Tenda Tunnel 4 Orang', 'Tenda', 'Tenda tunnel dengan ruang tidur luas dan teras untuk menyimpan perlengkapan camping.', 95000, '19_Tenda_Tunnel_4_Orang.png'],
        ['Tenda Geodesic 3 Orang', 'Tenda', 'Tenda geodesic kokoh untuk menghadapi cuaca berangin di area perkemahan.', 85000, '20_Tenda_Geodesis_3_Orang.png'],
        ['Tenda Camping Instan', 'Tenda', 'Tenda praktis dengan rangka otomatis yang mudah dibuka dan dilipat kembali.', 65000, '21_Tenda_Camping_Instan.png'],
        ['Flysheet Camping', 'Tenda', 'Flysheet serbaguna sebagai pelindung tambahan dari hujan dan terik matahari.', 20000, '22_Flysheet_Camping.png'],
        ['Sleeping Bag Mumet', 'Alat Tidur', 'Sleeping bag hangat dengan bentuk nyaman untuk menjaga suhu tubuh saat malam.', 28000, '23_Sleeping_Bag_Mumet.png'],
        ['Matras Angin', 'Alat Tidur', 'Matras angin ringan yang dapat dikempiskan agar ringkas dibawa saat hiking.', 25000, '24_Matras_Angin.png'],
        ['Bantal Angin', 'Alat Tidur', 'Bantal tiup ringan untuk menambah kenyamanan saat beristirahat di tenda.', 10000, '25_Bantal_Angin.png'],
        ['Selimut Camping', 'Alat Tidur', 'Selimut ringan sebagai lapisan tambahan agar tetap hangat saat beristirahat.', 18000, '12_Selimut_Camping.png'],
        ['Kursi Lipat Camping', 'Peralatan Masak', 'Kursi lipat ringan dengan rangka kuat untuk bersantai di area perkemahan.', 20000, '13_Kursi_Lipat.png'],
        ['Meja Lipat Camping', 'Peralatan Masak', 'Meja lipat ringkas untuk menyiapkan makanan dan perlengkapan camping.', 25000, '14_Meja_Lipat.png'],
        ['Lampu Camping LED', 'Peralatan Masak', 'Lampu LED hemat daya dengan pengait untuk menerangi bagian dalam tenda.', 15000, '15_Lampu_LED.png'],
        ['Peralatan Makan Set', 'Peralatan Masak', 'Set alat makan camping yang berisi piring, mangkuk, gelas, dan sendok.', 12000, '16_Peralatan_Makan_Set.png'],
        ['Teko Camping', 'Peralatan Masak', 'Teko aluminium ringan untuk merebus air menggunakan kompor portable.', 12000, '17_Teko_Camping.png'],
        ['Grill Portable', 'Peralatan Masak', 'Pemanggang lipat ringkas untuk memasak makanan di area camping.', 25000, '18_Grill_Portable.png'],
        ['Pompa Matras', 'Alat Tidur', 'Pompa tangan praktis untuk mengisi matras angin tanpa membutuhkan listrik.', 10000, '08_Pompa_Matras.png'],
        ['Tenda Dome 3 Orang', 'Tenda', 'Tenda dome tiga orang dengan ventilasi dan lapisan pelindung hujan.', 70000, '09_Tenda_Dome_3_Orang.png'],
        ['Nesting Mini', 'Peralatan Masak', 'Nesting ringkas untuk satu sampai dua orang, cocok untuk perjalanan singkat.', 15000, '10_Nesting_Mini.png'],
        ['Tenda Footprint', 'Tenda', 'Alas tambahan untuk melindungi bagian bawah tenda dari tanah lembap dan benda tajam.', 12000, '11_Tenda_Footprint.png'],
    ];

    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@gmail.com');
        $adminPassword = env('ADMIN_PASSWORD');
        if (! $adminPassword && app()->isProduction()) {
            // Di production jangan pernah membuat admin dengan password bawaan.
            $this->command?->warn('ADMIN_PASSWORD belum diisi: akun admin tidak dibuat.');
        } else {
            User::firstOrCreate(
                ['email' => strtolower($adminEmail)],
                ['password' => $adminPassword ?: 'admin123', 'role' => 'admin'],
            );
            $this->command?->info("Admin: {$adminEmail}");
        }

        $inserted = 0;
        $photosSet = 0;
        foreach (self::PRODUCTS as [$name, $category, $description, $price, $photo]) {
            $product = Product::firstOrNew(['name' => $name]);

            if (! $product->exists) {
                $product->fill([
                    'category' => $category,
                    'description' => $description,
                    'price' => $price,
                    'stock' => 5,
                ]);
                $inserted++;
            }

            // Pasang foto asli hanya jika produk belum punya foto sendiri
            // (kosong atau masih path lama di storage), agar upload admin tidak tertimpa.
            if (! $product->image || str_starts_with($product->image, 'products/seed/')) {
                $product->image = $this->storePhoto($photo);
                $photosSet++;
            }

            $product->save();
        }
        $this->command?->info("{$inserted} produk contoh ditambahkan, {$photosSet} foto dipasang.");
    }

    /** Foto produk bawaan disajikan langsung dari public/images/products. */
    private function storePhoto(string $file): string
    {
        $path = 'images/products/'.strtolower($file);
        if (! is_file(public_path($path))) {
            throw new RuntimeException("Foto produk tidak ditemukan: {$path}");
        }

        return $path;
    }
}
