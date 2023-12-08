@extends('layouts.app')

@section('content')
    @section('breadcrumbs')
        <li> 
            &nbsp; / <a href="{{ route('events.show', $event->id) }}">{{$event->name}}</a>
        </li>
        <li> &nbsp; / Authenticate </li>
    @endsection

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Authenticate QR Code</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center">
                            <div id="reader"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-4">SCAN RESULT</h4>
                        <div id="result" class="alert alert-info"></div>
                        <form id="qrForm" action="" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="ticket_id" class="form-label">Ticket ID</label>
                                <input type="text" class="form-control" id="ticket_id" name="ticket_id" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">User ID</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" readonly>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" id="submitForm">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para ler o QR Code -->
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset ('js/event/ticket-auth.js') }}"></script>

@endsection
