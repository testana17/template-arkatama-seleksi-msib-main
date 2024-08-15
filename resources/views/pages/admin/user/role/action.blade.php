<div class="d-flex justify-content-center align-items-center gap-2">
    @can($globalModule['update'])
        <button data-action="edit" data-url="{{ route('users.role.edit', $role->id) }}" data-target="#edit-role_modal" data-title="{{ $role->name }}"
            class="btn btn-warning">
            <i class="fas fa-pen fs-4"></i>
        </button>
    @endcan
    <button type="button" class="btn btn-light dropdown-toggle" data-bs-boundary="viewport" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fas fa-ellipsis-v me-2 "></i>
    </button>
    <ul class="dropdown-menu">
        @can($globalModule['read'])
            <li>
                <a href="{{ route('users.role.show', $role->id) }}" class="btn w-100 text-start">
                    <i class="fas fa-eye fs-4"></i>
                    <span class="ms-2">Detail</span>
                </a>
            </li>
        @endcan
        @can($globalModule['delete'])
            <li>
                <button data-action="delete" data-url="{{ route('users.role.destroy', $role->id) }}"
                    data-table-id="roles-table" data-name="{{ $role->name }}" class="btn w-100 text-start">
                    <i class="fas fa-trash fs-4"></i>
                    <span class="ms-2">Hapus</span>
                </button>
            </li>
        @endcan
    </ul>
</div>
