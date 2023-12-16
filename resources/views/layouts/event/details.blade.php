@extends('layouts.app')




@section('content')
    @section('breadcrumbs')
        <li> &nbsp; / {{ $event->name }} </li>
    @endsection

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm" style="transition: transform .2s;">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            @if($event->image_url)
                                <img src="{{asset('storage/' . $event->image_url) }}" class="card-img" alt="Event Image"
                                     style="height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-8">

                            <div class="card-body">
                                <button type="button" class="btn btn-outline-danger position-absolute top-0 end-0 m-3" data-toggle="modal" data-target="#reportModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flag-fill" viewBox="0 0 16 16">
                                    <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001"/>
                                    </svg> Report
                                </button>
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text">{{ $event->description }}</p>
                                <p class="card-text"><small class="text-muted">Tickets
                                        Available: {{ $event->current_tickets_qty }}</small></p>
                                <p class="card-text"><small class="text-muted">Price:
                                        ${{ number_format($event->current_price, 2) }}</small></p>

                                @if(auth()->check())
                                    @php
                                        $userTicket = $event->ticket->firstWhere('user_id', auth()->id());
                                        $user = auth()->user();
                                        $isOwner = $user->id == $event->owner->user_id;
                                        $isAdmin = $user->isAdmin();
                                        $isOwnerOrAdmin = $isOwner || $isAdmin;
                                    @endphp
                                
                                    @if(auth()->check() && $event->current_tickets_qty > 0 && !$event->ticket->contains('user_id', auth()->id())&& !$isOwnerOrAdmin)
                                        <a href="{{ route('ticket.buy', ['event' => $event->id]) }}"
                                           class="btn btn-success">Buy Ticket</a>
                                    @endif
                                    @if($userTicket || $isOwnerOrAdmin)
                                        <a href="{{ route('discussion.show', ['event' => $event->id]) }}"
                                           class="btn btn-primary m-1">Access Discussion</a>
                                        @if($userTicket && !$isOwnerOrAdmin)
                                            <a href="{{ route('ticket.show', ['event' => $event->id, 'ticket' => $userTicket->id]) }}"
                                               class="btn btn-info m-1">Access Ticket</a>

                                            <a href="{{ route('events.leave', ['event_id' => $event->id, 'ticket_id' => $userTicket->id]) }}"
                                               class="btn btn-info m-1">Leave Event</a>
                                        @endif
                                    @endif
                                    @if($isOwnerOrAdmin)
                                        <a href="{{ route('ticket.authorize', $event->id) }}"
                                           class="btn btn-warning m-1">Authenticate Ticket</a>
                                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-secondary m-1">Edit
                                            Event</a>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger m-1"
                                                    onclick="return confirm('Are you sure you want to cancel this event? This action cannot be undone.')">
                                                Cancel Event
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Report {{ $event->name }} </h5>
      </div>
      <div class="modal-body">
        <form action="{{ route('event-report.store', [$event->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
          <div class="form-group">
            <label for="reportReason"> Reason </label>
              <select class="form-control" id="reportReason" name="reportReason">
                <option value="Inappropriate content">Inappropriate content</option>
                <option value="Incorrect Information">Incorrect Information</option>
                <option value="Inappropriate Behavior at the Event">Inappropriate Behavior at the Event</option>
                <option value="Safety Conditions">Safety Conditions</option>
                <option value="Fraud or Suspicious Activity">Fraud or Suspicious Activity</option>
                <option value="Spam or Repetitive Content">Spam or Repetitive Content</option>
                <option value="Others">Others</option>
            </select>
            <label for="reportDescription"> Description </label>
                <textarea name="reportDescription" class="form-control" id="reportDescription" placeholder="Provide more details"></textarea>

          </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>

    <script type="text/javascript" src="{{ URL::asset ('js/event/details-event.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
