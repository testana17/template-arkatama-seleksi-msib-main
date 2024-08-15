<?php

namespace Database\Seeders;

use App\Models\Rpl\CPM;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\Matakuliah;
use Illuminate\Database\Seeder;

class MatakuliahCPMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::inRandomOrder()->first();

        $data = [
            [
                'cpm' => 'Memahami konsep dasar sosiologi dan antropologi serta hubungannya dengan pendidikan',
                'keterangan' => 'Sosiologi dan Antropologi',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menganalisis dampak perkembangan sosial dan budaya terhadap proses pendidikan',
                'keterangan' => 'Sosiologi dan Antropologi',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menerapkan teori-teori sosiologi dan antropologi dalam konteks pendidikan',
                'keterangan' => 'Sosiologi dan Antropologi',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menganalisis implikasi filsafat terhadap praktik pendidikan',
                'keterangan' => 'Filsafat dan Teori Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Memahami berbagai aliran filsafat dan teori pendidikan',
                'keterangan' => 'Filsafat dan Teori Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu mengkritisi dan merumuskan pandangan pribadi terhadap isu-isu pendidikan',
                'keterangan' => 'Filsafat dan Teori Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Memahami prinsip-prinsip dasar psikologi yang berkaitan dengan pendidikan',
                'keterangan' => 'Psikologi Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menganalisis perkembangan individu dan proses belajar-mengajar',
                'keterangan' => 'Psikologi Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menerapkan teori-teori psikologi dalam pengelolaan kelas dan bimbingan siswa',
                'keterangan' => 'Psikologi Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Memahami ajaran-ajaran dasar agama Islam dan prinsip-prinsip pendidikan Islam',
                'keterangan' => 'Pendidikan Agama Islam',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menganalisis peran pendidikan agama Islam dalam pembentukan karakter dan moralitas',
                'keterangan' => 'Pendidikan Agama Islam',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menerapkan nilai-nilai agama Islam dalam konteks pendidikan modern',
                'keterangan' => 'Pendidikan Agama Islam',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Memahami konsep dasar konseling dan peran konselor dalam pendidikan',
                'keterangan' => 'Pengantar Konseling',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu mengidentifikasi masalah-masalah psikologis dalam konteks pendidikan',
                'keterangan' => 'Pengantar Konseling',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menerapkan keterampilan dasar konseling dalam situasi-situasi pendidikan',
                'keterangan' => 'Pengantar Konseling',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu merumuskan gagasan-gagasan inovatif dalam bidang pendidikan',
                'keterangan' => 'Ilmu Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menganalisis peran lembaga pendidikan dalam masyarakat',
                'keterangan' => 'Ilmu Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Memahami perkembangan sistem pendidikan secara global',
                'keterangan' => 'Ilmu Pendidikan',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Memahami konsep dasar bimbingan dan konseling serta relevansinya dalam konteks pendidikan',
                'keterangan' => 'Dasar-dasar Bimbingan Dan Konseling Komprehensif',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menganalisis prinsip-prinsip bimbingan dan konseling dalam membantu perkembangan siswa',
                'keterangan' => 'Dasar-dasar Bimbingan Dan Konseling Komprehensif',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menerapkan teknik-teknik bimbingan dan konseling secara efektif',
                'keterangan' => 'Dasar-dasar Bimbingan Dan Konseling Komprehensif',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu berkomunikasi secara efektif dalam bahasa Indonesia, baik lisan maupun tulisan',
                'keterangan' => 'Bahasa Indonesia',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu menyusun dan menyajikan gagasan secara tertulis dengan jelas dan sistematis',
                'keterangan' => 'Bahasa Indonesia',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menguasai kaidah-kaidah bahasa Indonesia yang baik dan benar',
                'keterangan' => 'Bahasa Indonesia',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Dapat memahami konsep database dengan baik dan benar, serta dapat menerapkannya',
                'keterangan' => 'Pemrograman Dasar',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menguasai konsep dasar pemrograman seperti variabel, tipe data, struktur kendali, dan fungsi',
                'keterangan' => 'Pemrograman Dasar',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu memahami algoritma dasar dan menerapkannya dalam penyelesaian masalah sederhana',
                'keterangan' => 'Pemrograman Dasar',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menguasai konsep dasar pemrograman web dan teknologi-teknologi yang digunakan',
                'keterangan' => 'Pemrograman Web',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu mengembangkan situs web dinamis menggunakan bahasa pemrograman dan framework tertentu (misalnya, HTML, CSS, JavaScript, PHP, dan Laravel)',
                'keterangan' => 'Pemrograman Web',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu membangun halaman web menggunakan elemen dan atribut HTML dengan baik dan benar',
                'keterangan' => 'Pemrograman Web',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Menguasai konsep dasar pemrograman mobile',
                'keterangan' => 'Pemrograman Mobile',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu mengembangkan aplikasi mobile sederhana menggunakan bahasa pemrograman tertentu (misalnya, Java atau Kotlin untuk Android, Swift untuk iOS)',
                'keterangan' => 'Pemrograman Mobile',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'cpm' => 'Mampu membangun dan mendeploy aplikasi mobile ke platform yang dituju dengan baik dan benar',
                'keterangan' => 'Pemrograman Mobile',
                'is_active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            $data[$key]['matkul_id'] = Matakuliah::where('nama_mk', $value['keterangan'])->first()->id;
            CPM::create($data[$key]);
        }
    }
}
