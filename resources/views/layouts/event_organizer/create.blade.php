@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('/assets/css/create-event.css') }}">

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 40vh;">
        <div class="card w-100 p-3" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">
            <div class="card-body">
                <h5 class="text-muted mb-3">
                    To create an event, please provide your legal identifier and connect your Stripe account.
                </h5>

                <form id="connectStripeForm" method="get" action="{{ route('payment.connect') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="custom-label" for="legal_id">Legal Identifier:</label>
                        <input type="text" class="form-control custom-input" id="legal_id" name="legal_id"
                               placeholder="Enter your legal identifier...">
                    </div>

                    <button type="submit" class="btn btn-primary next-step w-100 custom-button mt-3 mb-3"
                            id="connectStripeBtn" disabled>
                        Connect to your Stripe Account
                    </button>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="{{ URL::asset('js/event/event-organizer.js') }}"></script>
    </div>
@endsection
