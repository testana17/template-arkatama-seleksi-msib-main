<?php

namespace Database\Seeders;

use App\Models\Cms\FAQs;
use App\Models\User;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faqsData = [
            [
                'question' => 'Apa itu Jalur RPL?',
                'answer' => 'Jalur RPL adalah jalur penerimaan mahasiswa baru yang memungkinkan pengakuan atas pengalaman belajar yang telah diperoleh di luar pendidikan formal, seperti kursus, pelatihan, atau pengalaman kerja yang relevan dengan program studi yang ditawarkan.',
                'is_active' => '1',
                'created_by' => User::get()->first()->id,
                'updated_by' => User::get()->first()->id,
            ],
            [
                'question' => 'Bagaimana cara mendaftar melalui Jalur RPL?',
                'answer' => 'Untuk mendaftar melalui Jalur RPL, Anda perlu mengisi formulir pendaftaran online dan menyertakan dokumen pendukung yang diperlukan, seperti transkrip nilai, sertifikat kursus, dan bukti-bukti pengalaman belajar lainnya.',
                'is_active' => '1',
                'created_by' => User::get()->first()->id,
                'updated_by' => User::get()->first()->id,
            ],
            [
                'question' => 'Apa saja dokumen yang harus disiapkan untuk pendaftaran Jalur RPL?',
                'answer' => 'Dokumen yang perlu disiapkan termasuk transkrip nilai, sertifikat kursus atau pelatihan, portfolio (jika diperlukan), surat referensi (jika diperlukan), dan dokumen-dokumen lain yang mendukung pengalaman belajar Anda.',
                'is_active' => '1',
                'created_by' => User::get()->first()->id,
                'updated_by' => User::get()->first()->id,
            ],
            [
                'question' => 'Berapa lama proses seleksi melalui Jalur RPL?',
                'answer' => 'Proses seleksi melalui Jalur RPL biasanya memakan waktu sekitar [jumlah waktu] setelah pendaftaran ditutup. Namun, waktu ini dapat bervariasi tergantung pada jumlah pendaftar dan kompleksitas penilaian.',
                'is_active' => '1',
                'created_by' => User::get()->first()->id,
                'updated_by' => User::get()->first()->id,
            ],
            [
                'question' => 'Apakah ada beasiswa yang tersedia untuk mahasiswa Jalur RPL?',
                'answer' => 'Ya, kami menyediakan berbagai program beasiswa dan bantuan keuangan untuk mahasiswa Jalur RPL yang memenuhi syarat. Informasi lebih lanjut tentang program-program ini dapat ditemukan di [link informasi beasiswa dan bantuan keuangan].',
                'is_active' => '1',
                'created_by' => User::get()->first()->id,
                'updated_by' => User::get()->first()->id,
            ],
        ];

        foreach ($faqsData as $data) {
            FAQs::create($data);
        }
    }
}
