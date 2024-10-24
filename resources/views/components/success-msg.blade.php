@props([
    'link' => null,
])

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }} @if($link) To view the details <a class="text-primary fw-bold" href="{{route('admin.application.list')}}">click here</a> @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
