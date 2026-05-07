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
            </div>
        </div>
    </div>
</x-app-layout>

<form method="POST" action="{{ route('employee.logout') }}">
    @csrf
    <button type="submit"
            class="bg-gray-700 text-gray-300 text-xs px-3 py-2 rounded-lg hover:bg-red-600 hover:text-white transition">
        Keluar
    </button>
</form>