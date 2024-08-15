@extends('layouts.app')

@section('content')
  <div class="app-container">
    @include('pages.admin.user.user-list.partials.modals')
    <div class="py-4">
      <div class="d-flex flex-md-row flex-column gap-4 mb-4 align-items-center">
        <div class="search-box">
          <label class="position-absolute " for="searchBox">
            <i class="fal fa-search fs-3"></i>
          </label>
          <input type="text" data-table-id="userlist-table" id="searchBox" data-action="search"
            class="form-control form-control-solid w-250px ps-13" placeholder="Cari User" />
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#filter-user_modal">
          <i class="fas fa-filter me-2"></i>
          <span>Filter</span>
        </button>
      </div>
      <div class="table-relative">
        {{ $dataTable->table() }}
      </div>
    </div>
  </div>

  @push('scripts')
    {{ $dataTable->scripts() }}
    <script>
      $(function() {
        $(document).on("click", "button[data-action='reset']", function() {
          $.ajax({
            url: "/users/user-list/" + $(this).data("id") + "/edit",
            method: "GET",
            beforeSend: function() {
              showPageOverlay();
            },
            complete: function() {
              hidePageOverlay();
            },
            success: function(response) {
              fillForm($("#reset-user_modal form"), response)
              $("#reset-user_modal").find("form").attr("action", "/users/user-list/reset-password/" + response
                .id);
              $("#reset-user_modal").modal("show");
            },
            error: function(err) {
              handleAjaxError(err);
            },
          })
        })

        $("#filter-user_modal-form").on("submit", function(ev) {
          ev.preventDefault();
          window.LaravelDataTables["userlist-table"].ajax.reload();
          $("#filter-user_modal").modal("hide");
          toastr.success("Filter berhasil diterapkan");
        })


        $("#filter-user_modal button[type='reset']").on("click", function(ev) {
          ev.preventDefault();
          let form = $("#filter-user_modal-form");
          resetForm($("#filter-user_modal-form"));
          window.LaravelDataTables["userlist-table"].ajax.reload();
          $("#filter-user_modal").modal("hide");
        })

      })
    </script>
  @endpush
@endsection
