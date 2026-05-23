<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="logo" style="width: 100%; text-align: center;">
            <img src="{{ asset('assets/images/logo/smaiba.png') }}" alt="Logo" style="max-width: 100%; height: 45px;">
        </div>
        <div class="toggler">
            <a href="#" class="sidebar-hide d-xl-none d-block">
                <i class="bi bi-x bi-middle"></i>
            </a>
        </div>
    </div>
</div>

        @if (auth()->user()->role === 'admin')
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu</li>

                    <li class="sidebar-item {{ Request::is('admin') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Request::is('admin/master') ? 'active' : '' }} ">
                        <a href="{{ route('admin.master.index') }}" class='sidebar-link'>
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Master Data</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Request::is('admin/user') ? 'active' : '' }} ">
                        <a href="{{ route('user.index') }}" class='sidebar-link'>
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Data User</span>
                        </a>
                    </li>


                    <li class="sidebar-title">Data Sekolah</li>

                    <li class="sidebar-item {{ Request::is('admin/kelas*') ? 'active' : '' }} ">
                        <a href="{{ route('kelas.index') }}" class='sidebar-link'>
                            <i class="bi bi-front"></i>
                            <span>Management Kelas</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Request::is('admin/siswa*') ? 'active' : '' }} ">
                        <a href="{{ route('siswa.index') }}" class='sidebar-link'>
                            <i class="bi bi-person-lines-fill"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('admin/guru*') ? 'active' : '' }} ">
                        <a href="{{ route('guru.index') }}" class='sidebar-link'>
                            <i class="bi bi-person-lines-fill"></i>
                            <span>Data Guru</span>
                        </a>
                    </li>
                    <li class="sidebar-title">Rekap Data</li>

                    <li class="sidebar-item {{ Request::is('admin/rekap-*') ? 'active' : '' }} ">
                        <a href="{{ route('rekap.bulanan') }}" class='sidebar-link'>
                            <i class="bi bi-clipboard-data"></i>
                            <span>Rekap Absensi Siswa</span>
                        </a>
                    </li><li class="sidebar-item {{ Request::is('admin/rekap/guru*') ? 'active' : '' }} ">
                        <a href="{{ route('rekap.guru.bulanan') }}" class='sidebar-link'>
                            <i class="bi bi-clipboard-data"></i>
                            <span>Rekap Absensi Guru</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Setting</li>

                    <li class="sidebar-item {{ Request::is('admin/libur') ? 'active' : '' }} ">
                        <a href="{{ route('admin.libur.index') }}" class='sidebar-link'>
                            <i class="bi bi-calendar2-plus-fill"></i>
                            <span>Set Hari Libur</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Request::is('admin/waktu-absensi') ? 'active' : '' }} ">
                        <a href="{{ route('admin.WaktuAbsensi.index') }}" class='sidebar-link'>
                            <i class="bi bi-alarm-fill"></i>
                            <span>Set Waktu Absensi</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if (auth()->user()->role === 'operator')
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu</li>

                    <li class="sidebar-item {{ Request::is('operator') ? 'active' : '' }} ">
                        <a href="{{ route('operator.dashboard') }}" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item  ">
                        <a href="{{ route('operator.absensi.index') }}" class='sidebar-link'>
                            <i class="bi bi-display-fill"></i>
                            <span>Absensi</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Rekapitulasi</li>


                    <li class="sidebar-item {{ Request::is('operator/absensi/siswa') ? 'active' : '' }} ">
                        <a href="{{ route('operator.absensi.siswa') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-medical-fill"></i>
                            <span>Absensi Siswa</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('operator/absensi/guru') ? 'active' : '' }} ">
                        <a href="{{ route('operator.absensi.guru') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-medical-fill"></i>
                            <span>Absnesi Guru</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Data Sekolah</li>

                    <li class="sidebar-item {{ Request::is('operator/kelas*') ? 'active' : '' }} ">
                        <a href="{{ route('operator.kelas.index') }}" class='sidebar-link'>
                            <i class="bi bi-front"></i>
                            <span>Data Kelas</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Request::is('operator/waktu-absensi') ? 'active' : '' }}">
                        <a href="{{ route('operator.waktu.index') }}" class="sidebar-link">
                            <i class="bi bi-bell-fill"></i>
                            <span>Waktu Absensi</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
