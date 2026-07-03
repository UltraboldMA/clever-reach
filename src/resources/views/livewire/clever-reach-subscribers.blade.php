<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('CleverReach Subscribers') }}

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
                <select wire:model.live="selectedNewsletter" class="border border-gray-200 rounded-l">
                    <option value="">{{ __('Newsletter auswählen') }}</option>
                    @foreach ($availableNewsletters as $nl)
                        <option value="{{ $nl->key }}">{{ $nl->name }}</option>
                    @endforeach
                </select>
                <button type="button" wire:click="loadSubscribers" class="px-2 py-1 text-white bg-green-400 rounded-r hover:bg-green-600">{{ __('Load') }}</button>
                <select wire:model.live="selectedGroup" class="ml-5 border border-gray-200 rounded-l">
                    <option value="">{{ __('Gruppe auswählen') }}</option>
                    @foreach ($availableGroups as $group)
                        <option value="{{ $group->external_id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                <button type="button" wire:click="loadSubscribers" class="px-2 py-1 text-white bg-green-400 rounded-r hover:bg-green-600">{{ __('Load') }}</button>
                <div role="status" class="mt-1 ml-5" wire:loading>
                    @include('clever-reach::components.spinner')
                </div>
            </div>
            @if (!empty($subscribers))
                <table class="w-full">
                    <thead>
                        <th class="py-1 text-left">ID</th>
                        <th class="py-1 text-left">Email</th>
                        <th class="py-1 text-left">{{ __('Activated') }}</th>
                        <th class="py-1 text-left">{{ __('Registered') }}</th>
                        <th class="py-1 text-left">{{ __('Active') }}</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($subscribers as $key => $subscriber)
                            <tr>
                                <td>{{ Arr::get($subscriber, 'id') }}</td>
                                <td>{{ Arr::get($subscriber, 'email') }}</td>
                                <td>{{ Arr::get($subscriber, 'registered') }}</td>
                                <td>{{ Arr::get($subscriber, 'activated') }}</td>
                                <td>
                                    @if (Arr::get($subscriber, 'active'))
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </td>
                                <td>
                                    <button wire:key="show-{{ $key }}" type="button" wire:click="showSubscriber({{ $key }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button wire:key="delete-{{ $key }}" type="button" wire:click="deleteConfirmSubscriber({{ $key }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex justify-between">
                    <div class="w-1/3">
                        @if ($page > 0)
                            <button type="button" wire:click="previousPage">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                </svg>
                            </button>
                        @endif
                    </div>
                    <div class="w-1/3 text-center">
                        {{ __('Page') }} {{ $page + 1 }}
                    </div>
                    <div class="w-1/3 text-right">
                        @if (count($subscribers) == $pageSize)
                            <button type="button" wire:click="nextPage">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="pt-5">
                    {{ __('No subscribers. List is empty or please select a new list and hit "Load".') }}
                </div>
            @endif
        </div>
    </div>
    {{-- Modal --}}
    @if ($currentSubscriber && !$showConfirm)
        <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full py-5 bg-black bg-opacity-50">
            <div class="w-full max-w-4xl max-h-full overflow-y-auto bg-white sm:rounded-2xl">
                <div class="w-full">
                    <div class="p-10">
                        <div class="mb-8">
                            <h1 class="mb-4 text-2xl font-extrabold">ID: {{ Arr::get($currentSubscriber, 'id') }}</h1>
                            <h4 class="mt-5 text-lg">{{ __('Global Attributes') }}</h4>
                            <div class="flex flex-col">
                                @foreach (Arr::get($currentSubscriber, 'global_attributes', []) as $key => $attribute)
                                    <div>
                                        <strong>{{ ucfirst($key) }}:</strong> {{ $attribute }}
                                    </div>
                                @endforeach
                            </div>
                            <h4 class="mt-5 text-lg">{{ __('Attributes') }}</h4>
                            <div class="flex flex-col">
                                @foreach (Arr::get($currentSubscriber, 'attributes', []) as $key => $attribute)
                                    <div>
                                        <strong>{{ ucfirst($key) }}:</strong> {{ $attribute }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-right">
                            <button wire:click="closeSubscriber" class="px-2 py-1 font-semibold text-white bg-black border border-black rounded-full hover:bg-white hover:text-black">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($showConfirm)
        <div class="fixed top-0 left-0 flex items-center justify-center w-full h-full py-5 bg-black bg-opacity-50">
            <div class="w-full max-w-4xl max-h-full overflow-y-auto bg-white sm:rounded-2xl">
                <div class="w-full">
                    <div class="p-10">
                        <div class="mb-8">
                            <h1 class="mb-4 text-2xl font-extrabold">{{ __('Delete') }}: {{ Arr::get($currentSubscriber, 'id') }}</h1>
                            <p>{{ __('Are you sure you want to delete this subscriber?') }}</p>
                        </div>
                        <div class="text-right">
                            <button wire:click="cancel" class="px-2 py-1 font-semibold text-white bg-black border border-black rounded-full hover:bg-white hover:text-black">{{ __('Cancel') }}</button>
                            <button wire:click="deleteSubscriber" class="px-2 py-1 font-semibold text-white bg-red-400 border border-red-400 rounded-full hover:bg-white hover:text-black">{{ __('Delete') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
