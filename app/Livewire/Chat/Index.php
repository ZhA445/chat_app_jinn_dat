<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;

class Index extends Component
{
    public $count = 0;
    public $query;
    public $selectedConversation;

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {
        return view('livewire.chat.index');
    }
}
