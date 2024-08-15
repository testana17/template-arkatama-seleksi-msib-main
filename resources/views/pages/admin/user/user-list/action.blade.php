<div class="d-flex justify-content-center align-items-center gap-2">

  <button data-action="edit" data-target="#edit-user_modal" data-url="{{ route('users.user-list.edit', $user->id) }}" class="btn btn-warning" data-title="{{ $user->name }}">
    <i class="fas fa-pen fs-4"></i>
  </button>

  <button type="button" class="btn btn-light dropdown-toggle" data-bs-boundary="viewport" data-bs-toggle="dropdown"
    aria-expanded="false">
    <i class="fas fa-ellipsis-v me-2 "></i>
  </button>
  <ul class="dropdown-menu">
    <li>
      <a href="{{ route('users.impersonate', $user->id) }}" class="btn w-100 text-start">
        <i class="fas fa-eye fs-4"></i>
        <span class="ms-2">Try Impersonate</span>
      </a>
    </li>
    <li>
        <button data-action="reset" data-id="{{$user->id}}" class="btn w-100 text-start">
          <i class="fas fa-key fs-4"></i>
          <span class="ms-2">Reset Password</span>
        </button>
    </li>
    <li>
      <button  data-url="{{ route('users.user-list.destroy', $user->id) }}"
        data-action="delete" data-table-id="userlist-table" data-name="{{ $user->name }}" class="btn w-100 text-start">
        <i class="fas fa-trash fs-4"></i>
        <span class="ms-2">Hapus</span>
      </button>
    </li>
  </ul>


</div>
