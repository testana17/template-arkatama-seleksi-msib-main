<div class="d-flex justify-content-center align-items-center gap-2">
    @can($globalModule['update'])
        <a href="{{ route('setting.menus.edit', $menus->id) }}" title="" class="btn btn-warning">
            <i class="fas fa-pen fs-4"></i>
        </a>
    @endcan
    @can($globalModule['delete'])
        <button class="btn btn-danger" data-url="{{ route('setting.menus.destroy', $menus->id) }}" data-name="Menu"
            data-action="delete" data-table-id="menus-table">
            <i class="fas fa-trash fs-4"></i>
        </button>
    @endcan
</div>
