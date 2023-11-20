<php? @extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="container">
        <h1>Notifications</h1>

        <div id="notificacoesContainer" class="hidden">
            <ul id="notificacoesLista">
                @forelse($eventNotifications as $notification)
                     <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title h4">{{$notification['eventName']}}</h3>
                            <p class="card-text text-muted mb-4">{{ $notification['eventNotification']->notification_text}}</p>
                            <a href="{{ route('events.show', $notification['eventNotification']->event_id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> View Event</a>

                                <form action="{{ route('notificationscontroller.markRead', $notification['eventNotification']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')   
                                    <button type="submit" class="btn btn-danger mt-3">Mark Read</button>
                                </form>

                        </div>
                    </div>
                @empty
                    <li>Sem notificações.</li>
                @endforelse
            </ul>
        </div>
    </div>

@endsection
