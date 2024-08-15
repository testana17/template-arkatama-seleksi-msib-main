<div class="d-flex justify-content-center align-items-center gap-2">
    @can($globalModule['update'])
        <a href="{{ route('setting.backup.edit', ['backupSchedule' => $backupSchedule]) }}" class="btn btn-warning">
            <i class="fas fa-pen fs-3"></i>
        </a>
    @endcan
    <button type="button" class="btn btn-light dropdown-toggle" data-bs-boundary="viewport" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fas fa-ellipsis-v me-2"></i>
    </button>
    <ul class="dropdown-menu">
        @can($globalModule['read'])
            <a href="{{ route('setting.backup.show', ['backupSchedule' => $backupSchedule]) }}"
                class="btn w-100 text-start">
                <i class="fas fa-eye fs-3"></i>
                <span class="ms-2">Detail</span>
            </a>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('setting.backup.destroy', ['backupSchedule' => $backupSchedule]) }}"
                data-action="delete" data-table-id="backupschedule-table" data-name="{{ $backupSchedule->name }}"
                class="btn w-100 text-start">
                <i class="fas fa-trash fs-3"></i>
                <span class="ms-2">Hapus</span>
            </button>
        @endcan
    </ul>
</div>
