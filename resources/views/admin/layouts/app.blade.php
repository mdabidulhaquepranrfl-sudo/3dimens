<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - ShopAdmin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#f0f4ff', 100: '#dce6fe', 500: '#4f72d9', 600: '#3b5dc8', 700: '#2e4aad' },
                        surface: '#f8f9fc',
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        #sidebar {
            transition: width 0.25s ease;
        }

        .nav-link {
            transition: background 0.15s, color 0.15s;
        }

        .nav-link.active,
        .nav-link:hover {
            background: rgba(79, 114, 217, 0.12);
            color: #4f72d9;
        }

        .nav-link.active .nav-icon,
        .nav-link:hover .nav-icon {
            color: #4f72d9;
        }

        table.dataTable thead th {
            background: #f8f9fc;
            color: #374151;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }

        table.dataTable tbody tr:hover {
            background: #f0f4ff;
        }

        table.dataTable {
            border-collapse: collapse !important;
        }

        table.dataTable td,
        table.dataTable th {
            padding: 10px 14px !important;
            border-bottom: 1px solid #f1f3f9 !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.85rem;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #4f72d9;
            box-shadow: 0 0 0 2px rgba(79, 114, 217, 0.15);
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.85rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 5px !important;
            border: none !important;
            padding: 4px 10px !important;
            font-size: 0.82rem !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f72d9 !important;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f0f4ff !important;
            color: #4f72d9 !important;
        }

        .modal-backdrop {
            background: rgba(15, 20, 40, 0.45);
            backdrop-filter: blur(2px);
        }

        .btn-primary {
            background: #4f72d9;
            color: #fff;
        }

        .btn-primary:hover {
            background: #3b5dc8;
        }

        .btn-secondary {
            background: #f1f3f9;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #4f72d9 !important;
            box-shadow: 0 0 0 3px rgba(79, 114, 217, 0.12) !important;
            outline: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        /* Select2 Tailwind Restyling */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 8px !important;
            background-color: #ffffff !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937 !important;
            font-size: 0.875rem !important;
            line-height: 40px !important;
            padding-left: 4px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 8px !important;
        }
        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden !important;
            font-size: 0.875rem !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            padding: 6px 10px !important;
            outline: none !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4f72d9 !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #4f72d9 !important;
            box-shadow: 0 0 0 3px rgba(79, 114, 217, 0.12) !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-surface min-h-screen flex text-gray-800 antialiased overflow-x-hidden">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

    <!-- SIDEBAR (Slide-over on mobile, static on desktop) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 min-h-screen bg-white border-r border-gray-100 flex flex-col flex-shrink-0 shadow-xl lg:shadow-sm transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center shadow-sm shadow-brand-500/30">
                    <i class="fa-solid fa-store text-white text-sm"></i>
                </div>
                <span class="font-bold text-gray-800 text-base tracking-tight">ShopAdmin</span>
            </a>
            <div class="flex items-center gap-2">
                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-brand-50 text-brand-600">Admin</span>
                <!-- Mobile Close Sidebar Button -->
                <button type="button" id="sidebarCloseBtn" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors" aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
        </div>

        <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
            <p class="text-[11px] font-semibold text-gray-400 px-3 pt-2 pb-1 uppercase tracking-widest">Main Menu</p>
            
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 font-medium text-sm">
                <i class="nav-icon fa-solid fa-chart-pie text-gray-400 w-4 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 font-medium text-sm">
                <i class="nav-icon fa-solid fa-box-open text-gray-400 w-4 text-center"></i>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.customers.index') }}"
                class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 font-medium text-sm">
                <i class="nav-icon fa-solid fa-users text-gray-400 w-4 text-center"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 font-medium text-sm">
                <i class="nav-icon fa-solid fa-receipt text-gray-400 w-4 text-center"></i>
                <span>Orders</span>
            </a>
        </nav>

        <!-- Current Admin User & Logout -->
        <div class="px-4 py-4 border-t border-gray-100 bg-gray-50/50 mt-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-600 font-semibold text-xs">
                            {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'AD', 0, 2)) }}
                        </span>
                    </div>
                    <div class="truncate">
                        <p class="text-sm font-semibold text-gray-700 truncate">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::guard('admin')->user()->email ?? 'admin@shop.com' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTAINER -->
    <div class="flex-1 flex flex-col min-w-0 w-full overflow-x-hidden">

        <!-- TOP BAR -->
        <header class="bg-white border-b border-gray-100 px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sticky top-0 z-30 shadow-sm/50">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Hamburger button for Mobile -->
                <button type="button" id="sidebarToggleBtn" class="lg:hidden p-2 -ml-1 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors" aria-label="Open sidebar">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg font-bold text-gray-800 leading-tight truncate">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400 mt-0.5 truncate hidden sm:block">@yield('page_sub', 'Manage your e-commerce platform')</p>
                </div>
            </div>
            <div class="flex items-center flex-wrap gap-2 sm:gap-3 self-end sm:self-auto">
                @yield('top_actions')
            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        <div class="px-3 sm:px-6 pt-3">
            @if(session('success'))
                <div class="mb-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500 flex-shrink-0"></i>
                        <span class="text-xs sm:text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="$(this).parent().fadeOut()" class="text-emerald-400 hover:text-emerald-600 ml-2"><i class="fa-solid fa-times text-xs"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-3 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                        <span class="text-xs sm:text-sm">{{ session('error') }}</span>
                    </div>
                    <button onclick="$(this).parent().fadeOut()" class="text-red-400 hover:text-red-600 ml-2"><i class="fa-solid fa-times text-xs"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-3 px-4 py-3 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm shadow-sm">
                    <p class="font-semibold mb-1 flex items-center gap-2 text-xs sm:text-sm"><i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i> Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-3 sm:p-5 md:p-6 w-full max-w-full overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <!-- GLOBAL DELETE CONFIRM MODAL -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center modal-backdrop p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-auto overflow-hidden border border-gray-100">
            <div class="p-5 sm:p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-trash text-lg"></i>
                </div>
                <h3 class="text-base font-bold text-gray-800 mb-1">Delete <span id="deleteItemType">Record</span>?</h3>
                <p class="text-xs sm:text-sm text-gray-500 mb-6">This action cannot be undone. Are you sure you want to remove this record?</p>
                
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeDeleteModal()" class="flex-1 btn-secondary text-xs sm:text-sm font-medium py-2.5 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white text-xs sm:text-sm font-medium py-2.5 rounded-lg transition-colors shadow-sm shadow-red-500/20">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Init Select2 globally for any dropdown with .select2 class
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });

        // Mobile Sidebar Toggle Logic
        const $sidebar = $('#sidebar');
        const $backdrop = $('#sidebarBackdrop');

        function openSidebar() {
            $sidebar.removeClass('-translate-x-full');
            $backdrop.removeClass('hidden');
            $('body').addClass('overflow-hidden lg:overflow-auto');
        }

        function closeSidebar() {
            $sidebar.addClass('-translate-x-full');
            $backdrop.addClass('hidden');
            $('body').removeClass('overflow-hidden lg:overflow-auto');
        }

        $('#sidebarToggleBtn').on('click', openSidebar);
        $('#sidebarCloseBtn').on('click', closeSidebar);
        $backdrop.on('click', closeSidebar);

        // Auto-close mobile sidebar when clicking a link
        $('#sidebar nav a').on('click', function() {
            if ($(window).width() < 1024) {
                closeSidebar();
            }
        });

        // Global Delete Modal Helper
        function confirmDelete(actionUrl, typeName = 'Record') {
            $('#deleteItemType').text(typeName);
            $('#deleteForm').attr('action', actionUrl);
            $('#deleteModal').removeClass('hidden').addClass('flex');
        }

        function closeDeleteModal() {
            $('#deleteModal').addClass('hidden').removeClass('flex');
        }

        $('#deleteModal').on('click', function(e) {
            if ($(e.target).is('#deleteModal')) closeDeleteModal();
        });
    </script>
    @stack('scripts')
</body>
</html>
