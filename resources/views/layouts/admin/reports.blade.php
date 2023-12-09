@extends('layouts.app')


@section('content')

    <div class="container mt-4">
        <h2 class="mb-4">Reports</h2>
            
            <div class="row">
                <div class="col-md-6">
                 <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Event Name</th>
                        <th scope="col">Reports</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>

                        @foreach ($events as $event )
                            <td> {{ $event->name }} </td>
                            <td> {{ $event->event_report_count }} </td>
                        @endforeach
                        <td>
                        <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#reportModal" onClick="getEventReports('{{ $event->id }}')">    
                            <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#eye-fill') }}"></use></svg>
                            View
                        </button>

                        <button type="button" class="btn btn-success">
                            <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#check-square-fill') }}"></use></svg>
                            Check
                        </button>

                        <button type="button" class="btn btn-danger">
                            <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#ban-fill') }}"></use></svg>
                            Ban
                        </button>   
                        </td>
                    </tr>
                    </tbody>
                 </table>
                </div>
                         <div class="col-md-6">
                 <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Comment</th>
                        <th scope="col">Reports</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Mulher tem mesmo é que lavar louça</td>
                        <td>5.000</td>
                        <td>
                        <button type="button" class="btn btn-primary">    
                            <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#eye-fill') }}"></use></svg>
                            View
                        </button>

                        <button type="button" class="btn btn-success">
                            <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#check-square-fill') }}"></use></svg>
                            Check
                        </button>

                        <button type="button" class="btn btn-danger">
                            <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#ban-fill') }}"></use></svg>
                            Ban
                        </button>                       
                        </td>
                    </tr>
                    </tbody>
                 </table>
                </div>
            </div>
    </div>

<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Report {{ $event->name }} </h5>
      </div>
      <div class="modal-body" id="reportContainer">
       <!--  Js insert -->
      </div>
    </div>
  </div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/reports/report_event.js') }}"></script>
@endsection