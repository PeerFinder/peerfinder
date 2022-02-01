<?php

namespace Talk;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matcher\Models\Peergroup;
use Talk\Models\Conversation;
use Talk\Models\Receipt;
use Talk\Models\Reply;
use Illuminate\Support\Str;
use Talk\Events\UnreadReply;

class Talk
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function filterUsers($users, $minUsers = 1)
    {
        $currentUser = auth()->user();
        
        if (count($users) > $minUsers && $currentUser) {
            $users = array_filter($users, fn ($user) => $currentUser->id != $user->id);
        }

        $users = array_values($users);

        return $users;
    }

    public function filterUsersForConversation($conversation)
    {
        return $this->filterUsers($conversation->users->all());
    }

    public function usersAsString($users, $with_links = false)
    {
        if ($with_links) {
            return implode(", ", array_map(fn ($user) => '<a href="' . $user->profileUrl() . '">' . htmlspecialchars($user->name) . '</a>', $users));
        }

        return implode(", ", array_map(fn ($user) => $user->name, $users));
    }

    public function createReply(Conversation $conversation, User $user, $input)
    {
        Validator::make($input, Reply::rules()['create'])->validate();

        $reply = new Reply();

        if (key_exists('reply', $input)) {
            $parent = Reply::whereIdentifier($input['reply'])->whereConversationId($conversation->id)->firstOrFail();
            $reply->reply()->associate($parent);
        }

        $reply->conversation()->associate($conversation);
        $reply->user()->associate($user);
        $reply->message = key_exists('reply_message', $input) ? $input['reply_message'] : $input['message'];
        $reply->save();

        $conversation->touch();

        foreach ($conversation->users as $u) {
            if ($u->id != $user->id) {
                $receipt = Receipt::where(['user_id' => $u->id, 'conversation_id' => $conversation->id]);

                if (!$receipt->exists()) {
                    $receipt = new Receipt();
                    $receipt->user_id = $u->id;
                    $receipt->conversation_id = $conversation->id;
                } else {
                    $receipt = $receipt->first();
                }

                $receipt->reply_id = $reply->id;
                $receipt->save();
            }
        }

        return $reply;
    }

    public function createConversation($owner, $participants, $input)
    {
        $conversation = null;

        Validator::make($input, Conversation::rules()['create'])->validate();
        Validator::make($input, Reply::rules()['create'])->validate();

        if ($owner instanceof User) {
            $conversation = $owner->owned_conversations()->create($input);
        }

        if ($conversation) {
            foreach ($participants as $participant) {
                $conversation->addUser($participant);
            }

            $this->createReply($conversation, $owner, $input);
        }

        return $conversation;
    }

    public function cleanupForUser(User $user)
    {
        $user->receipts()->each(function ($receipt) {
            $receipt->delete();
        });

        if ($user->anonymous_replies) {
            $user->replies()->each(function ($reply) {
                $reply->user_id = null;
                $reply->save();
            });
        } else {
            $user->replies()->each(function ($reply) {
                $reply->delete();
            });            
        }

        $user->participated_conversations()->detach();

        $user->owned_conversations()->each(function ($conversation) {
            $conversation->delete();
        });
    }

    public function checkConversationCreation($users)
    {
        if (!$users || count($users) < 1) {
            return redirect(route('talk.index'));
        }

        $users[] = auth()->user();

        $conversation = Conversation::forUsers($users)->get()->first();

        if ($conversation) {
            return redirect($conversation->getUrl());
        }

        return null;
    }

    public function userHasUnreadConversations(User $user)
    {
        return $user->receipts->count() > 0;
    }

    public function getRecentUnreadConversationForUser(User $user)
    {
        $receipt = $user->receipts->first();

        if ($receipt) {
            return $receipt->conversation;
        } else {
            return null;
        }
    }

    public function embedConversation(Conversation $conversation)
    {
        $conversation->markAsRead();

        $last_reply = $conversation->replies()->orderByDesc('created_at')->first('identifier');

        return view('talk::conversations.embedded.show', [
            'conversation' => $conversation,
            'replies' => $this->repliesTree($conversation),
            'highlighted_reply' => $last_reply ? $last_reply->identifier : null,
        ]);
    }

    public function dynamicConversationsUrl($user)
    {
        if ($this->userHasUnreadConversations($user)) {
            $conversation = $this->getRecentUnreadConversationForUser($user);
            return $conversation->getUrl();
        } else {
            return route('talk.index');
        }
    }

    public function formatDateTime($datetime)
    {
        if ($this->user && $this->user->timezone) {
            $datetime->setTimezone($this->user->timezone);
        }

        return $datetime->format('H:i - d.m.y');
    }

    public function renderReplyMessage($raw_message)
    {
        $html = Str::of($raw_message)->markdown([
            'html_input' => 'strip',
            'renderer' => [
                'soft_break' => "<br>\n",
            ]
        ]);

        $html = trim($html);

        return $html;
    }

    private function traverseRepliesTree($replies, $callback)
    {
        $replies->each(function($reply) use($callback) {
            $callback($reply);
            $this->traverseRepliesTree($reply->replies, $callback);
        });
    }

    public function repliesTree(Conversation $conversation)
    {
        $user_cache = [];

        $top_replies = $conversation->getReplies();

        $this->traverseRepliesTree($top_replies, function ($reply) use (&$user_cache) {
            if ($reply->user_id) {
                $user_cache[$reply->user_id] = null;
            }
        });

        $users = User::whereIn('id', array_keys($user_cache))->get();

        $users->each(function($user) use (&$user_cache) {
            $user_cache[$user->id] = $user;
        });

        $this->traverseRepliesTree($top_replies, function ($reply) use ($user_cache) {
            if ($reply->user_id) {
                $reply->user = $user_cache[$reply->user_id];
            }
        });

        return $top_replies;
    }

    /**
     * Gets existing receipts without mail notification. From the oldest to the newest.
     * 
     * @param mixed $min_age Minimum age in minutes
     * @param int $limit How many receipts shall be processed during a call
     * @return mixed list of receipts
     */
    public function getUnreadReceipts($min_age, $limit = 0)
    {
        $receiptsQuery = Receipt::where('last_mail_notification', null)
                            ->where('created_at', '<', now()->subMinutes($min_age))
                            ->with(['conversation', 'user'])
                            ->orderBy('created_at', 'asc');
        
        if ($limit > 0) {
            $receiptsQuery->limit($limit);
        }

        return $receiptsQuery->get();
    }

    public function sendNotificationsForReceipts()
    {
        $min_age = config('talk.min_receipt_age');
        $limit = config('talk.receipts_batch_limit');

        $receipts = $this->getUnreadReceipts($min_age, $limit);

        foreach ($receipts as $receipt) {
            $receipt->last_mail_notification = now();
            $receipt->save();

            UnreadReply::dispatch($receipt);
        }
    }
}
