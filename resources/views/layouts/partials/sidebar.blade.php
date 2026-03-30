<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ tenant() ? route('dashboard', tenant('id')) : url('/super-admin/dashboard') }}" class="brand-link" style="background-color: #343a40;">
      <img src="{{ (tenant() && tenant('logo_url')) ? global_asset('storage/' . tenant('logo_url')) : global_asset($appConfig->get('logo_url', 'adminlte3/dist/img/AdminLTELogo.png')) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8; background: #fff;">
      <span class="brand-text font-weight-bold" style="font-size: 1.1rem;">{{ tenant() ? (tenant('name') ?? tenant('id')) : $appConfig->get('app_name', 'MBG AkunPro') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ Auth::user()->profile_photo ? global_asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" class="img-circle elevation-2" alt="User Image" style="width: 33.6px; height: 33.6px; object-fit: cover;">
        </div>
        <div class="info">
          <a href="#" class="d-block font-weight-bold" style="font-size: 0.9rem;">{{ strtoupper(Auth::user()->name ?? 'DIAN ARDIANSYAH') }}</a>
          <a href="#" style="font-size: 0.75rem; color: #ced4da;"><i class="fas fa-circle text-success" style="font-size: 0.6rem;"></i> ONLINE</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
          
          @if(tenant())
              <!-- DASHBOARD -->
              <li class="nav-header text-uppercase opacity-50 small font-weight-bold">UTAMA</li>
              <li class="nav-item">
                <a href="{{ route('dashboard', tenant('id')) }}" class="nav-link {{ request()->is('*/dashboard') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dasbor Dapur</p>
                </a>
              </li>

              <!-- KEUANGAN & ANGGARAN -->
              <li class="nav-header text-uppercase opacity-50 small font-weight-bold mt-2">KEUANGAN</li>
              
              <li class="nav-item {{ request()->is('*/accounting*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('*/accounting*') ? 'active' : '' }} {{ !tenant()->isFeatureEnabled('accounting_full') ? 'text-muted' : '' }}">
                  <i class="nav-icon fas fa-calculator {{ request()->is('*/accounting*') ? '' : 'text-primary' }}"></i>
                  <p>
                    Akuntansi
                    @if(!tenant()->isFeatureEnabled('accounting_full'))
                        <i class="fas fa-lock text-xs float-right mt-1 ml-1 opacity-50"></i>
                    @else
                        <i class="right fas fa-angle-left"></i>
                    @endif
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('accounting.accounts.index', tenant('id')) }}" class="nav-link {{ request()->is('*/accounting/accounts*') ? 'active' : '' }}">
                      <i class="fas fa-book nav-icon text-xs"></i>
                      <p>Bagan Akun (COA)</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('accounting.journals.index', tenant('id')) }}" class="nav-link {{ request()->is('*/accounting/journals*') ? 'active' : '' }}">
                      <i class="fas fa-list-alt nav-icon text-xs"></i>
                      <p>Jurnal Umum</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('accounting.ledger.index', tenant('id')) }}" class="nav-link {{ request()->is('*/accounting/ledger*') ? 'active' : '' }}">
                      <i class="fas fa-book-open nav-icon text-xs"></i>
                      <p>Buku Besar</p>
                    </a>
                  </li>
                  <li class="nav-item {{ request()->is('*/accounting/reports*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('*/accounting/reports*') ? 'active' : '' }}">
                      <i class="fas fa-file-invoice nav-icon text-xs text-danger"></i>
                      <p>Laporan <i class="right fas fa-angle-left"></i></p>
                    </a>
                      <ul class="nav nav-treeview pl-3">
                          <li class="nav-item">
                             <a href="{{ route('accounting.reports.balance-sheet', tenant('id')) }}" class="nav-link {{ request()->is('*/accounting/reports/balance-sheet*') ? 'active' : '' }}">
                                 <i class="far fa-circle nav-icon text-xs"></i><p>Neraca</p>
                             </a>
                          </li>
                         <li class="nav-item">
                            <a href="{{ route('accounting.reports.profit-loss', tenant('id')) }}" class="nav-link {{ request()->is('*/accounting/reports/profit-loss*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs"></i><p>Laba Rugi</p>
                            </a>
                         </li>
                      </ul>
                  </li>
                </ul>
              </li>

              <li class="nav-item {{ request()->is('*/budgeting*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('*/budgeting*') ? 'active' : '' }} {{ !tenant()->isFeatureEnabled('budgeting') ? 'text-muted' : '' }}">
                  <i class="nav-icon fas fa-wallet {{ request()->is('*/budgeting*') ? '' : 'text-danger' }}"></i>
                  <p>
                    Anggaran
                    @if(!tenant()->isFeatureEnabled('budgeting'))
                        <i class="fas fa-lock text-xs float-right mt-1 ml-1 opacity-50"></i>
                    @else
                        <i class="right fas fa-angle-left"></i>
                    @endif
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('budgeting.index', tenant('id')) }}" class="nav-link {{ request()->is('*/budgeting') ? 'active' : '' }}">
                      <i class="fas fa-plus-circle nav-icon text-xs"></i>
                      <p>Input Anggaran</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('budgeting.monitoring', tenant('id')) }}" class="nav-link {{ request()->is('*/budgeting/monitoring*') ? 'active' : '' }}">
                      <i class="fas fa-chart-line nav-icon text-xs"></i>
                      <p>Monitoring</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- OPERASIONAL & PENJUALAN -->
              <li class="nav-header text-uppercase opacity-50 small font-weight-bold mt-2">OPERASIONAL</li>

              <li class="nav-item {{ request()->is('*/sales*') || request()->is('*/menu*') || request()->is('*/recipes*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('*/sales*') || request()->is('*/menu*') || request()->is('*/recipes*') ? 'active' : '' }} {{ !tenant()->isFeatureEnabled('sales') ? 'text-muted' : '' }}">
                  <i class="nav-icon fas fa-shopping-cart {{ request()->is('*/sales*') || request()->is('*/menu*') || request()->is('*/recipes*') ? '' : 'text-success' }}"></i>
                  <p>
                    Penjualan & Menu
                    @if(!tenant()->isFeatureEnabled('sales'))
                        <i class="fas fa-lock text-xs float-right mt-1 ml-1 opacity-50"></i>
                    @else
                        <i class="right fas fa-angle-left"></i>
                    @endif
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('tenant.sales.create', tenant('id')) }}" class="nav-link {{ request()->is('*/sales/create') ? 'active' : '' }}">
                      <i class="fas fa-plus-circle nav-icon text-xs text-success"></i>
                      <p>Kasir / Catat Jual</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('tenant.sales.index', tenant('id')) }}" class="nav-link {{ request()->is('*/sales') ? 'active' : '' }}">
                      <i class="fas fa-receipt nav-icon text-xs text-info"></i>
                      <p>Riwayat Transaksi</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('tenant.menu.recipe.indexAll', tenant('id')) }}" class="nav-link {{ request()->is('*/recipes*') ? 'active' : '' }}">
                      <i class="fas fa-layer-group nav-icon text-xs text-warning"></i>
                      <p>Resep & BOM</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('tenant.menu.index', tenant('id')) }}" class="nav-link {{ request()->is('*/menu*') ? 'active' : '' }}">
                      <i class="fas fa-utensils nav-icon text-xs text-primary"></i>
                      <p>Database Menu</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item {{ request()->is('*/inventory*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('*/inventory*') ? 'active' : '' }} {{ !tenant()->isFeatureEnabled('inventory') ? 'text-muted' : '' }}">
                  <i class="nav-icon fas fa-boxes {{ request()->is('*/inventory*') ? '' : 'text-warning' }}"></i>
                  <p>
                    Inventaris Stok
                    @if(!tenant()->isFeatureEnabled('inventory'))
                        <i class="fas fa-lock text-xs float-right mt-1 ml-1 opacity-50"></i>
                    @else
                        <i class="right fas fa-angle-left"></i>
                    @endif
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('inventory.items.index', tenant('id')) }}" class="nav-link {{ request()->is('*/inventory/items*') ? 'active' : '' }}">
                      <i class="fas fa-cube nav-icon text-xs text-warning"></i>
                      <p>Data Barang</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('inventory.movements.index', tenant('id')) }}" class="nav-link {{ request()->is('*/inventory/movements*') ? 'active' : '' }}">
                      <i class="fas fa-exchange-alt nav-icon text-xs text-info"></i>
                      <p>Pergerakan Stok</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="{{ route('tenant.circle-menus.index', tenant('id')) }}" class="nav-link {{ request()->is('*/circle-menus*') ? 'active' : '' }} {{ !tenant()->isFeatureEnabled('circle_menu') ? 'text-muted' : '' }}">
                  <i class="nav-icon fas fa-truck-loading {{ request()->is('*/circle-menus*') ? '' : 'text-primary' }}"></i>
                  <p>
                    Menu Circle (MBG)
                    @if(!tenant()->isFeatureEnabled('circle_menu'))
                        <i class="fas fa-lock text-xs float-right mt-1 ml-1 opacity-50"></i>
                    @endif
                  </p>
                </a>
              </li>

              <li class="nav-item {{ request()->is('*/procurement*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('*/procurement*') ? 'active' : '' }} {{ !tenant()->isFeatureEnabled('procurement') ? 'text-muted' : '' }}">
                  <i class="nav-icon fas fa-truck {{ request()->is('*/procurement*') ? '' : 'text-danger' }}"></i>
                  <p>
                    Pengadaan (PO)
                    @if(!tenant()->isFeatureEnabled('procurement'))
                        <i class="fas fa-lock text-xs float-right mt-1 ml-1 opacity-50"></i>
                    @else
                        <i class="right fas fa-angle-left"></i>
                    @endif
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('procurement.suppliers.index', tenant('id')) }}" class="nav-link {{ request()->is('*/procurement/suppliers*') ? 'active' : '' }}">
                      <i class="fas fa-users nav-icon text-xs"></i>
                      <p>Supplier</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('procurement.pos.index', tenant('id')) }}" class="nav-link {{ request()->is('*/procurement/pos*') ? 'active' : '' }}">
                      <i class="fas fa-shopping-cart nav-icon text-xs"></i>
                      <p>Purchase Order</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- SDM & KARYAWAN -->
              <li class="nav-header text-uppercase opacity-50 small font-weight-bold mt-2">SDM & KARYAWAN</li>
              <li class="nav-item {{ request()->is('*/hr*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('*/hr*') ? 'active' : '' }} {{ !tenant()->isFeatureEnabled('hr') ? 'text-muted' : '' }}">
                  <i class="nav-icon fas fa-user-friends {{ request()->is('*/hr*') ? '' : 'text-info' }}"></i>
                  <p>
                    Manajemen SDM
                    @if(!tenant()->isFeatureEnabled('hr'))
                        <i class="fas fa-lock text-xs float-right mt-1 ml-1 opacity-50"></i>
                    @else
                        <i class="right fas fa-angle-left"></i>
                    @endif
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('tenant.hr.employee.index', tenant('id')) }}" class="nav-link {{ request()->is('*/hr/employees*') ? 'active' : '' }}">
                      <i class="fas fa-users-cog nav-icon text-xs"></i>
                      <p>Daftar Karyawan</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('tenant.hr.payroll.index', tenant('id')) }}" class="nav-link {{ request()->is('*/hr/payrolls*') ? 'active' : '' }}">
                      <i class="fas fa-money-check-alt nav-icon text-xs"></i>
                      <p>Penggajian (Payroll)</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- SISTEM & PENGATURAN -->
              <li class="nav-header text-uppercase opacity-50 small font-weight-bold mt-2">PENGATURAN</li>
              <li class="nav-item">
                <a href="{{ route('profile.edit', tenant('id')) }}" class="nav-link {{ request()->is('*/profile*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-cog {{ request()->is('*/profile*') ? '' : 'text-primary' }}"></i>
                  <p>Profil Saya</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('settings.users.index', tenant('id')) }}" class="nav-link {{ request()->is('*/settings/users*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-shield {{ request()->is('*/settings/users*') ? '' : 'text-info' }}"></i>
                  <p>Manajemen User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('settings.kitchen.index', tenant('id')) }}" class="nav-link {{ request()->is('*/settings/kitchen*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-store {{ request()->is('*/settings/kitchen*') ? '' : 'text-secondary' }}"></i>
                  <p>Profil Dapur</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('tenant.billing.index', tenant('id')) }}" class="nav-link {{ request()->is('*/billing*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-file-invoice-dollar {{ request()->is('*/billing*') ? '' : 'text-success' }}"></i>
                  <p>
                    Langganan
                    @if(tenant()->is_on_trial)
                        <span class="badge badge-warning right">{{ tenant()->trial_days_left }} Hari Trial</span>
                    @endif
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('tenant.support.index', tenant('id')) }}" class="nav-link {{ request()->is('*/support*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-headset {{ request()->is('*/support*') ? '' : 'text-info' }}"></i>
                  <p>Pusat Dukungan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('tenant.testimonials.index', tenant('id')) }}" class="nav-link {{ request()->is('*/testimonials*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-star {{ request()->is('*/testimonials*') ? '' : 'text-warning' }}"></i>
                  <p>Kesan & Pesan Kami</p>
                </a>
              </li>
          @else
              <!-- MENU CENTRAL (SUPER ADMIN) -->
              <li class="nav-header">CORE SYSTEM</li>
              <li class="nav-item">
                <a href="{{ url('/super-admin/dashboard') }}" class="nav-link {{ request()->is('super-admin/dashboard') ? 'active bg-primary' : '' }}">
                  <i class="nav-icon fas fa-chart-pie"></i>
                  <p>Main Dashboard</p>
                </a>
              </li>

              <li class="nav-item {{ request()->is('super-admin/tenants*') || request()->is('super-admin/subscriptions*') || request()->is('super-admin/users*') || request()->is('super-admin/promos*') || request()->is('super-admin/announcements*') || request()->is('super-admin/billing*') || request()->is('super-admin/payment-methods*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('super-admin/tenants*') || request()->is('super-admin/subscriptions*') || request()->is('super-admin/users*') || request()->is('super-admin/promos*') || request()->is('super-admin/announcements*') || request()->is('super-admin/billing*') || request()->is('super-admin/payment-methods*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-layer-group"></i>
                  <p>
                    SaaS Management
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ url('/super-admin/tenants') }}" class="nav-link {{ request()->is('super-admin/tenants*') ? 'active' : '' }}">
                      <i class="fas fa-landmark nav-icon" style="font-size: 0.9rem;"></i>
                      <p>Data Cabang Dapur</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/super-admin/subscriptions') }}" class="nav-link {{ request()->is('super-admin/subscriptions*') ? 'active' : '' }}">
                      <i class="fas fa-box-open nav-icon" style="font-size: 0.9rem;"></i>
                      <p>Paket Langganan</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/super-admin/users') }}" class="nav-link {{ request()->is('super-admin/users*') ? 'active' : '' }}">
                      <i class="fas fa-users-cog nav-icon" style="font-size: 0.9rem;"></i>
                      <p>User Global</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/super-admin/promos') }}" class="nav-link {{ request()->is('super-admin/promos*') ? 'active' : '' }}">
                      <i class="fas fa-percent nav-icon" style="font-size: 0.9rem;"></i>
                      <p>Kode Promo</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->is('super-admin/announcements*') ? 'active' : '' }}">
                      <i class="fas fa-bullhorn nav-icon" style="font-size: 0.9rem;"></i>
                      <p>Pengumuman Global</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/super-admin/billing') }}" class="nav-link {{ request()->is('super-admin/billing*') ? 'active' : '' }}">
                      <i class="fas fa-file-invoice-dollar nav-icon" style="font-size: 0.9rem;"></i>
                      <p>Billing / Invoice</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/super-admin/payment-methods') }}" class="nav-link {{ request()->is('super-admin/payment-methods*') ? 'active' : '' }}">
                      <i class="fas fa-credit-card nav-icon" style="font-size: 0.9rem;"></i>
                      <p>Rekening Bank</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-header mt-2">FRONTEND CMS</li>
              <li class="nav-item {{ request()->is('super-admin/landing*') || request()->is('super-admin/testimonials*') || request()->is('super-admin/custom-pages*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('super-admin/landing*') || request()->is('super-admin/testimonials*') || request()->is('super-admin/custom-pages*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-copy"></i>
                  <p>
                    Landing Page
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{ url('/super-admin/custom-pages') }}" class="nav-link {{ request()->is('super-admin/custom-pages*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon" style="font-size: 0.9rem;"></i>
                        <p>Halaman Kustom</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ url('/super-admin/landing-settings') }}" class="nav-link {{ request()->is('super-admin/landing-settings*') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h nav-icon" style="font-size: 0.9rem;"></i>
                        <p>Settings Utama</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ url('/super-admin/testimonials') }}" class="nav-link {{ request()->is('super-admin/testimonials*') ? 'active' : '' }}">
                        <i class="fas fa-comments nav-icon" style="font-size: 0.9rem;"></i>
                        <p>Testimoni Client</p>
                      </a>
                    </li>
                </ul>
              </li>

              <li class="nav-header mt-2">SYSTEM & SUPPORT</li>
              <li class="nav-item">
                <a href="{{ url('/super-admin/config') }}" class="nav-link {{ request()->is('super-admin/config*') ? 'active bg-primary' : '' }}">
                  <i class="nav-icon fas fa-tools"></i>
                  <p>Config Aplikasi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('super-admin.audit-logs.index') }}" class="nav-link {{ request()->is('super-admin/audit-logs*') ? 'active bg-primary' : '' }}">
                  <i class="nav-icon fas fa-shield-alt"></i>
                  <p>Audit Logs</p>
                </a>
              </li>
              <li class="nav-item {{ request()->is('super-admin/support*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('super-admin/support*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-life-ring"></i>
                  <p>
                    Support Center
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('support.tickets.index') }}" class="nav-link {{ request()->is('super-admin/support/tickets*') ? 'active' : '' }}">
                      <i class="fas fa-ticket-alt nav-icon" style="font-size: 0.9rem;"></i>
                      <p>List Tiket</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('support.faq.index') }}" class="nav-link {{ request()->is('super-admin/support/faq*') ? 'active' : '' }}">
                      <i class="fas fa-question-circle nav-icon" style="font-size: 0.9rem;"></i>
                      <p>Data FAQ</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="{{ route('backups.index') }}" class="nav-link {{ request()->is('super-admin/backups*') ? 'active bg-primary' : '' }}">
                  <i class="nav-icon fas fa-database"></i>
                  <p>Database Backup</p>
                </a>
              </li>

              <li class="nav-header mt-2">AKUN</li>
              <li class="nav-item">
                <a href="{{ route('super-admin.profile.edit') }}" class="nav-link {{ request()->is('super-admin/profile*') ? 'active bg-primary' : '' }}">
                  <i class="nav-icon fas fa-user-cog"></i>
                  <p>Profil Administrator</p>
                </a>
              </li>
          @endif

          <li class="nav-item mt-2">
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-power-off text-danger"></i>
              <p class="text-danger">Keluar Aplikasi</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
  </form>
