<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Vehicle') }} - {{ $vehicle->license_plate }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="license_plate" :value="__('License Plate')" />
                            <x-text-input id="license_plate" class="block mt-1 w-full" type="text" name="license_plate" :value="old('license_plate', $vehicle->license_plate)" required autofocus />
                            <x-input-error :messages="$errors->get('license_plate')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="brand" :value="__('Brand')" />
                            <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand', $vehicle->brand)" required />
                            <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="model" :value="__('Model')" />
                            <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model', $vehicle->model)" required />
                            <x-input-error :messages="$errors->get('model')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Select Type</option>
                                <option value="angkutan_orang" {{ old('type', $vehicle->type) == 'angkutan_orang' ? 'selected' : '' }}>Angkutan Orang</option>
                                <option value="angkutan_barang" {{ old('type', $vehicle->type) == 'angkutan_barang' ? 'selected' : '' }}>Angkutan Barang</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="ownership" :value="__('Ownership')" />
                            <select name="ownership" id="ownership" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Select Ownership</option>
                                <option value="perusahaan" {{ old('ownership', $vehicle->ownership) == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                                <option value="sewa" {{ old('ownership', $vehicle->ownership) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                            </select>
                            <x-input-error :messages="$errors->get('ownership')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="region" :value="__('Region')" />
                            <x-text-input id="region" class="block mt-1 w-full" type="text" name="region" :value="old('region', $vehicle->region)" />
                            <x-input-error :messages="$errors->get('region')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('Location')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $vehicle->location)" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 rounded-md" rows="3">{{ old('notes', $vehicle->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $vehicle->is_active) ? 'checked' : '' }} class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('vehicles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                        <x-primary-button>
                            {{ __('Update Vehicle') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
