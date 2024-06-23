<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable=[
        'receiver_id',
        'sender_id',
    ];

    // public function getListeners(){
    //     $auth_id = auth()->user()->id;

    //     return[
    //         "echo:private-users.{$auth_id},.App\\Events\\MessageSent" => 'handleMessageRead',
    //     ];
    // }

    // public function handleMessageRead($event){
    //     dd($event);
    // }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getReceiver()
    {

        if($this->sender_id === auth()->id()){
            return User::firstWhere('id',$this->receiver_id);
        }
        else{
            return User::firstWhere('id',$this->sender_id);
        }

    }

    public function scopeWhereNotDeleted($query){
        $userId = auth()->id();

        return $query->where(function($query) use($userId){
            $query->whereHas('messages',function($query) use($userId){
                $query->where(function($query) use($userId){
                    $query->where('sender_id',$userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function($query) use($userId){
                    $query->where('receiver_id',$userId)
                        ->whereNull('receiver_deleted_at');
                });
            })->orWhereDoesntHave('messages');
        });
    }

    public function unreadMessagesCount() : int{
        return $unreadMessages = Message::where('conversation_id',$this->id)
                                    ->where('receiver_id',auth()->user()->id)
                                    ->whereNull('read_at')->count();
    }


    // public function receivesBroadcastNotificationsOn(): string
    // {
    //     return 'conversations.'.$this->id;
    // }
}
