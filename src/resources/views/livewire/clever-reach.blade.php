<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('CleverReach') }}

        </h2>
    </x-slot>
    <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-5 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            @if (!$showForm)
                @if (session()->has('message'))
                    <div class="px-2 py-1 my-5 text-white bg-green-600 rounded">{{ session()->get('message') }}</div>
                @elseif(session()->has('error'))
                    <div class="px-2 py-1 my-5 text-white bg-red-600 rounded">{{ session()->get('error') }}</div>
                @endif
                @if ($cleverReachClients->isEmpty() && config('clever-reach.singleClient'))
                    <button type="button" class="float-right" wire:click="createClient">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                @endif
                <table class="w-full">
                    <thead>
                        <th class="py-1 text-left">Name</th>
                        <th class="py-1 text-left">Client ID</th>
                        <th class="py-1 text-left">Newsletters</th>
                        <th class="py-1 text-left">Token Expires</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($cleverReachClients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->client_id }}</td>
                                <td>{{ $client->clever_reach_newsletters_count }}</td>
                                <td>{{ $client->tokenValid }}</td>
                                <td class="text-right">
                                    <button type="button" wire:click="refreshToken({{ $client->id }})"
                                        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-green-800 border border-transparent rounded-md hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25">
                                        <div wire:loading.delay.remove>Token</div>
                                        <div wire:loading.delay>Loading</div>
                                    </button>
                                    <button wire:click="editClient({{ $client->id }})" type="button"
                                        class="items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25">
                                        {{ __('Edit') }}
                                    </button>
                                    <button wire:click="deleteConfirmClient({{ $client->id }})" type="button"
                                        class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-red-600 border border-transparent rounded-md hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <!-- create/edit client -->
            @if ($showForm)
                <div class="grid grid-cols-12 gap-4 py-5">
                    <div class="col-span-12 py-2 sm:col-span-6">
                        <label for="client.name" class="block text-sm font-medium text-gray-700">
                            {{ __('Name') }}
                        </label>
                        <input wire:model="client.name" autofocus type="text" id="client.name"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        @error('client.name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-12 py-2 sm:col-span-6">
                        <label for="client.client_id" class="block text-sm font-medium text-gray-700">
                            {{ __('Client ID') }}
                        </label>
                        <input wire:model="client.client_id" type="text" id="client.client_id"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        @error('client.client_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-12 py-2">
                        <label for="client.client_secret" class="block text-sm font-medium text-gray-700">
                            {{ __('Secret') }}
                        </label>
                        <input wire:model="client.client_secret" type="password" id="client.client_secret" autocomplete="off"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        @error('client.client_secret')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-6 col-start-1 text-left">
                        <button wire:click="cancel" type="button"
                            class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-red-600 border border-transparent rounded-md hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                    <div class="col-span-6 text-right">
                        <button wire:click="saveClient" type="button"
                            class="items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if ($showConfirm)
        <div class="absolute top-0 flex w-screen min-h-screen py-10 antialiased font-medium text-gray-800 bg-opacity-90 bg-slate-200">
            <div class="max-w-lg mx-2 p-2 sm:m-auto bg-white border-[1px] border-gray-200 shadow rounded-xl hover:shadow-lg">
                <div class="relative p-6">
                    <a href="#" wire:click="cancel" class="absolute top-1.5 right-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 cursor-pointer fill-current text-slate-500 hover:text-slate-900">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold">{{ __('Delete Client: ') }} {{ $client->name }}</h1>
                    <p class="text-sm text-gray-500">{{ __('Are you sure you want to delete this client.') }}</p>
                    <div class="flex flex-row mt-6 space-x-2 justify-evenly">
                        <a href="#" wire:click="cancel" class="w-full py-3 text-sm text-center text-gray-500 transition duration-150 ease-linear bg-white border border-gray-200 rounded-lg hover:bg-gray-100">{{ __('Cancel') }}</a>
                        <a href="#" wire:click="deleteClient"
                            class="w-full py-3 text-sm font-medium text-center text-white transition duration-150 ease-linear bg-red-600 border border-red-600 rounded-lg hover:bg-red-500">{{ __('Delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
