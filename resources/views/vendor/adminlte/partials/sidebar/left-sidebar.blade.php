<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

{{-- Sidebar brand logo --}}
<div class="brand-logo-container text-center">
    <a href="{{ url('/') }}" class="brand-link d-flex flex-column align-items-center">
        <img src="{{ asset('images/nha.png') }}" 
            alt="Logo" 
            class="brand-image"
            id="sidebar-logo"
            style="max-height: 100px; width: auto;">
        <span class="brand-text font-weight-bold mt-2 d-none d-md-block">NOTICE TRACKER</span>
    </a>
</div>


    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>

<style>
/* Enhanced Sidebar Base Design */
.main-sidebar {
    background: linear-gradient(135deg, #295F98 0%, #1E3A5F 100%);
    color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: width 0.3s ease-in-out;
}

/* Navigation Item Styling */
.nav-sidebar .nav-item {
    position: relative;
    margin: 8px 0;
    perspective: 1000px;
}

.nav-sidebar .nav-link {
    color: #ffffff !important;
    font-size: 15px;
    padding: 12px 15px;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
}

/* Hover Effect with Subtle Depth */
.nav-sidebar .nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateX(5px) rotateX(5deg);
    box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
}

/* Active Link Enhanced Design */
.nav-sidebar .nav-item > .nav-link.active {
    background: #4A8FD4 !important;
    color: #ffffff !important;
    font-weight: bold;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(74, 143, 212, 0.6);
    position: relative;
    overflow: visible;
}

/* Animated Active State Indicator */
.nav-sidebar .nav-item > .nav-link.active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 10%;
    width: 6px;
    height: 80%;
    background: linear-gradient(to bottom, #ffffff, #e0e0e0);
    border-radius: 5px;
    animation: pulse 1.5s infinite alternate;
}

/* Pulse Animation for Active Indicator */
@keyframes pulse {
    0% { opacity: 0.7; transform: scaleY(1); }
    100% { opacity: 1; transform: scaleY(1.05); }
}

/* Nested Treeview Styling */
.nav-treeview .nav-item .nav-link {
    padding-left: 40px;
    font-size: 14px;
    opacity: 0.9;
    transition: all 0.3s ease;
}

.nav-treeview .nav-item .nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    opacity: 1;
}

.nav-treeview .nav-item .nav-link.active {
    background: #5BA5E0 !important;
    border-radius: 6px;
    opacity: 1;
}

/* Logo and Branding Section */
.brand-logo-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    transition: all 0.3s ease-in-out;
    position: relative;
    overflow: hidden;
}

.brand-image {
    max-height: 100px;
    width: auto;
    transition: all 0.3s ease-in-out;
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
}

.brand-text {
    color: white;
    font-size: 20px;
    text-align: center;
    font-weight: bold;
    transition: all 0.3s ease-in-out;
    margin-top: 10px;
    letter-spacing: 1px;
}

/* Sidebar Collapse Animations */
.sidebar-collapse .brand-image {
    max-height: 50px;
    transform: scale(0.8);
}

.sidebar-collapse .brand-text {
    font-size: 0;
    opacity: 0;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .brand-image {
        max-height: 80px;
    }
    
    .nav-sidebar .nav-link {
        justify-content: center;
    }
}

/* Optional: Subtle Background Pattern */
.main-sidebar::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        linear-gradient(45deg, rgba(255,255,255,0.05) 25%, transparent 25%),
        linear-gradient(-45deg, rgba(255,255,255,0.05) 25%, transparent 25%);
    background-size: 30px 30px;
    opacity: 0.3;
    pointer-events: none;
}
</style>
