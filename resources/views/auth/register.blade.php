@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/register.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

@section('content')
    <div class="container" style="height: 100vh;">
        <div class="row justify-content-center h-100">
            <div class="col-md-6 d-flex flex-column justify-content-center h-100">
                <img src="{{ URL::asset('/assets/img/logo-without-text.svg') }}" alt="Logo Tech Crafted" width="400"/>
                <img class="mt-4" src="{{ URL::asset('/assets/img/logo-without-image.svg') }}" alt="Text Tech Crafted"
                     width="400"/>
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center h-100">
                <h2 style="color: #0E4A67;font-weight: bolder;">Create Your Account</h2>
                <h6 style="color: #0E4A67">Already have an account? <a href="/login" style="color: #5826D7">Login</a>
                </h6>

                <form method="POST" class="mt-3" action="{{route('register') }}" id="register-form"
                      enctype="multipart/form-data">
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
                        <div class="form-row mb-3">
                            <label class="custom-label" for="name">{{ __('Name') }}</label>
                            <input id="name" type="text" placeholder="Your name..."
                                   class="form-control custom-input @error('name') is-invalid @enderror" name="name"
                                   value="{{ old('name') }}" required autocomplete="name" autofocus>
                            <span class="invalid-feedback" role="alert"></span>
                        </div>

                        <div class="form-row mb-3">
                            <label class="custom-label" for="university_id">Your university</label>
                            <select id="university_id" name="university_id" class="form-control custom-input">
                                <option value="">Choose university</option>
                                @foreach ($universities as $university)
                                    <option value="{{ $university->id }}">{{ $university->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-row mb-3">
                            <label class="custom-label w-100" for="phone">{{ __('Phone') }}</label>
                            <input id="phone" type="text"
                                   class="form-control custom-input @error('phone') is-invalid @enderror" name="phone"
                                   value="{{ old('phone') }}" oninput="validatePhoneInput(this)" required>
                            <span class="invalid-feedback" role="alert" id="error-message-phone"></span>
                        </div>

                        <div class="form-row mb-3">
                            <label class="custom-label" for="birthdate">{{ __('Birthdate') }}</label>
                            <input id="birthdate" type="date"
                                   class="form-control custom-input @error('birthdate') is-invalid @enderror"
                                   name="birthdate" value="{{ old('birthdate') }}" required>
                            <span class="invalid-feedback" role="alert" id="error-message-birthdate"></span>
                        </div>

                        <button type="button" class="btn btn-primary next-step w-100 custom-button mt-3">Next Step
                            <svg width="23" height="23" viewBox="0 0 16 16" fill="none" class="ms-1"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1675_1807)">
                                    <path d="M10.7814 7.33312L7.20541 3.75712L8.14808 2.81445L13.3334 7.99979L8.14808 13.1851L7.20541 12.2425L10.7814 8.66645H2.66675V7.33312H10.7814Z"
                                          fill="white"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1675_1807">
                                        <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>

                    <!-- Step 2: credentials -> email and password -->
                    <div id="step-2" class="form-step">
                        <div class="form-row mb-3">
                            <label class="custom-label" for="email">{{ __('Email Address') }}</label>

                            <input id="email" type="email"
                                   class="form-control custom-input @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}" required autocomplete="email">
                            <span class="invalid-feedback" role="alert" id="error-message-email"></span>
                        </div>


                        <div class="form-row mb-3">
                            <label class="custom-label" for="password">{{ __('Password') }}</label>
                            <input id="password" type="password"
                                   class="form-control custom-input @error('password') is-invalid @enderror"
                                   name="password"
                                   required autocomplete="new-password">
                            <span class="invalid-feedback" role="alert" id="error-message-password"></span>
                        </div>

                        <div class="form-row mb-3">
                            <label class="custom-label" for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control custom-input"
                                   name="password_confirmation" required autocomplete="new-password">
                            <span class="invalid-feedback" role="alert" id="error-message-password-confirm"></span>
                        </div>

                        <button type="button" class="btn btn-primary next-step w-100 custom-button mt-3 mb-3">Next Step
                            <svg width="23" height="23" viewBox="0 0 16 16" fill="none" class="ms-1"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1675_1807)">
                                    <path d="M10.7814 7.33312L7.20541 3.75712L8.14808 2.81445L13.3334 7.99979L8.14808 13.1851L7.20541 12.2425L10.7814 8.66645H2.66675V7.33312H10.7814Z"
                                          fill="white"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1675_1807">
                                        <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                        <button type="button" class="btn btn-secondary prev-step w-100">Last Step</button>
                    </div>

                    <!-- Step 3: insert profile image -->
                    <div id="step-3" class="form-step">
                        <div class="form-row mb-3">
                            <label class="custom-label" for="image_url">User Image:</label>
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


                        <div class="w-100 d-flex align-items-center justify-content-center">
                            <img id="preview" class="w-50 rounded-circle">
                        </div>
                        <button type="submit"
                                class="btn btn-primary next-step w-100 custom-button mt-3 mb-3">{{ __('Register') }}
                        </button>
                        <button type="button" class="btn btn-secondary prev-step w-100">Last Step</button>

                    </div>

                </form>

            </div>
        </div>

        <script type="text/javascript" src="{{ URL::asset ('js/auth/register.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

@endsection
