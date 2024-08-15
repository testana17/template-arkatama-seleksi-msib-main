<div class="d-flex justify-content-center align-items-center gap-2">
    @can($globalModule['update'])
        <button data-action="edit" title="edit" data-target="#edit-news_modal"
            data-url="{{ route('cms.news.edit', $news->id) }}" class="btn btn-warning ">
            <i class="fas fa-pen fs-4"></i>
        </button>
    @endcan
    @can($globalModule['delete'])
        <button data-url="{{ route('cms.news.destroy', $news->id) }}" data-action="delete" data-table-id="news-table"
            data-name="{{ $news->title }}" class="btn  btn-danger ">
            <i class="fas fa-trash fs-4 "></i>
        </button>
    @endcan
</div>
