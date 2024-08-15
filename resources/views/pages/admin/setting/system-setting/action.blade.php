<div class="d-flex justify-content-center align-items-center ">

    @if ($systemSetting->trashed())
        @can($globalModule['update'])
            <button data-action="restore" title="restore setting" id="restore" class="btn btn-success"
                data-action-text="Apakah anda yakin untuk me-restore setting ini ?" data-action-method="POST"
                data-action-url="{{ route('setting.system-setting.restore', $systemSetting->id) }}" action-need-confirm>
                <i class="fas fa-recycle fs-3"></i>
            </button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('setting.system-setting.edit', $systemSetting->id) }}" data-title=" {{ $systemSetting->name }}"
                data-target="#edit-setting_modal" class="me-2 btn btn-warning">
                <i class="fas fa-pen fs-4"></i>
            </button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('setting.system-setting.destroy', $systemSetting->id) }}" data-action="delete"
                data-table-id="system-setting-table" data-name="{{ $systemSetting->name }}" class="btn btn-danger">
                <i class="fas fa-trash fs-4"></i>
            </button>
        @endcan
    @endif
</div>
