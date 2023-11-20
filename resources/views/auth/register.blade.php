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
                        <form method="POST" action="{{route('register') }}" enctype="multipart/form-data">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Step 1: provide name, birthdate and university -->
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
                                               value="{{ old('phone') }}" oninput="validatePhoneInput(this)" required>

                                        @error('phone')
                                        <span class="invalid-feedback" role="alert" id="error-message-phone">
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

                                        <span class="invalid-feedback" role="alert" id="error-message-birthdate"></span>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary next-step">Next Step</button>
                            </div>

                            <!-- Step 2: credentials -> email and password -->
                            <div id="step-2" class="form-step">
                                <div class="row mb-3">
                                    <label for="email"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" required autocomplete="email">

                                        <span class="invalid-feedback" role="alert" id="error-message-email"></span>
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

                                        <span class="invalid-feedback" role="alert" id="error-message-password"></span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password-confirm"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required autocomplete="new-password">
                                        <span class="invalid-feedback" role="alert"
                                              id="error-message-password-confirm"></span>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-secondary prev-step">Last Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step</button>
                            </div>

                            <!-- Step 3: insert profile image -->
                            <div id="step-3" class="form-step">
                                <div class="row mb-3">
                                    <label for="image_url">User Image:</label>
                                    <input type="file" class="form-control image-input" id="image_url" name="image_url"
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
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex align-items-center justify-content-center">
                                                <div class="img-container d-flex justify-content-center">
                                                    <img id="image" src="#" style="width: 100%; height: 100%;"
                                                         alt="img">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
                    if (validateStep(currentStep)) {
                        document.getElementById('step-' + currentStep).style.display = 'none';
                        currentStep++;
                        document.getElementById('step-' + currentStep).style.display = 'block';
                    }
                });
            });

            document.querySelectorAll('.prev-step').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.getElementById('step-' + currentStep).style.display = 'none';
                    currentStep--;
                    document.getElementById('step-' + currentStep).style.display = 'block';
                });
            });

            function validateStep(step) {
                let valid = true;
                if (step === 1) {
                    valid = validateStep1();
                } else if (step === 2) {
                    valid = validateStep2();
                }
                return valid;
            }

            function validateStep1() {
                let valid = true;
                let name = document.getElementById('name').value;
                let universityId = document.getElementById('university_id').value;
                let phone = document.getElementById('phone').value;
                let birthdate = document.getElementById('birthdate').value;

                if (!name || !universityId || !phone || !birthdate) {
                    alert('Please fill in all fields in Step 1');
                    valid = false;
                } else {
                    let currentDate = new Date();
                    let inputDate = new Date(birthdate);

                    if (inputDate > currentDate) {
                        displayError('Birthdate cannot be greater than the current date', 'birthdate');
                        valid = false;
                    } else {
                        clearError('birthdate');
                    }
                }

                return valid;
            }


            function validateStep2() {
                let valid = true;

                let email = document.getElementById('email').value;
                let password = document.getElementById('password').value;
                let confirmPassword = document.getElementById('password-confirm').value;

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!email || !emailRegex.test(email)) {
                    displayError('Invalid email address', 'email');
                    valid = false;
                } else {
                    clearError('email');
                }

                if (!password || password.length < 8) {
                    displayError('Password must be at least 8 characters', 'password');
                    valid = false;
                } else {
                    clearError('password');
                }

                if (password !== confirmPassword) {
                    displayError('Passwords do not match', 'password-confirm');
                    valid = false;
                } else {
                    clearError('password-confirm');
                }

                return valid;
            }

            function displayError(message, input) {
                let errorMessageElement = $("#error-message-" + input);
                errorMessageElement.html(`<strong>${message}</strong>`);
                errorMessageElement.css('display', 'inherit');
                document.getElementById(input).classList.add('is-invalid');
            }

            function clearError(input) {
                let errorMessageElement = $("#error-message-" + input);
                errorMessageElement.html('');
                errorMessageElement.css('display', 'none');
                document.getElementById(input).classList.remove('is-invalid')
            }
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
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                minContainerWidth: image.width < 200 && image.height < 200 ? image.width * 2 : image.width,
                minContainerHeight: image.width < 200 && image.height < 200 ? image.height * 2 : image.height,
            });
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

        document.querySelector('form[action="{{ route("register") }}"]').addEventListener('submit', function (e) {
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
                                treatError(response)
                                return
                            } else if (response.redirected) {
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

        function validatePhoneInput(inputField) {
            inputField.value = inputField.value.replace(/[^0-9+ ]/g, '');
            const errorMessageBox = $("#error-message-phone")

            if (/[^0-9+ ]/.test(inputField.value)) {
                errorMessageBox.innerHTML = '<strong>Invalid input. Please enter only numbers, +, or space.</strong>';
                errorMessageBox.css('display', 'inherit')
            } else {
                errorMessageBox.innerHTML = '';
                errorMessageBox.css('display', 'none')
            }
        }


    </script>

    <style>
        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }
    </style>
@endsection
