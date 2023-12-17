@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method=" POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end" for="university_id">Your university</label>
                            <div class="col-md-6">
                                <select id="university_id" name="university_id" class="form-control custom-input">
                                    <option value="">Choose university</option>
                                    @foreach ($universities as $university)
                                    <option value="{{ $university->id }}">{{ $university->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end" for="phone">{{ __('Phone') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control custom-input @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" oninput="validatePhoneInput(this)" required>
                                <span class="invalid-feedback" role="alert" id="error-message-phone"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end" for="birthdate">{{ __('Birthdate') }}</label>
                            <div class="col-md-6">
                                <input id="birthdate" type="date" class="form-control custom-input @error('birthdate') is-invalid @enderror" name="birthdate" required>
                                <span class="invalid-feedback" role="alert" id="error-message-birthdate"></span>
                            </div>

                        </div>
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
