@props([
    'title' => '',
    'links' => [
        [
            'name' => '',
            'route' => '',
            'active' => false,
            'has_permission' => true
        ]
    ]
])

<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-15">
    <div class="d-flex align-items-center gap-8">
        <div class="icon text-title text-23">
            <i class="ri-terminal-line"></i>
        </div>
        <h6 class="card-title text-18">{{$title}}</h6>
    </div>
    <!-- Sub Menu -->
    <div class="sub-menu-wrapper">
        <ul class="sub-menu-list">
            @foreach($links ?? [] as $link)
                @continue(isset($link['has_permission']) && ! $link['has_permission'])

                <li class="sub-menu-item">
                    <a href="{{$link['route']}}" class="single {{$link['active'] ? 'active' : ''}}">{{$link['name']}}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- / Sub Menu -->
</div>
