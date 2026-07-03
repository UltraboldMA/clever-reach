<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('CleverReach Newsletters') }}
        </h2>
    </x-slot>
    <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-5 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            @if (session()->has('message'))
                <div class="px-2 py-1 my-5 text-white bg-green-600 rounded">{{ session()->get('message') }}</div>
            @elseif(session()->has('error'))
                <div class="px-2 py-1 my-5 text-white bg-red-600 rounded">{{ session()->get('error') }}</div>
            @endif
            <div class="flex justify-end w-full">
                <div role="status" class="mt-1 ml-5" wire:loading.delay>
                    @include('clever-reach::components.spinner')
                </div>
                @if (!$showForm)
                    <button type="button" wire:click="create" class="px-2 py-1 text-white bg-green-400 hover:bg-green-600">{{ __('Create') }}</button>
                @else
                    <button type="button" wire:click="cancel" class="px-2 py-1 text-white bg-red-400 hover:bg-red-600">{{ __('Cancel') }}</button>
                @endif
            </div>
            @if (!$showForm)
                @if (!$newsletters->isEmpty())
                    <table class="w-full">
                        <thead>
                            <th class="py-1 text-left">ID</th>
                            <th class="py-1 text-left">Name</th>
                            <th class="py-1 text-left">Key</th>
                            <th class="py-1 text-left">{{ __('Form') }}</th>
                            <th class="py-1 text-left">{{ __('Group') }}</th>
                            <th class="py-1 text-left">{{ __('Language') }}</th>
                            <th class="py-1 text-left">{{ __('Double Opt-In') }}</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($newsletters as $newsletter)
                                <tr>
                                    <td>{{ $newsletter->id }}</td>
                                    <td>{{ $newsletter->name }}</td>
                                    <td>{{ $newsletter->key }}</td>
                                    <td>{{ $newsletter->clever_reach_form?->name }}</td>
                                    <td>{{ $newsletter->clever_reach_group?->name }}</td>
                                    <td>{{ $newsletter->language }}</td>
                                    <td>
                                        @if ($newsletter->double_opt_in)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </td>
                                    <td class="flex justify-end">
                                        <button wire:click="edit({{ $newsletter->id }})" type="button" class="px-2 py-1 text-sm text-white bg-green-400 hover:bg-green-600">{{ __('Edit') }}</button>
                                        <button wire:click="delete({{ $newsletter->id }})" type="button" class="px-2 py-1 text-sm text-white bg-red-400 hover:bg-red-600">{{ __('Delete') }}</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-12 gap-4 mt-10">
                        <div class="flex flex-col col-span-12 sm:col-span-6">
                            <label>{{ __('Group') }}</label>
                            <select class="w-full border border-gray-200 rounded" wire:model="cleverReachNewsletter.group_id">
                                <option value="">{{ __('Select group') }}</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}/{{ $group->external_id }}</option>
                                @endforeach
                            </select>
                            @error('cleverReachNewsletter.group_id')
                                <span class="text-sm italic text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col col-span-12 sm:col-span-6">
                            <label>{{ __('Form') }}</label>
                            <select class="w-full border border-gray-200 rounded" wire:model="cleverReachNewsletter.form_id">
                                <option value="">{{ __('Select form') }}</option>
                                @foreach ($forms as $form)
                                    <option value="{{ $form->id }}">{{ $form->name }}/{{ $form->external_id }}</option>
                                @endforeach
                            </select>
                            @error('cleverReachNewsletter.form_id')
                                <span class="text-sm italic text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!config('clever-reach.singleClient'))
                            <div class="flex flex-col col-span-12 sm:col-span-6">
                                <label>{{ __('Client') }}</label>
                                <select class="w-full border border-gray-200 rounded" wire:model="cleverReachNewsletter.clever_reach_client_id">
                                    <option value="">{{ __('Select client') }}</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('cleverReachNewsletter.clever_reach_client_id')
                                    <span class="text-sm italic text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <div class="flex flex-col col-span-12 sm:col-span-6">
                            <label>{{ __('Language') }}</label>
                            <select class="w-full border border-gray-200 rounded" wire:model="cleverReachNewsletter.language">
                                <option value="">{{ __('Select language') }}</option>
                                @foreach (config('clever-reach.available_languages') as $lang)
                                    <option value="{{ $lang }}">{{ strtoupper($lang) }}</option>
                                @endforeach
                            </select>
                            @error('cleverReachNewsletter.language')
                                <span class="text-sm italic text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col col-span-12 sm:col-span-6">
                            <label>Name</label>
                            <input type="text" class="border border-gray-200 rounded" wire:model="cleverReachNewsletter.name" />
                            @error('cleverReachNewsletter.name')
                                <span class="text-sm italic text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col col-span-12 sm:col-span-6">
                            <label>{{ 'Key' }}</label>
                            <input type="text" class="border border-gray-200 rounded" wire:model="cleverReachNewsletter.key" />
                            @error('cleverReachNewsletter.key')
                                <span class="text-sm italic text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col col-span-12">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="1" wire:model="cleverReachNewsletter.double_opt_in" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Double-opt-in') }}</span>
                            </label>
                        </div>
                        <div class="flex flex-col col-span-12 sm:col-span-6">
                            <div class="flex">
                                <label>{{ __('Attributes') }}</label>
                                <button type="button" wire:click="addAttribute" class="ml-5"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                            @foreach ($clAttributes as $key => $attribute)
                                <div wire:key="attribute-{{ $key }}" class="relative">
                                    <input type="text" wire:model="clAttributes.{{ $key }}" class="w-full mt-5 border border-gray-200 rounded" />
                                    <div wire:click="deleteAttribute({{ $key }})" class="absolute text-gray-300 cursor-pointer right-2 bottom-2 hover:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex flex-col col-span-12 sm:col-span-6">
                            <div class="flex">
                                <label>{{ __('Global Attributes') }}</label>
                                <button type="button" wire:click="addGlobalAttribute" class="ml-5"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                            @foreach ($globalAttributes as $key => $attribute)
                                <div wire:key="global-{{ $key }}" class="relative">
                                    <input type="text" wire:model="globalAttributes.{{ $key }}" class="w-full mt-5 border border-gray-200 rounded" />
                                    <div wire:click="deleteGlobalAttribute({{ $key }})" class="absolute text-gray-300 cursor-pointer right-2 bottom-2 hover:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-span-12 col-start-1 mt-10 text-right">
                            <button type="button" wire:click="save" class="px-2 py-1 text-white bg-green-500 hover:bg-green-700">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
