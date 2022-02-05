<?php

namespace Talk\Components;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;
use Talk\Models\Conversation;

class ConversationsList extends Component
{
    private $data = [];
    
    public $conversation = null;

    public function __construct($conversation)
    {
        $this->conversation = $conversation;
        
        $this->user = auth()->user();

        $conversations = $this->user->participated_conversations()->with([
            'users', 
            'receipts' => function ($query) {
                $query->where('user_id', $this->user->id);
            }, 
            'replies' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'conversationable',
        ])->orderBy('updated_at', 'desc')->get();

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