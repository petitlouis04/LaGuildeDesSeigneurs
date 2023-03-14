<?php

namespace App\Event;

use App\Entity\Player;
use Symfony\Contracts\EventDispatcher\Event;

class PlayerEvent extends Event
{
    public const PLAYER_MODIFIED = 'app.player.modified';
    public const PLAYER_CREATED = 'app.player.created';
    public const PLAYER_CREATED_POST_DATABASE = 'app.player.created.post.database';

    protected Player $player;
    public function __construct(
        Player $player
    ) {
        $this->player = $player;
    }
    public function getPlayer(): Player
    {
        return $this->player;
    }
}
