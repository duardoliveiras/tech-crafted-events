@extends('layouts.app')

@section('content')
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
                        <form action="{{ route('profile.update', $user->id) }}" method="POST" id="update-form"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-row mb-3">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}"
                                       required>
                            </div>

                            <div class="form-row mb-3">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ $user->email }}" required>
                            </div>

                            <div class="form-row mb-3">
                                <label for="birthdate">Birth date:</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate"
                                       value="{{ $user->birthdate }}" required>
                            </div>

                            <div class="form-row mb-3">
                                <label for="phone_number">Phone number:</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                       value="{{ $user->phone }}" required>
                            </div>

                            <div class="form-row mb-3">

                                <label class="custom-label" for="image_url">User Image:</label>
                                <div class="w-100 d-flex align-content-center justify-content-center">
                                    <img class="rounded-circle shadow-1-strong mb-2" id="preview"
                                         src="{{ $user->image_url ? asset('storage/' . $user->image_url) : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}"
                                         alt="avatar"/>
                                </div>
                                <input type="file" class="form-control custom-input image-input" id="image_url"
                                       name="image_url"
                                       required>
                            </div>

                            <div class="modal fade" id="cropImageModal" tabindex="-1" role="dialog"
                                 aria-labelledby="cropImageModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cropImageModalLabel">Crop image</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close" onclick="closeModal()"></button>
                                        </div>
                                        <div class="modal-body d-flex align-items-center justify-content-center">
                                            <div class="img-container d-flex justify-content-center">
                                                <img id="image" src="#" style="width: 100%; height: 100%;"
                                                     alt="img">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                    onclick="closeModal()">
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary" id="crop">Crop</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
        <script>
            let modal = document.getElementById('cropImageModal');
            let image = document.getElementById('image');
            let cropper;

            document.body.addEventListener("change", function (e) {
                if (e.target.classList.contains("image-input")) {
                    let files = e.target.files;
                    let done = function (url) {
                        image.src = url;
                        modal.style.display = "block"
                        modal.classList.add('show');
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
                }
            });

            image.addEventListener('load', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                    minContainerWidth: image.width < 200 && image.height < 200 ? image.width * 2 : image.width,
                    minContainerHeight: image.width < 200 && image.height < 200 ? image.height * 2 : image.height,
                });
            }, false);

            function closeModal() {
                // document.getElementById("backdrop").style.display = "none"
                modal.style.display = "none"
                modal.classList.remove("show")

                cropper.destroy();
                cropper = null;
            }

            document.getElementById('crop').addEventListener('click', function () {
                let canvas = cropper.getCroppedCanvas({
                    width: 160,
                    height: 160,
                });

                canvas.toBlob(function (blob) {
                    let url = URL.createObjectURL(blob);
                    let preview = document.getElementById('preview');
                    preview.src = url;

                    modal.style.display = 'none';

                    cropper.destroy();
                    cropper = null;
                });
            });

            document.getElementById('update-form').addEventListener('submit', function (e) {
                console.log('entrou aqui')
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
                                if (!response.ok) {
                                    console.log('deu ruim')
                                    treatError(response)
                                    return
                                } else if (response.redirected) {
                                    console.log('deu bom com')
                                    console.log(response)
                                    window.location.href = response.url;
                                }
                                return response.json();
                            })
                    });
            });

            function treatError(response) {
                response.json().then(data => {
                    displayErrors(data.errors)
                })
            }

            function displayErrors(errors) {
                clearErrors();

                let alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger';

                let errorList = document.createElement('ul');

                Object.keys(errors).forEach(field => {
                    let input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        let errorContainer = document.createElement('span');
                        errorContainer.className = 'invalid-feedback';
                        errorContainer.setAttribute('role', 'alert');
                        errorContainer.style.display = 'inherit';
                        errorContainer.innerHTML = `<strong>${errors[field][0]}</strong>`;
                        input.parentNode.appendChild(errorContainer);

                        let errorItem = document.createElement('li');
                        errorItem.textContent = errors[field][0];
                        errorList.appendChild(errorItem);
                    }
                });

                alertDiv.appendChild(errorList);

                let form = document.querySelector('form');
                form.insertBefore(alertDiv, form.firstChild);
            }

            function clearErrors() {
                let errorMessages = document.querySelectorAll('.invalid-feedback');
                errorMessages.forEach(message => message.parentNode.removeChild(message));

                let inputs = document.querySelectorAll('.is-invalid');
                inputs.forEach(input => input.classList.remove('is-invalid'));

                let alertDiv = document.querySelector('.alert-danger');
                if (alertDiv) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    </div>
@endsection
