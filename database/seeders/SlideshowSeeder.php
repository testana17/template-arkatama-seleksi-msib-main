<?php

namespace Database\Seeders;

use App\Models\Cms\SlideShow;
use App\Models\Cms\SlideShowItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SlideshowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Storage::disk('public')->exists('slideshow')) {
            Storage::disk('public')->makeDirectory('slideshow');
        }

        if (! file_exists(storage_path('app/public/slideshow/slide1_dummy.png')) || ! file_exists(storage_path('app/public/slideshow/slide2_dummy.png'))) {
            \Illuminate\Support\Facades\File::copy(public_path('assets/images/slideshow/slide1.png'), storage_path('app/public/slideshow/slide1_dummy.png'));
            \Illuminate\Support\Facades\File::copy(public_path('assets/images/slideshow/slide2.png'), storage_path('app/public/slideshow/slide2_dummy.png'));
        }

        $slideshow = SlideShow::create([
            'name' => 'Slide Show Dummy',
            'description' => 'Slide Show Dummy',
            'is_active' => '1',
        ]);

        $slideItem = [
            [
                'slideshow_id' => $slideshow->id,
                'image' => 'slideshow/slide1_dummy.png',
                'title' => 'Rekognisi Pembelajaran Lampau',
                'caption' => 'Capaian Pembelajaran seseorang yang diperoleh dari pendidikan formal, nonformal, informal, dan/atau pengalaman kerja sebagai dasar untuk melanjutkan pendidikan formal dan untuk melakukan penyetaraan dengan kualifikasi tertentu',
                'order' => 1,
            ],
            [
                'slideshow_id' => $slideshow->id,
                'image' => 'slideshow/slide2_dummy.png',
                'title' => 'Keunggulan RPL',
                'caption' => 'Dengan raihan mata kuliah yang diperoleh melalui penyetaraan akademik atas pengalaman kerja dan pelatihan bersertifikasi, maka camaba bisa mempercepat waktu kelulusan dibandingkan dengan mahasiswa jalur reguler selama 8 semester.',
                'order' => 2,
            ],
        ];

        foreach ($slideItem as $item) {
            SlideShowItem::create($item);
        }
    }
}
