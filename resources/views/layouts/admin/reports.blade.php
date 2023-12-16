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
                            @forelse ($events as $event )
                                <tr>
                                    <td> <a href="{{ route('events.show', $event->id) }}" > {{ $event->name }} </a>  </td>
                                    <td> {{ $event->event_report_count }} </td>
                                <td>
                                    <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#reportModal" onClick="getEventReportsView('{{ $event->id }}','{{ $event->name}}', 'event')">    
                                        <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#eye-fill') }}"></use></svg>
                                        View
                                    </button>

                                    <button type="button" class="btn btn-success" onclick="check_all_event('{{ $event->id }}' )">
                                        <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#check-square-fill') }}"></use></svg>
                                        Check
                                    </button>

                                    <button type="button" class="btn btn-danger" onClick="banEvent( '{{ $event->id }}' )">
                                        <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#ban-fill') }}"></use></svg>
                                        Ban
                                    </button>   
                                </td>
                                </tr>
                            @empty 
                                <tr>
                                </tr>
                            @endforelse

                    
                    </tbody>
                 </table>
                </div>
                         <div class="col-md-6">
                 <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">User</th>
                        <th scope="col">Reports</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse ($comments as $comment )
                        <tr>
                            <td> <a href="{{ route('profile.show', $comment->user->id) }}" > {{ $comment->user->name }} </a> </td>
                            <td> {{ $comment->comment_report_count }} </td>
                            <td>
                            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#reportModal" onClick="getEventReportsView('{{ $comment->user->id }}','{{ $comment->user->name }}', 'comment')">    
                                <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#eye-fill') }}"></use></svg>
                                View
                            </button>

                            <button type="button" class="btn btn-success" onclick="check_all_comment('{{ $comment->user->id }}' )">
                                <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#check-square-fill') }}"></use></svg>
                                Check
                            </button>

                            <button type="button" class="btn btn-danger" onClick="banComment( '{{ $comment->id }}' )">
                                <svg class="bi" width="16" height="16"><use xlink:href="{{ asset('assets/svg/icons.svg#ban-fill') }}"></use></svg>
                                Ban
                            </button>   
                            </td>
                        </tr>
                    @empty
                        <tr>
                        </tr>
                    @endforelse
                    </tbody>
                 </table>
                </div>
            </div>
    </div>


<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel"></h5>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close" onclick="window.location=route_reports">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="container mt-3">
                <div class="input-group">
                    <select class="form-control form-control-sm" id="reportReason" name="reportReason">
                        <option value="All">All</option>
                        <option value="Inappropriate content">Inappropriate content</option>
                        <option value="Incorrect Information">Incorrect Information</option>
                        <option value="Inappropriate Behavior at the Event">Inappropriate Behavior at the Event</option>
                        <option value="Safety Conditions">Safety Conditions</option>
                        <option value="Fraud or Suspicious Activity">Fraud or Suspicious Activity</option>
                        <option value="Spam or Repetitive Content">Spam or Repetitive Content</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Reason</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <!-- Insert JS -->
                    </tr>
                </tbody>
            </table>
                <nav aria-label="..." >
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Insert JS -->
                    </ul>
                </nav>
        </div>
    </div>
</div>
    

    <script>
        var route_reports = "{{ route('admin.reports') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/reports/report_event.js') }}"></script>
@endsection