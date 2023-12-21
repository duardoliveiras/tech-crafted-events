@extends('layouts.app')

@section('content')
@section('breadcrumbs')
<li>
    &nbsp; / <a href="{{ route('profile.show', $user->id) }}">{{$user->name}}</a>
</li>
<li>
    &nbsp; / Edit
</li>
@endsection
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Edit user profile</h4>
                </div>
                <div class="card-body">
                    {{-- Eventually erros --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Edition forms --}}
                    <form action="{{ route('profile.update', $user->id) }}" method="POST" id="update-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if($user->provider == null)
                        <div class="form-row mb-3">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>

                        <div class="form-row mb-3">
                            <label for="email">E-mail:</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        @endif

                        <div class="form-row mb-3">
                            <label for="birthdate">Birth date:</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ $user->birthdate }}" required>
                        </div>

                        <div class="form-row mb-3">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
                        </div>

                        @if($user->provider == null)
                        <div class="form-row mb-3">

                            <label class="custom-label" for="image_url">User Image:</label>
                            <div class="w-100 d-flex align-content-center justify-content-center">
                                <img class="rounded-circle shadow-1-strong mb-2" style="max-width: 200px; max-height: 200px;" id="preview" src="{{ $user->image_url ? asset('storage/' . $user->image_url) : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}" alt="avatar" />
                            </div>
                            <input type="file" accept="image/jpeg, image/png, image/jpg, image/gif, image/svg+xml" class="form-control custom-input image-input" id="image_url" name="image_url" required>
                        </div>

                        <div class="modal fade" id="cropImageModal" tabindex="-1" role="dialog" aria-labelledby="cropImageModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cropImageModalLabel">Crop image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                                    </div>
                                    <div class="modal-body d-flex align-items-center justify-content-center">
                                        <div class="img-container d-flex justify-content-center">
                                            <img id="image" src="#" style="width: 100%; height: 100%;" alt="img">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">
                                            Close
                                        </button>
                                        <button type="button" class="btn btn-primary" id="crop">Crop</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-success">Save editions</button>

                    </form>
                    @isset($mensagemSucesso)
                    <div class="alert alert-success">
                        {{ $mensagemSucesso }}
                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    <script type="text/javascript" src="{{ URL::asset ('js/auth/edit-user.js') }}"></script>

</div>
@endsection
