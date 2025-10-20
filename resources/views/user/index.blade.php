<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Akun Kasir') }}
            </h2>
            <a href="{{ route('admin.register') }}"
                class="inline-block px-3 py-1 text-xs font-medium text-gray-800 bg-white hover:bg-gray-200 rounded">
                + Tambah Kasir Baru
            </a>
        </div>
    </x-slot>

    <div class="">
    <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
        <div class="overflow-hidden bg-white shadow-sm dark:bg-white sm:rounded-lg">
            <div class="px-4 pt-4 mb-3 w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
                
                {{-- Hasil Pencarian --}}
                @if (request('search'))
                    <div class="bg-blue-600 text-white text-sm font-semibold rounded-t-md px-4 py-2 mb-4">
                        Hasil pencarian untuk: <strong class="font-bold">{{ request('search') }}</strong>
                    </div>
                @endif

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('user.index') }}" class="flex items-center gap-2">
                    <x-text-input id="search" name="search" type="text"
                        class="w-full mr-2 text-sm py-1 px-2 text-white bg-gray-800 border-gray-600"
                        placeholder="Cari berdasarkan nama produk..." value="{{ request('search') }}" autofocus />

                    {{-- Tombol Search --}}
                    <x-search-button type="submit" class="text-xs px-3 py-1">
                        {{ __('Search') }}
                    </x-search-button>

                    {{-- Tombol Reset (muncul hanya jika ada pencarian) --}}
                    @if (request('search'))
                        <a href="{{ route('user.index') }}"
                            class="text-xs px-3 py-2 rounded-xl bg-gradient-to-br from-amber-600 via-amber-500 to-amber-500/70
                                   backdrop-blur-md text-white font-medium border
                                   hover:scale-105 hover:shadow-lg transition-all duration-300 whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

                @if (session('success'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="pb-2 ml-3 text-xs text-green-600 dark:text-green-600 font-semibold">
                        {{ session('success') }}
                    </p>
                @endif

                @if (session('danger'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="pb-2 ml-3 text-xs text-red-600 dark:text-red-600 font-semibold">
                        {{ session('danger') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="relative overflow-x-auto flex justify-center">
            <table class="w-full max-w-full text-xs text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-white uppercase dark:text-gray-200"
                    style="background-image: url('{{ asset('images/card.png') }}'); background-size: cover; background-position: center;">
                    <tr>
                        <th class="px-3 py-2">Id</th>
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Email</th>
                        <th class="px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="bg-cover bg-center" style="background-image: url('{{ asset('images/card1.png') }}');">
                            <td class="px-3 py-2 font-medium text-white dark:text-white">
                                {{ $user->id }}
                            </td>
                            <td class="px-2 py-1 font-medium text-white dark:text-white">
                                {{ $user->name }}
                            </td>
                            <td class="px-2 py-1 text-white dark:text-white">
                                {{ $user->email }}
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex flex-col sm:flex-row sm:space-x-2 text-xs items-start">
                                    <a href="{{ route('user.edit', $user) }}"
                                        class="inline-flex items-center bg-white px-1 py-0.5 rounded font-semibold text-blue-600 hover:bg-blue-50 hover:underline mb-1 sm:mb-0">
                                        Edit
                                    </a>

                                    <form action="{{ route('user.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');"
                                        class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center bg-white px-1 py-0.5 rounded font-semibold text-red-600 hover:bg-red-50 hover:underline mb-1 sm:mb-0">
                                            Delete
                                        </button>
                                    </form>

                                    @if ($user->is_admin)
                                        <form action="{{ route('user.removeadmin', $user) }}" method="POST" class="inline-flex">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center bg-white px-1 py-0.5 rounded font-semibold text-emerald-600 hover:bg-emerald-50 hover:underline whitespace-nowrap mb-1 sm:mb-0">
                                                Remove Admin
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('user.makeadmin', $user) }}" method="POST" class="inline-flex">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center bg-white px-1 py-0.5 rounded font-semibold text-amber-600 hover:bg-amber-50 hover:underline whitespace-nowrap mb-1 sm:mb-0">
                                                Make Admin
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr
                            style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">
                            <td class="px-3 py-2 text-white text-center font-medium" colspan="4">
                                Empty
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 text-xs">
                {{ $users->links() }}
            </div>
        @endif
    </div>
    </div>
    </div>
</x-app-layout>