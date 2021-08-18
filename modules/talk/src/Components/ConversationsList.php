<?php

namespace Talk\Components;

use App\Models\User;
use Illuminate\View\Component;

class ConversationsList extends Component
{
    private $data = [];
    
    public $conversation = null;

    public function __construct($conversation)
    {
        $this->conversation = $conversation;
        
        $this->user = auth()->user();

        $conversations = $this->user->participated_conversations;

        $this->data = [
            'user' => $this->user,
            'conversations' => $conversations,
        ];
    }

    public function render()
    {
        return view('talk::components.conversations.list', $this->data);
    }
}