@extends('layouts.auth')

@section('content')
  <div class="">
    <div class=" position-relative mb-4"
      style="background: rgb(9,79,183); background: linear-gradient(97deg, rgba(9,79,183,1) 0%, rgba(53,96,160,1) 87%);">
      <img src="{{ asset('landing/images/svgs/acc-reg-2.svg') }}" alt=""
        class=" position-absolute end-0  d-none d-lg-block z-index-0" style="top: 25%">
      <img src="{{ asset('landing/images/svgs/acc-reg-1.svg') }}" alt=""
        class=" position-absolute d-none d-lg-block  z-index-0" style="bottom:0; ">
      <div class="container d-flex flex-column justify-content-center align-items-center py-4 text-center">
        <h1 class="fw-bolder text-white" style="font-size: 56px">Pendaftaran</h1>
        <h1 class="fw-bolder text-white" style="font-size: 56px">PMB Jalur RPL</h1>
      </div>
    </div>
    <div class="container bg-white my-5" style="box-shadow: 0px 1px 22px 0px #15223214;">
      <div class="py-5 px-5 d-flex flex-column align-content-center gap-4">
        <div class="d-flex flex-column gap-2">
          @if (!$is_timeline_exist || $pilihanProdis->isEmpty())
            <div class="text-center">
              <img src="{{ asset('landing/images/svgs/undraw_cancel_u1it.svg') }}" alt="" class="w-50">
            </div>
            <div class="text-center">
              <h1 class="fw-semibold fs-6">Pendaftaran Ditutup</h1>
              <p class="fw-normal fs-4">Mohon maaf, pendaftaran telah ditutup. Silahkan hubungi panitia PMB untuk informasi lebih lanjut.</p>
            </div>
          @else
            <div class="">
              <form method="POST" action="{{ URL::to('register') }}" class="d-flex flex-column gap-2" id="form-register">
                @csrf
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <x-atoms.form-label for="prodis" required>Prodi Pilihan</x-atoms.form-label>
                    <x-atoms.select class="form-select" name="prodis" id="prodis">
                      @foreach ($pilihanProdis as $pilihanProdi)
                        <option value="{{ $pilihanProdi->programStudi->id }}">
                          {{ $pilihanProdi->programStudi->nama_prodi }}
                          ({{ $pilihanProdi->programStudi->singkatan }})
                        </option>
                      @endforeach
                    </x-atoms.select>
                  </div>
                </div>
                <div id="form-registration" style="display: none">
                  <div class="p-3 mb-3" style="background: #F5F6FA">
                    <p class="m-0 fs-3" style="color: #5A607F">Perhatian : Harap memasukan data
                      pendaftaran
                      dengan benar</p>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="nik_field">NIK</x-atoms.form-label>
                      <x-atoms.input type="text" name="nik" id="nik_field" maxlength="16" placeholder="Masukan NIK"
                        value="{{ old('nik') }}" />
                    </div>
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="nama_lengkap_field">Nama
                        Lengkap</x-atoms.form-label>
                      <x-atoms.input type="text" name="nama_lengkap" id="nama_lengkap_field"
                        placeholder="Masukan Nama Lengkap" value="{{ old('nama_lengkap') }}" />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="instansi_field">Asal
                        Instansi</x-atoms.form-label>
                      <x-atoms.input type="text" name="instansi" id="instansi_field"
                        placeholder="Masukan Asal Instansi" value="{{ old('instansi') }}" />
                    </div>
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="provinsi_field">Asal
                        Provinsi</x-atoms.form-label>
                      <x-atoms.select2 name="provinsi" id="provinsi_field" placeholder="Cari Provinsi"
                        source="{{ route('reference.provinsi') }}" />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="email_field">Email</x-atoms.form-label>
                      <x-atoms.input type="email" name="email" id="email_field" placeholder="Masukan Email"
                        value="{{ old('email') }}" />
                    </div>
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="phone_field">Nomor
                        Handphone</x-atoms.form-label>
                      <x-atoms.input type="text" name="nomor_telepon" id="phone_field"
                        placeholder="Masukan Nomor Handphone" value="{{ old('phone') }}" />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="password_field">Password</x-atoms.form-label>
                      <x-atoms.input type="password" name="password" id="password_field" placeholder="Masukan Password" />
                    </div>
                    <div class="col-md-6 mb-3">
                      <x-atoms.form-label required for="password_confirmation_field">Konfirmasi
                        Password</x-atoms.form-label>
                      <x-atoms.input type="password" name="password_confirmation" id="password_confirmation_field"
                        placeholder="Masukan Konfirmasi Password" />
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-center">
                    <button type="submit"
                      class="btn btn-primary btn-succes w-50 py-3 rounded-2 fw-semibold">Daftar</button>
                  </div>
                </div>
              </form>
            </div>
          @endif
        </div>
      </div>
    </div>

    <script>
      // Dapatkan elemen select prodis
      let prodisSelect = document.getElementById('prodis');
      let inputAsalInstansi = document.getElementById('instansi_field');
      let inputNama = document.getElementById('nama_lengkap_field');
      let inputPhone = document.getElementById('phone_field');
      let inputNIK = document.getElementById('nik_field');

      // Tambahkan event listener untuk menangani perubahan
      prodisSelect.addEventListener('change', function() {
        // Dapatkan nilai yang dipilih
        let selectedValue = prodisSelect.value;

        // Dapatkan elemen form-registration
        let formRegistration = document.getElementById('form-registration');

        // Periksa apakah nilai yang dipilih ada
        if (selectedValue) {
          // Jika ada, tampilkan form-registration
          formRegistration.style = 'display: block';
        } else {
          // Jika tidak, sembunyikan form-registration
          formRegistration.style = 'display: none';
        }
      });

      inputAsalInstansi.addEventListener('input', function(ev) {
        ev.target.value = ev.target.value.replace(/[^a-zA-Z0-9\s]/g, '');
      });

      inputNama.addEventListener('input', function() {
        validateInputChar(event.target);
      });

      inputNIK.addEventListener('input', function(e) {
        var value = e.target.value;
        e.target.value = value.replace(/[^0-9]/g, '');
      });

      inputPhone.addEventListener('input', function() {
        let value = event.target.value;
        event.target.value = value.replace(/[^0-9]/g, '');
      });
    </script>
  @endsection

  @push('scripts')
    <script>
      $(document).on('form-submitted:form-register', function() {
        window.location.href = "{{ route('login') }}";
      });
    </script>
  @endpush
