<div class="container-fluid h-100 overflow-hidden d-flex justify-content-center align-items-center">
    <div class="w-100 h-100 d-flex bg-white border rounded row">

        <div class="relative col-4 d-none d-lg-block border-bottom mb-2 flex-shrink-0 h-100 border">
            <livewire:chat.chat-list :selectedConversation="$selectedConversation" :query="$query">
        </div>

        <div class="h-100 col-xl-8 col-lg-8 col-md-12 border-left border-bottom mb-2 d-flex flex-column align-items-center justify-content-center ">
            <h4 class="fs-3">Choose a conversation to start chatting</h4>
        </div>

    </div>
</div>
