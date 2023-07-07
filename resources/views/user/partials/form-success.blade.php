@if (Session::has('success'))

<div class="alert alert-success"  role="alert">
    <button type="button" class="close hide-close" aria-label="Close">
    <span aria-hidden="true">×</span>
    </button>
    <p class="m-0">
        {{ Session::get('success') }}
    </p>
</div>

@endif
@if (Session::has('unsuccess'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <p class="m-0">
        {{ Session::get('unsuccess') }}
    </p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
