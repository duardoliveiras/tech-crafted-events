@forelse($events as $event)
    <div class="col-md-4">
        <div class="card mb-4 shadow card-hover-effect" style="border-width: 0;">
            <a href="/events/{{ $event->id }}" class="text-decoration-none text-reset">
                <div style="position: absolute; top: 10px; left: 20px; background: white; color: #7848F4; padding: 8px; border-radius: 10px;">
                    @if ($event->current_price == 0)
                        FREE
                    @else
                        € {{ number_format($event->current_price, 2) }}
                    @endif
                </div>
                <img src="{{asset('event/' . $event->image_url)}}"
                     class="card-img-top" alt="{{ $event->name }}"
                     style="width: 100%; height: 300px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $event->name }}</h5>
                    <p class="card-text mb-1" style="color: #7848F4;">
                        {{ \Carbon\Carbon::parse($event->start_date)->format('l, F j, Y, g:i A') }}
                    </p>
                    <p class="card-text mb-1" style="color: #7E7E7E;">{{ $event->address }}
                        , {{ $event->city->name }}</p>
                    <small style="color: #7E7E7E; font-size: .8em;"><i>Event created by user from &#174;
                            <u>{{ $event->owner->user->university->name }}</u></i></small>
                </div>
            </a>
        </div>
    </div>
    @if ($loop->iteration % 3 == 0)
        </div>
    <div class="row d-flex mt-3 justify-content-center">
        @endif
        @empty
            <div class="col-12 text-center">
                <p class="lead">No upcoming events found.</p>
            </div>
@endforelse
