<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
                <div class="p-6 text-gray-700">
                    <strong>Role Anda:</strong> 
                    <span class="text-blue-600">
                        {{ Auth::user()->role ?? 'Tidak ada role yang ditentukan' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar-footer text-center mt-3 p-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-75">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
</x-app-layout>
