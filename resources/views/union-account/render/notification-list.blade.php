@auth('union_account')
    @php
        $notifications = \App\Models\Notification::whereDate('created_at', '>=', now()->subDays(2))->latest()->get();
        $unreadNotifications = $notifications->whereNull('read_at')->count();
    @endphp
    <a href="javascript:void(0)" class="cart-items dropdown-toggle toggle-arro-hidden"
       data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-notification-2-line p-0"></i>

        @if($unreadNotifications > 0)
            <span class="count">{{$unreadNotifications}}</span>
        @endif
    </a>

    <div class="dropdown-list-style dropdown-menu dropdown-menu-end">
        <div class="notification-header d-flex justify-content-between align-items-center mb-10">
            <h6>Notifications</h6>
            <button class="clear-notification">clear</button>
        </div>
        <ul class="notification-listing scroll-active p-0">
            @forelse($notifications ?? [] as $notification)
                <li class="list mb-6 {{! $notification->read_at ? 'unread-message' : ''}}">
                    <a class="list-items custom-break-spaces dropdown-item"
                       href="{{$notification->link}}">
                        <i class="ri-notification-3-line"></i>
                        <p class="line-clamp-2">{{$notification->message}}</p>
                        <span>
                                            <small>{{$notification->created_at->diffForHumans()}}</small>
                                        </span>
                    </a>
                </li>
            @empty
                <li class="list">
                    <a class="dropdown-item my-4" href="javascript:void(0)">
                        <p class="line-clamp text-center">No notification found</p>
                    </a>
                </li>
            @endforelse
        </ul>

        @if($notifications->count() > 10)
            <a href="{{route('union.notification.all')}}" class="see-all-notification border-0">see all
                notification</a>
        @endif
    </div>
@endauth
