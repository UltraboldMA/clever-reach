<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('CleverReach Formulare') }}

        </h2>
    </x-slot>
    <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-5 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            @if (session()->has('message'))
                <div class="px-2 py-1 my-5 text-white bg-green-600 rounded">{{ session()->get('message') }}</div>
            @elseif(session()->has('error'))
                <div class="px-2 py-1 my-5 text-white bg-red-600 rounded">{{ session()->get('error') }}</div>
            @endif
            <div class="flex w-full">
                @if (!config('clever-reach.singleClient'))
                    <select wire:model="selectedClient" class="border border-gray-200 rounded-l">
                        <option value="">{{ __('Client auswählen') }}</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                @endif
                <button type="button" wire:click="loadForms" class="px-2 py-1 text-white bg-green-400 hover:bg-green-600">Load</button>
                <button type="button" wire:click="refreshForms" class="px-2 py-1 text-white bg-red-400 rounded-r hover:bg-red-600">Refresh from API</button>
                <div role="status" class="mt-1 ml-5" wire:loading>
                    @include('clever-reach::components.spinner')
                </div>
            </div>
            @if (!empty($forms))
                <table class="w-full">
                    <thead>
                        <th class="py-1 text-left">ID</th>
                        <th class="py-1 text-left">Name</th>
                    </thead>
                    <tbody>
                        @foreach ($forms as $form)
                            <tr>
                                <td>{{ $form->id }}</td>
                                <td>{{ $form->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
