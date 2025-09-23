<!-- Navbar -->
@php
// function untuk mendapatkan url sesuai dengan role
if (!function_exists('getRoleUrl')) {
function getRoleUrl()
{
try {
$role = auth()->user()->role_id ?? null;
switch ($role) {
case 1: return 'admin';
case 2: return 'dospem';
case 3: return 'mahasiswa';
default: return '';
}
} catch (Exception $e) {
return '';
}
}
}

// function untuk mendapatkan username
if (!function_exists('getuserName')) {
function getuserName()
{
try {
if (!auth()->check()) {
return 'Guest';
}

$role = auth()->user()->role_id ?? null;
switch ($role) {
case 1:
return auth()->user()->admin?->nama_admin ?? auth()->user()->username;
case 2:
return auth()->user()->dosen?->nama ?? auth()->user()->username;
case 3:
return auth()->user()->mahasiswa?->nama ?? auth()->user()->username;
default:
return auth()->user()->username ?? 'User';
}
} catch (Exception $e) {
return 'User';
}
}
}

// function untuk mendapatkan id dari masing" role
if (!function_exists('getIdUser')) {
function getIdUser()
{
try {
if (!auth()->check()) {
return '';
}

$role = auth()->user()->role_id ?? null;
switch ($role) {
case 1:
return auth()->user()->admin?->id_admin ?? '';
case 2:
return auth()->user()->dosen?->id_dospem ?? '';
case 3:
return auth()->user()->mahasiswa?->id_mahasiswa ?? '';
default:
return '';
}
} catch (Exception $e) {
return '';
}
}
}

// function untuk mendapatkan foto profile user
if (!function_exists('getProfilePhoto')) {
function getProfilePhoto()
{
try {
if (!auth()->check()) {
return asset('image/fotoDefault.jpg');
}

return auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('image/fotoDefault.jpg');
} catch (Exception $e) {
return asset('image/fotoDefault.jpg');
}
}
}
@endphp

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl mt-3 mx-3 bg-primary"
    id="navbarBlur" data-scroll="false">
    <div class="container-fluid justify-content-between py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                @isset($breadcrumb->list)
                @foreach ($breadcrumb->list as $key => $value)
                @if ($key == count($breadcrumb->list) - 1)
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">
                    {{ $value ?? 'Unknown Page' }}
                </li>
                @else
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-white" href="#">{{ $value ?? '' }}</a>
                </li>
                @endif
                @endforeach
                @else
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">
                    Dashboard
                </li>
                @endisset
            </ol>
            <h6 class="font-weight-bolder text-white mb-0">
                @isset($breadcrumb->list)
                {{ end($breadcrumb->list) ?? 'Dashboard' }}
                @else
                Dashboard
                @endisset
            </h6>
        </nav>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-3 me-sm-4 flex-grow-0" id="navbar">
            <ul class="navbar-nav align-items-center justify-content-end gap-3">
                @auth
                <div class="dropdown" style="cursor: pointer;">
                    <div class="text-white dropdown-toggle mb-0" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="{{ getProfilePhoto() }}" class="rounded-circle me-2" id="mini-profile"
                            style="object-fit: cover" width="40" height="40" alt="User Image">
                        {{ getuserName() }}
                    </div>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item" href="{{ url(getRoleUrl() . '/' . getIdUser() . '/profile') }}">
                                <i class="ni ni-single-02 text-warning text-sm opacity-10 me-2"></i>Profile
                            </a>
                        </li>
                        @if (auth()->user()->role_id == 3)
                        <li>
                            <a class="dropdown-item"
                                href="{{ url(getRoleUrl() . '/' . getIdUser() . '/prestasi') }}">
                                <i class="ni ni-trophy text-warning text-sm opacity-10 me-2"></i>Prestasi
                            </a>
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="/logout">
                                <i class="ni ni-user-run text-warning text-sm opacity-10 me-2"></i>Log out
                            </a>
                        </li>
                    </ul>
                </div>
                @else
                <div class="text-white mb-0">
                    <a href="/login" class="text-white text-decoration-none">
                        <i class="ni ni-single-02 me-1"></i> Login
                    </a>
                </div>
                @endauth

                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>

                @auth
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp

                    <a href="javascript:;" class="nav-link text-white p-0 position-relative" id="notifDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                        @if ($unreadCount > 0)
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-3 py-3 me-sm-n4 w-auto shadow"
                        style="background-color: #fff; color: #212529; width: 100%; min-width: 400px; max-width: 460px; max-height: 400px; overflow-y: auto; border-radius: 12px;"
                        aria-labelledby="notifDropdown">
                        <li>
                            <h6 class="font-weight-bold" style="color: #212529!important;">Notifikasi Terbaru</h6>
                        </li>
                        @forelse ($navbarNotifications as $notif)
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md"
                                href="{{ route('notifikasi.baca', $notif->id) }}">
                                <div class="d-flex py-1">
                                    <div class="">
                                        <img src="{{ getProfilePhoto() }}"
                                            class="avatar avatar-sm me-3 rounded-circle" style="object-fit: cover"
                                            onerror="this.onerror=null;this.src='{{ asset('image/fotoDefault.jpg') }}';"
                                            alt="Profile Photo">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center" style="min-width: 0;">
                                        <h6 class="text-sm font-weight-normal mb-1 text-break">
                                            <i class="fa fa-bell text-warning me-1"></i>
                                            <span class="font-weight-bold" style="color: #212529;">
                                                {{ $notif->data['title'] ?? 'Notifikasi' }}
                                            </span>
                                        </h6>
                                        <p class="text-xs text-dark mb-0 text-break"
                                            style="white-space: normal; width: 100%;">
                                            {{ $notif->data['pesan'] ?? '' }}
                                        </p>
                                        <p class="text-xs text-secondary mb-0 mt-2">
                                            <i class="fa fa-clock me-1"></i>
                                            {{ $notif->created_at->locale('id')->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @empty
                        <li class="text-center text-secondary text-sm px-3 py-2">Tidak ada notifikasi baru</li>
                        @endforelse
                    </ul>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->