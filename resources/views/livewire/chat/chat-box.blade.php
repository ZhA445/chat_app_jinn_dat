<div x-data="{ height: 0, conversationElement: document.getElementById('conversation') , markAsRead:null}" x-init="height = conversationElement.scrollHeight,
            $nextTick(() => conversationElement.scrollTop = height)
"
    @scroll-bottom.window="
    $nextTick(()=>conversationElement.scrollTop=conversationElement.scrollHeight)
    "

    @new-message-noti.window="
    markAsRead=true
    {{-- alert('read true') --}}
    "
    class=" border-bottom d-flex flex-column overflow-y-scroll flex-grow w-100 h-100">

    {{-- header  --}}
    <header class="w-100 sticky d-flex z-10 bg-white border-bottom">
        <div class="d-flex w-100 align-items-center px-2 py-2">
            <a href="" class=" flex-shrink-0 d-lg-none text-black me-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                </svg>
            </a>

            {{-- avatar  --}}

            <div class=" flex-shrink-0 me-3">
                <x-avatar style="width: 40px; height: 40px;"></x-avatar>
            </div>

            <h6 class=" font-semibold truncate">{{ $selectedConversation->getReceiver()->email }}</h6>

        </div>
    </header>
    {{-- @scroll="
    scropTop = $el.scrollTop;

    if(scropTop <= 0){
        window.livewire.emit('loadMore');
    } "  --}}
    {{-- body
    @update-chat-height.window="
        newHeight = $el.scrollHeight;
        oldHeight = height;

        $el.scrollTop = newHeight-oldHeight;
        height= newHeight;

        " --}}

    <main
        @scroll="
        scropTop = $el.scrollTop;
        if(scropTop <= 0){
            $wire.dispatchSelf('loadMore');
        }
        "
        @update-chat-height.window="
        newHeight = $el.scrollHeight;
        oldHeight = $el.scrollTop;
            $el.scrollTop = newHeight-oldHeight;
            height= newHeight;
        "
        id="conversation"
        class="d-flex flex-column align-self-start gap-3 pt-2 overflow-y-auto overflow-x-hidden w-100 h-100 my-auto">

        @if ($loadedMessages)

            @php
                $previousMessage = null;

            @endphp

            @foreach ($loadedMessages as $key => $message)
                {{-- keep track of the previous message   --}}

                @if ($key > 0)
                    @php
                        $previousMessage = $loadedMessages->get($key + 1);
                    @endphp
                @endif

                <div
                wire:key = "{{time().$key}}"
                    class="w-100 bg-gray py-1 px-2 d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : '' }}">

                    {{-- avatar --}}
                    @if ($message->sender_id !== auth()->id())
                        <div @class([
                            'flex-shrink-0',
                            'me-2',
                            'invisible' => $previousMessage?->sender_id == $message->sender_id,
                        ])>
                            <x-avatar style="width: 30px; height: 30px;"></x-avatar>
                        </div>
                    @endif

                    {{-- message body --}}
                    <div class="bg-primary text-white p-1 px-2 w-auto rounded max-width-75 {{ $message->sender_id !== auth()->id() ? 'bg-secondary' : '' }}"
                        style="max-width: 70%">

                        <p class="me-3">
                            {{ $message->body }}
                        </p>

                        <div class=" float-end d-flex align-items-center ms-auto">
                            <small>{{ $message->created_at->format('g:i a') }}</small>

                            @if ($message->sender_id == auth()->id())
                                <div x-data="{markAsRead:@json($message->isRead())}">
                                    {{-- @if ($message->isRead()) --}}
                                        {{-- double click --}}
                                        <svg x-cloak x-show="markAsRead" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                            <path
                                                d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0" />
                                            <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708" />
                                        </svg>
                                    {{-- @else --}}
                                        {{-- single click --}}
                                        <svg x-show="!markAsRead" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                            <path
                                                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0" />
                                        </svg>
                                    {{-- @endif --}}

                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            @endforeach
        @endif


    </main>

    {{-- footer  --}}

    <footer class=" flex-shrink-1 z-10 bg-white inset-x-0">

        <div class="p-2 border-top overflow-hidden">

            <form action="#" method="post" class="py-2" @submit.prevent="$wire.sendMessage"
                x-data="{ body: @entangle('body') }">
                @csrf

                <input type="hidden" autocomplete="false" class="d-none">

                <div class="row w-100 mx-auto">
                    <input x-model="body" id="body" type="text" autocomplete="false" autofocus
                        placeholder="write your messge here" maxlength="1700" class="col-10 col-lg-11 col-md-10 col-sm-10 py-2">
                    <button x-bind:disabled="!body.trim()"
                        class="col-2 col-lg-1 col-md-2 col-sm-2 border border-black border-start-0 mx-auto d-flex justify-content-center align-items-center cursor-pointer"
                        type="submit" id="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50%" height="50%" fill="currentColor"
                            class="bi bi-send" viewBox="0 0 16 16">
                            <path
                                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                        </svg>
                    </button>
                </div>

            </form>

            @error('body')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

    </footer>
    <script>
        const btn = document.getElementById('btn');

        btn.addEventListener('click',function handleClick(event){

            const input = document.getElementById('body');

            input.value = '';
        })
    </script>

</div>
