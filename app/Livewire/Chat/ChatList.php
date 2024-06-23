<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Exception;
use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;

    public $loadedMessages;
    public $query;

    protected $listeners = ['refreshChatList'=> '$refresh'];

    public function getListeners(){
        $auth_id = auth()->user()->id;

        return[
            'refreshChatList',
            "echo:private-users.{$auth_id},.App\\Events\\MessageSent" => 'handleMessageSent',
            "echo:private-users.{$auth_id},.App\\Events\\MessageRead" => 'handleMessageRead',
        ];
    }

    public function handleMessageRead($event){
        if ($event['conversation_id'] == $this->selectedConversation->id) {
            // dd($event);
        }
    }

    public function deleteByUser($id){

        $userId = auth()->id();
        $conversation = Conversation::find(decrypt($id));

        $conversation->messages()->each(function($message) use($userId){

            if($message->sender_id == $userId){
                $message->update(['sender_deleted_at'=>now()]);
            }elseif($message->receiver_id == $userId){
                $message->update(['receiver_deleted_at'=>now()]);
            }

        });

        $receiverAlsoDeleted = $conversation->messages()
                                ->where(function($query) use($userId){
                                    $query->where('sender_id',$userId)
                                        ->orWhere('receiver_id',$userId);
                                })->where(function($query) use($userId){
                                    $query->whereNull('sender_deleted_at')
                                        ->orWhereNull('receiver_deleted_at');
                                })->doesntExist();

        if($receiverAlsoDeleted){
            $conversation->forceDelete();
        }

        return redirect(route('chat.index'));
    }

    public function render()
    {
        $user = auth()->user();
        $sentConversations = $user->sentConversations()->latest('updated_at')->get();
        $receivedConversations = $user->receivedConversations()->latest('updated_at')->get();

        $conversations = $sentConversations->merge($receivedConversations)->sortByDesc('updated_at');

        return view('livewire.chat.chat-list',[
            'conversations'=> $conversations,
            'sentConversations' => $sentConversations
        ]);
    }
}
