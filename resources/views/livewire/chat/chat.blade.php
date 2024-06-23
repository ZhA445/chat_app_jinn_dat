<div class="h-100 container-fluid d-flex justify-content-center align-items-center">
    <div class="w-100 h-100 d-flex bg-white border rounded row">

        <div class="relative col-4 d-none d-lg-block flex-shrink-0 h-100 border">
            <livewire:chat.chat-list :selectedConversation="$selectedConversation" :query="$query">
        </div>

        <div class="h-100 col-xl-8 col-lg-8 col-md-12 border-left d-flex flex-column align-items-center justify-content-center ">
            <livewire:chat.chat-box :selectedConversation="$selectedConversation">
        </div>

    </div>
</div>
