<li class="sidebar-menu-item {{activeCurrentSidebarMenu('dashboard')}}">
    <a href="{{route('dashboard')}}" class="parent-item-content">
        <i class="ri-dashboard-line"></i>
        <span class="on-half-expanded">Dashboard</span>
    </a>
</li>

<!-- Single Menu -->
<li class="sidebar-menu-item {{activeCurrentSidebarMenu(['user.registration', 'user.slip.registration'])}}">
    <a href="{{route('user.registration')}}" class="parent-item-content">
        <i class="ri-hand-heart-line"></i>
        <span class="on-half-expanded">Registration</span>
    </a>
</li>

<!-- Single Menu -->
<li class="sidebar-menu-item {{activeCurrentSidebarMenu(['user.application.list', 'user.slip.list'])}}">
    <a href="{{route('user.application.list')}}" class="parent-item-content">
        <i class="ri-file-list-2-line"></i>
        <span class="on-half-expanded">Application List</span>
    </a>
</li>

<li class="sidebar-menu-item {{activeCurrentSidebarMenu('user.transaction.history')}}">
    <a href="{{route('user.transaction.history')}}" class="parent-item-content">
        <i class="ri-list-indefinite"></i>
        <span class="on-half-expanded">Transaction History</span>
    </a>
</li>
