<div class="d-flex justify-content-center align-items-center gap-2">
    @if (request()->routeIs('cms.timeline.histori'))
        @can($globalModule['update'])
            <button data-action="restore" data-url="{{ route('cms.timeline.restore', $timeline->id) }}"
                data-table-id="timeline-table" data-name="Timeline" class="btn btn-success"><i
                    class="fas fa-recycle fs-3"></i></button>
        @endcan
    @else
        @can($globalModule['update'])
            <button data-action="edit" data-url="{{ route('cms.timeline.edit', $timeline->id) }}"
                data-target="#edit-timeline_modal" class="btn btn-warning">
                <i class="fas fa-pen fs-3"></i>
            </button>
        @endcan
        @can($globalModule['delete'])
            <button data-url="{{ route('cms.timeline.destroy', $timeline->id) }}" data-action="delete"
                data-table-id="timeline-table" class="btn btn-danger"
                data-name="{{ $timeline->tahun_ajaran->tahun_ajaran }}" {{ $timeline->tahun_ajaran->is_current == '1' ? 'disabled' : '' }}>
                <i class="fas fa-trash fs-3"></i>
            </button>
        @endcan
    @endif
</div>
