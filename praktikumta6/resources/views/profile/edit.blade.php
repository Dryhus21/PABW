<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Petugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Akun</h3>

                    <dl class="mt-4 space-y-2 text-sm text-gray-700">
                        <div class="flex flex-wrap gap-2">
                            <dt class="font-medium">Nama:</dt>
                            <dd>{{ Auth::user()->name }}</dd>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <dt class="font-medium">Email:</dt>
                            <dd>{{ Auth::user()->email }}</dd>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <dt class="font-medium">Akun dibuat:</dt>
                            <dd>{{ Auth::user()->created_at->diffForHumans() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
