<?php

namespace App\Traits;

use App\Repository\ChatRepository;

trait HasChatRepository
{
    /**
     * @var ChatRepository;
     */
    private $chatRepository;

    /**
     * @required
     */
    public function setChatRepository(ChatRepository $chatRepository): void
    {
        $this->chatRepository = $chatRepository;
    }
}