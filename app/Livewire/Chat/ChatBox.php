<?php

namespace App\Livewire\Chat;

use App\Models\Message;
// use App\Notifications\MessageSent;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body = '';

    public $loadedMessages;

    public $paginate_var = 15;

    protected $listeners = [
        'loadMore',
        'refreshChatBox'=>'$refresh',
    ];


    // $auth_id = auth()->user()->id;
    public function getListeners()
    {
        $auth_id = auth()->user()->id;
        return [
            'loadMore',
            'refreshChatBox',
            "echo:private-users.{$auth_id},.App\\Events\\MessageSent" => 'broadcastedNotifications',
            "echo:private-users.{$auth_id},.App\\Events\\MessageRead" ,
        ];
    }

    public function broadcastedNotifications($event)
    {
        // dd($event);

        if ($event['conversation_id'] == $this->selectedConversation->id) {
            $this->dispatch('scroll-bottom');

            $newMessage = Message::find($event['message_id']);

            $this->loadedMessages->push($newMessage);

            //mark as read

            $newMessage->read_at = now();
            $newMessage->save();

            $this->dispatch('refreshChatList');

            $this->dispatch('refreshChatBox');
            //broadcast

            broadcast(
                new \App\Events\MessageRead(
                    $this->selectedConversation->id,
                    $newMessage,
                    $event['user_id']
                )
            );

            $this->dispatch('new-message-noti');
        }
    }



    public function loadMore(): void
    {
        //increment
        $this->paginate_var += 10;

        //call loadMessages()
        $this->loadMessages();

        //update chat height

        $this->dispatch('update-chat-height');


    }


    public function loadMessages()
    {
        $count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->skip($count - $this->paginate_var)
            ->take($this->paginate_var)
            ->get();


        return $this->loadedMessages;
    }


    public function sendMessage()
    {
        $this->validate(['body' => 'required|string|max:1700']);

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body,
        ]);


        // $this->reset('body');
        //scroll-bottom


        //push the message

        $this->loadedMessages->push($createdMessage);

        //update conversation model

        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        // $this->dispatchBrowserEvent('scroll-bottom');
        $this->dispatch('scroll-bottom');
        //refresh chatlist

        $this->dispatch('refreshChatList');

        //broadcast

        // $this->selectedConversation->getReceiver()
        //     ->notify(new MessageSent(
        //         Auth()->user(),
        //         $createdMessage,
        //         $this->selectedConversation,
        //         $this->selectedConversation->getReceiver()->id,
        //     ));
        broadcast(
            new \App\Events\MessageSent(
                auth()->user(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id,
            )
        );



    }

    public function mount($selectedConversation)
    {
        $this->selectedConversation = $selectedConversation;
        $this->loadMessages();
        $this->dispatch('scroll-bottom');

    }



    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
