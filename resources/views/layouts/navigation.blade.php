{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-bold text-xl text-gray-800">☕ Emily Coffee</a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    @can('manage categories')
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">Kategori</x-nav-link>
                    @endcan
                    @can('manage products')
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Produk</x-nav-link>
                    @endcan
                    @can('manage tables')
                        <x-nav-link :href="route('tables.index')" :active="request()->routeIs('tables.*')">Meja</x-nav-link>
                    @endcan
                    @can('manage users')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Pengguna</x-nav-link>
                    @endcan
                    @can('create transactions')
                        <x-nav-link :href="route('transactions.create')" :active="request()->routeIs('transactions.create')">Transaksi Baru</x-nav-link>
                    @endcan
                    @can('view transactions')
                        <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">Riwayat Transaksi</x-nav-link>
                    @endcan
                    @can('view reports')
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Laporan</x-nav-link>
                    @endcan
                    @can('view logs')
                        <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">Log Aktivitas</x-nav-link>
                    @endcan
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <span class="inline-flex rounded-md">
                                <span class="px-3 py-2 text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            </span>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Logout</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>