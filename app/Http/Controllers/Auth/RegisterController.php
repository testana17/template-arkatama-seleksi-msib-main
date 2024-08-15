<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Timeline;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\Register;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use ResponseFormatter;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $currentTahunAjaranId = TahunAjaran::getCurrent()['id'];

        return Validator::make($data, [
            'prodis' => ['required', 'string'],
            'nik' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('registers')->where(function ($query) use ($currentTahunAjaranId, $data) {
                    return $query->where('nik', $data['nik'])
                        ->where('tahun_ajaran_id', $currentTahunAjaranId);
                }),
            ],
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:255'],
            'instansi' => ['required', 'string', 'max:255'],
            'provinsi' => ['required', 'max:10'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('registers')->where(function ($query) use ($currentTahunAjaranId, $data) {
                    return $query->where('email', $data['email'])
                        ->where('tahun_ajaran_id', $currentTahunAjaranId);
                }),
            ],
            'nomor_telepon' => [
                'required',
                'numeric',
                Rule::unique('registers')->where(function ($query) use ($currentTahunAjaranId, $data) {
                    return $query->where('nomor_telepon', $data['nomor_telepon'])
                        ->where('tahun_ajaran_id', $currentTahunAjaranId);
                }),
            ],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ], [
            'prodis.required' => 'Program Studi harus diisi',
            'prodis.string' => 'Program Studi harus berupa string',
            'nik.required' => 'NIK harus diisi',
            'nik.string' => 'NIK harus berupa string',
            'nik.digits' => 'NIK harus berupa 16 digit angka',
            'nik.unique' => 'NIK sudah terdaftar',
            'nama_lengkap.required' => 'Nama Lengkap harus diisi',
            'nama_lengkap.string' => 'Nama Lurator harus berupa string',
            'nama_lengkap.min' => 'Nama Lengkap minimal terdiri dari 3 karakter',
            'nama_lengkap.max' => 'Nama Lengkap tidak boleh lebih dari 255 karakter',
            'instansi.max' => 'Instansi tidak boleh lebih dari 255 karakter',
            'instansi.string' => 'Instansi harus berupa string',
            'instansi.required' => 'Instansi harus diisi',
            'provinsi.required' => 'Provinsi harus diisi',
            'provinsi.max' => 'Provinsi tidak boleh lebih dari 10 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus berupa email',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'nomor_telepon.required' => 'Nomor Telepon harus diisi',
            'nomor_telepon.numeric' => 'Nomor Telepon harus berupa angka',
            'nomor_telepon.unique' => 'Nomor Telepon sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa string',
            'password.min' => 'Password minimal 8 karakter',
            'password_confirmation.required' => 'Konfirmasi Password harus diisi',
            'password_confirmation.string' => 'Konfirmasi Password harus berupa string',
            'password_confirmation.min' => 'Konfirmasi Password minimal 8 karakter',
            'password_confirmation.same' => 'Konfirmasi Password tidak sesuai dengan Password',
        ]);
    }

    public function showRegistrationForm()
    {
        $pilihanProdis = ProdiPilihan::with(['programStudi'])
            ->withCount(['register as register_count' => function ($q) {
                $q->whereHas('pembayaran');
            }])
            ->where('tahun_ajaran_id', optional(TahunAjaran::getCurrent())['id'])
            ->where('is_active', '1')
            ->whereDate('tanggal_mulai_pendaftaran', '<=', now())
            ->whereDate('tanggal_selesai_pendaftaran', '>', now())
            ->havingRaw('register_count < kuota_pendaftar')
            ->get();
        $is_timeline_exist = Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->count() > 0;

        return view('auth.register', compact('pilihanProdis', 'is_timeline_exist'));
    }

    public function register(\Illuminate\Http\Request $request)
    {
        $this->validator($request->all())->validate();
        try {
            $user = $this->create($request->all());
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th->getMessage());
        }

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        // return $request->wantsJson()
        //     ? new \Illuminate\Http\JsonResponse([], 201)
        //     : redirect($this->redirectPath())->with('success', 'Registrasi berhasil. Silakan periksa email Anda untuk verifikasi.');
        return ResponseFormatter::success('Registrasi berhasil. Silakan login untuk melanjutkan pendaftaran.');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            $prodi_id = $data['prodis'];
            $prodi_pilihan = ProdiPilihan::where('prodi_id', $prodi_id)
                ->where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])
                ->first();

            if (! $prodi_pilihan) {
                throw new \Exception('Jadwal pendaftaran tidak ditemukan.');
            }
            if (! Carbon::now()->between($prodi_pilihan->tanggal_mulai_pendaftaran, $prodi_pilihan->tanggal_selesai_pendaftaran)) {
                throw new \Exception('Pendaftaran telah ditutup. Silakan hubungi panitia PMB untuk informasi lebih lanjut.');
            }

            DB::beginTransaction();
            $kode_pendaftaran = 'KP'.rand(100000, 999999);

            if (substr($data['nomor_telepon'], 0, 1) === '0') {
                $data['nomor_telepon'] = '62'.substr($data['nomor_telepon'], 1);
            }

            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                // 'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make($data['password']),
                'remember_token' => Str::random(10),
            ]);

            $user->assignRole('camaba');

            $registerInstance = Register::create([
                'prodi_pilihan_id' => $prodi_pilihan->id,
                'kode_pendaftaran' => $kode_pendaftaran,
                'nik' => $data['nik'],
                'user_id' => (int) $user->id,
                'tahun_ajaran_id' => (int) TahunAjaran::getCurrent()['id'],
                'prodi_id' => $prodi_id,
                'asal_instansi' => $data['instansi'],
                'provinsi_id' => (int) $data['provinsi'],
                'nama_lengkap' => $user->name,
                'email' => $user->email,
                'nomor_telepon' => $data['nomor_telepon'],
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
