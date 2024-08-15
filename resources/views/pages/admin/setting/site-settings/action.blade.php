<div class="d-flex justify-content-center align-items-center gap-2">

    @if ($siteSetting->trashed())
        @can($globalModule['update'])
            <button data-action="restore" title="restore setting" id="restore" class="btn btn-success"
                data-action-text="Apakah anda yakin untuk me-restore setting ini ?" data-action-method="POST"
                data-action-url="{{ route('setting.site-settings.restore', $siteSetting->id) }}" action-need-confirm>
                <i class="fas fa-recycle fs-3"></i>
            </button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('setting.site-settings.edit', $siteSetting->id) }}" data-title="{{ $siteSetting->name }}"
                data-target="#edit-site-setting_modal" class="btn btn-warning ">
                <i class="fas fa-pen fs-4"></i>
            </button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('setting.site-settings.destroy', $siteSetting->id) }}" data-action="delete"
                data-table-id="sitesetting-table" data-name="Site Setting" class="btn btn-danger">
                <i class="fas fa-trash fs-4"></i>
            </button>
        @endcan
    @endif

</div>
