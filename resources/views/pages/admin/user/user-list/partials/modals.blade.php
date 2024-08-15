<x-mollecules.modal id="edit-user_modal" action="/users/user-list/{id}" tableId="userlist-table" method="PUT">
  <x-slot:title>Edit User</x-slot:title>
  <div class="mb-3">
    <x-atoms.form-label for="name_field2" required>Nama</x-atoms.form-label>
    <x-atoms.input name="name" id="name_field2" placeholder="Masukkan Nama" />
  </div>
  <div class="mb-3">
    <x-atoms.form-label for="role_field2" required>Role</x-atoms.form-label>
    <x-atoms.select2 name="role" id="role_field2" :lists="c_option($rolesRef, 'name', 'name')" placeholder="Pilih Role" />
  </div>
  <div class="mb-3">
    <x-atoms.form-label for="email_field2" required>Email</x-atoms.form-label>
    <x-atoms.input type="email" name="email" id="email_field2" placeholder="Masukkan Email" />
  </div>
  <div class="mb-3">
    <x-atoms.form-label for="password_field2" required>Password</x-atoms.form-label>
    <x-atoms.input type="password" name="password" id="password_field2"
      placeholder="Masukkan password baru , atau biarkan kosong " />
  </div>
  <x-slot:footer>
    <button type="submit" class="btn btn-primary">Simpan User</button>
  </x-slot:footer>
</x-mollecules.modal>

<x-mollecules.modal id="reset-user_modal" tableId="userlist-table" action="">
  <x-slot:title>Reset Password</x-slot:title>
  <div class="mb-3">
    <x-atoms.form-label for="name_field2" required>Nama</x-atoms.form-label>
    <x-atoms.input name="name" id="name_field" readonly />
  </div>
  <div class="mb-3">
    <x-atoms.form-label for="role_field" required>Role</x-atoms.form-label>
    <x-atoms.input name="role" id="role_field" readonly />
  </div>
  <div class="mb-3">
    <x-atoms.form-label for="email_field" required>Email</x-atoms.form-label>
    <x-atoms.input type="email" name="email" id="email_field" readonly />
  </div>
  <x-slot:footer>
    <button type="submit" class="btn btn-primary">Reset Password</button>
  </x-slot:footer>
</x-mollecules.modal>

<x-mollecules.modal id="filter-user_modal" :closeOnBlur="true" :hasCloseBtn="false" action="" :custom="true" :resetOnClose="false">
  <x-slot:title>Gunakan Filter</x-slot:title>
  <x-slot:iconClose>
    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
      aria-label="Close" cancel-btn>
      <i class="fal fa-times fs-4"></i>
    </button>
  </x-slot:iconClose>
  <div class="row gy-4">
    <div class="pb-5">
      <x-atoms.form-label for="kepala_keluarga_field4" required>Role</x-atoms.form-label>
      <x-atoms.select name="role_id" placeholder="Pilih Role" id="role_field4">
        @foreach ($rolesRef as $role)
          <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
      </x-atoms.select>
    </div>
  </div>
  <x-slot:footer>
    <div class="d-flex gap-2">
      <button type="reset" class="btn btn-danger">Reset</button>
      <button type="submit" class="btn btn-primary">Terapkan Filter</button>
    </div>
  </x-slot:footer>
</x-mollecules.modal>
