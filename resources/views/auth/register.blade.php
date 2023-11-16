@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{route('register') }}" enctype="multipart/form-data"
                              id="form-register">
                            @csrf

                            <!-- Step 1: Informar Nome e Data de Nascimento -->
                            <div id="step-1" class="form-step">
                                <div class="row mb-3">
                                    <label for="name"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror" name="name"
                                               value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="university_id" class="col-md-4 col-form-label text-md-end">Your
                                        university</label>
                                    <div class="col-md-6">
                                        <select id="university_id" name="university_id" class="form-control">
                                            <option value="">Choose university</option>
                                            @foreach ($universities as $university)
                                                <option value="{{ $university->id }}">{{ $university->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>

                                    <div class="col-md-6">
                                        <input id="phone" type="text"
                                               class="form-control @error('phone') is-invalid @enderror" name="phone"
                                               value="{{ old('phone') }}" required>

                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="birthdate"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Birthdate') }}</label>

                                    <div class="col-md-6">
                                        <input id="birthdate" type="date"
                                               class="form-control @error('birthdate') is-invalid @enderror"
                                               name="birthdate" value="{{ old('birthdate') }}" required>

                                        @error('birthdate')
                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary next-step">Next Step</button>
                            </div>

                            <!-- Step 2: Informar Email e Senha -->
                            <div id="step-2" class="form-step">
                                <div class="row mb-3">
                                    <label for="email"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password"
                                               required autocomplete="new-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password-confirm"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <button type="button" class="btn btn-secondary prev-step">Last Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step</button>
                            </div>

                            <!-- Step 3: Inserir Imagem do Perfil -->
                            <div id="step-3" class="form-step">
                                <div class="row mb-3">
                                    <label for="image_url">User Image:</label>
                                    <input type="file" class="form-control image-input" id="image_url" name="image_url"
                                           required>
                                </div>

                                <div class="modal fade" id="cropImageModal" tabindex="-1" role="dialog" aria-labelledby="cropImageModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="cropImageModalLabel">Crop image</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body d-flex align-items-center justify-content-center">
                                                <div class="img-container d-flex justify-content-center">
                                                    <img id="image" src="#" style="width: 100%; height: 100%;" alt="img">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="button" class="btn btn-primary" id="crop">Crop</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <img id="preview">

                                <button type="button" class="btn btn-secondary prev-step">Last Step</button>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-step:not(#step-1)').forEach(function (step) {
                step.style.display = 'none';
            });

            let currentStep = 1;

            document.querySelectorAll('.next-step').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.getElementById('step-' + currentStep).style.display = 'none';
                    currentStep++;
                    document.getElementById('step-' + currentStep).style.display = 'block';
                });
            });

            document.querySelectorAll('.prev-step').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.getElementById('step-' + currentStep).style.display = 'none';
                    currentStep--;
                    document.getElementById('step-' + currentStep).style.display = 'block';
                });
            });
        });

        let $modal = $('#cropImageModal');
        let image = document.getElementById('image');
        let cropper;

        $("body").on("change", ".image-input", function (e) {
            let files = e.target.files;
            let done = function (url) {
                image.src = url;
                $modal.modal('show');
            };
            let reader;
            let file;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        image.addEventListener('load', function () {
            console.log(image)
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                minContainerWidth: image.width < 200 && image.height < 200 ? image.width*2 : image.width,
                minContainerHeight: image.width < 200 && image.height < 200 ? image.height*2 : image.height,
            });
            console.log(image.height)
            console.log(image.width)
        }, false);

        $modal.on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        $("#crop").click(function () {
            let canvas = cropper.getCroppedCanvas({
                width: 160,
                height: 160,
            });

            canvas.toBlob(function (blob) {
                url = URL.createObjectURL(blob);
                let preview = document.getElementById('preview');
                preview.src = url;
                $modal.modal('hide');
            });
        })

        document.getElementById('form-register').addEventListener('submit', function (e) {
            e.preventDefault();

            let form = e.target;
            let formData = new FormData(form);

            let imageSrc = document.getElementById('preview').src;
            fetch(imageSrc)
                .then(res => res.blob())
                .then(blob => formData.append('image_url', blob, 'user_image.png'))
                .then(() => {
                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => {
                            if (response.redirected) {
                                window.location.href = response.url;
                            } else {
                                return response.json();
                            }
                        })
                })
        });
    </script>

    <style>
        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>
@endsection
