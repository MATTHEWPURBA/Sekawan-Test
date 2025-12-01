<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('bookings.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="vehicle_id" :value="__('Vehicle')" />
                            <select name="vehicle_id" id="vehicle_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Select Vehicle</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} - {{ $vehicle->brand }} {{ $vehicle->model }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('vehicle_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="driver_id" :value="__('Driver')" />
                            <select name="driver_id" id="driver_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Select Driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->license_number }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('driver_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time')" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time" :value="old('end_time')" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="purpose" :value="__('Purpose')" />
                            <textarea id="purpose" name="purpose" class="mt-1 block w-full border-gray-300 rounded-md" rows="3" required>{{ old('purpose') }}</textarea>
                            <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="destination" :value="__('Destination')" />
                            <x-text-input id="destination" class="block mt-1 w-full" type="text" name="destination" :value="old('destination')" required />
                            <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 rounded-md" rows="3">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="approvers" :value="__('Approvers (Minimum 2)')" />
                            <select name="approvers[]" id="approvers" class="mt-1 block w-full border-gray-300 rounded-md" multiple required>
                                @foreach($approvers as $approver)
                                    <option value="{{ $approver->id }}">{{ $approver->name }} ({{ $approver->email }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Hold Ctrl/Cmd to select multiple approvers</p>
                            <x-input-error :messages="$errors->get('approvers')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('bookings.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                        <x-primary-button>
                            {{ __('Create Booking') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

