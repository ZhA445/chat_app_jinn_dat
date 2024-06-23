<div x-data="{ type: 'all', query: @entangle('query') }"
    x-init = "
    setTimeout(()=>{
        conversationElement = document.getElementById('conversation-'+query);

        //scroll to element

        if(conversationElement){
        conversationElement.scrollIntoView({'behavior':'smooth'})
        }
    }),200;
"
    class="d-flex flex-column h-100 overflow-hidden">

    <header class="px-3 z-10 bg-white sticky top-0 w-100 py-2">
        <div class="border-bottom justify-content-between rounded d-flex align-items-center p-3">

            <div class="d-flex align-items-center g-2">
                <h5 class=" font-semibold fs-5">Chats</h5>
            </div>

            <button class="border-1 border-opacity-10 bg-white h-auto align-items-center">
                <svg class="bi bi-list" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg>
            </button>

        </div>
        {{-- Filters  --}}

        <div class="d-flex gap-3 align-items-center p-3 bg-white">
            <button @click="type='all'" :class="{ 'bg-primary-subtle border-0': type == 'all' }"
                class="inline-flex justify-content-center fs-6 btn border-secondary rounded-pill gap-x-1 text-xl px-lg-4 px-3 py-lg-2 py-1">
                All
            </button>
            <button @click="type='deleted'" :class="{ 'bg-primary-subtle border-0': type == 'deleted' }"
                class="inline-flex justify-content-center fs-6 btn border-secondary  rounded-pill gap-x-1 text-xl px-lg-4 py-lg-2 px-3 py-1">
                Deleted
            </button>
        </div>

    </header>

    <main class=" overflow-y-scroll overflow-hidden flex-grow h-100 relative" style="contain: content"
        x-data="{ hovered: null }">
        <ul class="col text-center list-group h-100">

            @if ($conversations)
                @foreach ($conversations as $key => $conversation)
                    {{-- @mouseover="hovered=1" @mouseleave="hovered=null"
                    :class="{ 'bg-light cursor-pointer': hovered === 1 }" --}}
                    <li id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}"
                        class="row mb-2 rounded d-flex border px-1 py-2 list-group-item {{ $conversation->id == $selectedConversation?->id ? 'bg-body-secondary' : '' }}">
                        <a href="{{ route('chat.chat', $conversation->id) }}"
                            class="d-flex justify-content-between text-decoration-none row-12 col-11">
                            <div class="float-star col-2">
                                <x-avatar style="width: 50px; height: 50px;"
                                    src="https://cdn.pixabay.com/photo/2017/08/06/21/01/louvre-2596278_960_720.jpg"></x-avatar>
                            </div>

                            <aside class="col-10 row d-flex">
                                {{-- <a href=""></a> --}}
                                <div class="col-12">

                                    {{-- name and date  --}}
                                    {{-- <small>{{$conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans()}}</small> --}}

                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <h5 class="mb-0 font-semibold">{{ $conversation->getReceiver()->name }}</h5>
                                        @if ($conversation->updated_at->shortAbsoluteDiffForHumans() < 1)
                                            <small>1s</small>
                                        @else
                                            <small>{{ $conversation->updated_at->shortAbsoluteDiffForHumans() }}</small>
                                        @endif

                                    </div>

                                    {{-- Message  --}}

                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="mb-0 text-truncate pe-4">
                                            @if ($conversation->messages?->last()?->sender_id == auth()->id())
                                                You:
                                            @endif
                                            {{ $conversation->messages?->last()?->body ?? '' }}</p>

                                        {{-- unread count --}}
                                        @if ($conversation->unreadMessagesCount() > 0)
                                            <span class=" text-end bg-primary rounded-circle px-2 text-white">{{$conversation->unreadMessagesCount()}}</span>
                                        @endif
                                    </div>
                                </div>

                            </aside>
                        </a>
                        <div class="col-1 dropdown z-10  align-items-center text-start d-flex border-0">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">

                                    <button type="button">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-three-dots-vertical"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                        </svg>
                                    </button>

                                </x-slot>

                                <x-slot name="content">

                                    <div class="w-100 z-100" class="dropdown-menu">
                                        <button
                                            class="text-center gap-3 d-flex w-100 px-4 py-2 text-left text-sm leading-5 text-gray-500 dropdown-item">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                    height="16" fill="currentColor" class="bi bi-person"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                                                </svg>
                                            </span>

                                            view Profile
                                        </button>
                                        <button
                                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                            wire:click="deleteByUser('{{encrypt($conversation->id)}}')"
                                            class="text-center gap-3 d-flex w-100 px-4 py-2 text-left text-sm leading-5 text-gray-500 dropdown-item">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                    height="16" fill="currentColor" class="bi bi-trash3"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </span>

                                            Delete
                                        </button>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </li>
                @endforeach
                @else
                <li class="list-group-item text-center">No Conversations</li>
            @endif

        </ul>
    </main>

</div>
