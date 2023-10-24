<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Item::factory(100)->create();

        $datas = [
            [
                'name' => 'Panasonic AG-AC 120 EN',
                'item_category_id' => 1,
                'slug' => Str::slug('Panasonic AG-AC 120 EN'),
                // 'supplier_id' => 0,
                'description' => 'SpesifikasiResolusi : 2.2 MPLCD : 3.5"Batery : VW-VBG6Optical : 22x Memori : SD/SDHC/SDXCHarga : Rp. 31,450,000Garansi : Garansi Panasonic Gobel Indonesia',
                'buy_price' => 31450000,
                'quantity_in_stock' => 1,
            ],
            [
                'name' => 'Sony PXW-X70',
                'item_category_id' => 1,
                'slug' => Str::slug('Sony PXW-X70'),
                // 'supplier_id' => 0,
                'description' => 'Product Highlights :
                                    1" Exmor R CMOS Sensor
                                    HD Recording
                                    Built-In SD Media Card Slots
                                    Viewfinder &amp; Flip-Out LCD Screen
                                    XAVC, AVCHD, DV File Based Recording
                                    Slow &amp; Quick Motion
                                    3G-SDI &amp; HDMI Output
                                    Wireless LAN Control
                                    Optional Upgrade To UHD 4K',
                'buy_price' => 26999000,
                'quantity_in_stock' => 1,
            ],
            [
                'name' => 'Nikon D800E',
                'item_category_id' => 2,
                'slug' => Str::slug('Nikon D800E'),
                // 'supplier_id' => 0,
                'description' => 'Optimized for NEF Raw File Capture
                                    Modified OLP Filter for High Resolution
                                    36.3Mp CMOS FX Format Sensor
                                    EXPEED 3 Image-Processing Engine
                                    3.2″ LCD Monitor
                                    Nikon F Mount Lens Mount
                                    Eye-Level Pentaprism Viewfinder
                                    1920 x 1080/30/25/24p HD Video Capture
                                    Built-In Flash + i-TTL Flash Control
                                    Matrix/Center-Weighted/Spot Metering',
                'buy_price' => 32410000,
                'quantity_in_stock' => 1,
            ],
            [
                'name' => 'Canon EOS 6D',
                'item_category_id' => 2,
                'slug' => Str::slug('Canon EOS 6D'),
                // 'supplier_id' => 0,
                'description' => 'Hadir sebagai DSLR paling ringan dalam jajaran EOS full-frame DSLR, EOS 6D Mark II adalah kamera DSLR yang dahsyat namun tetap ringkas, yang mampu membawa karya Anda ke level berikutnya. Sensor 26,2 megapiksel, Dual Pixel CMOS AF, dan layar sentuh LCD Vari-angle yang dimiliki kamera ini memberikan Anda kemudahan untuk mengambil foto dan video yang memukau. Anda dapat mengabadikan momen dengan AF cepat, yang dapat dioperasikan melalui layar sentuh dan dari sudut yang berbeda-beda. Movie digital IS, sistem stabilisasi gambar 5-poros, ditanamkan pada kamera ini untuk menekan guncangan kamera apabila merekam film dengan genggaman tangan — fitur yang berguna, khususnya apabila merekam dalam kualitas setinggi Full HD 60p / 50p.',
                'buy_price' => 31290000,
                'quantity_in_stock' => 2
            ],
            [
                'name' => 'DJI MAVIC AIR',
                'item_category_id' => 3,
                'slug' => Str::slug('DJI MAVIC AIR'),
                // 'supplier_id' => 0,
                'description' => 'Hadir sebagai DSLR paling ringan dalam jajaran EOS full-frame DSLR, EOS 6D Mark II adalah kamera DSLR yang dahsyat namun tetap ringkas, yang mampu membawa karya Anda ke level berikutnya. Sensor 26,2 megapiksel, Dual Pixel CMOS AF, dan layar sentuh LCD Vari-angle yang dimiliki kamera ini memberikan Anda kemudahan untuk mengambil foto dan video yang memukau. Anda dapat mengabadikan momen dengan AF cepat, yang dapat dioperasikan melalui layar sentuh dan dari sudut yang berbeda-beda. Movie digital IS, sistem stabilisasi gambar 5-poros, ditanamkan pada kamera ini untuk menekan guncangan kamera apabila merekam film dengan genggaman tangan — fitur yang berguna, khususnya apabila merekam dalam kualitas setinggi Full HD 60p / 50p.',
                'buy_price' => 17263037,
                'quantity_in_stock' => 1
            ],
            [
                'name' => 'Libec 650EX',
                'item_category_id' => 4,
                'slug' => Str::slug('Libec 650EX'),
                // 'supplier_id' => 0,
                'description' => null,
                'buy_price' => 17263037,
                'quantity_in_stock' => 3
            ],
            [
                'name' => 'GOPRO HERO 5 BLACK',
                'item_category_id' => 5,
                'slug' => Str::slug('GOPRO HERO 5 BLACK'),
                // 'supplier_id' => 0,
                'description' => 'Hadir untuk melanjutkan kesuksesan Kamera Action pendahulunya, GOPRO Hero 5 membawa perubahan yang signifikan dan peningkatan kualitas yang membuat anda tak akan ragu untuk memilihnya. GOPRO Hero 5 hadir sebagai kamera action yang paling tinggi kualitas dan kekuatannya serta tetap mudah digunakan bahkan oleh seorang pemula. Penggunaan komponen pilihan menjadikan produk ini menjadi lebih kokoh dan tahan pada kondisi yang ekstrim sekalipun. Ukuran yang tetap kompak membuat produk ini juga lebih mudah digunakan dan dipasangkan dengan beragam mounting yang tersedia. Tersedianya teknologi Simple One Button juga menjadi solusi yang menarik bagi anda yang tak ingin direpotkan ketika hendak mengoperasikan produk kamera action GOPRO Hero 5.',
                'buy_price' => 2450000,
                'quantity_in_stock' => 1
            ],
        ];

        Item::insert($datas);
    }
}
