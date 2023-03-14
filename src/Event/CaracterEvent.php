<?php

namespace App\Event;

use App\Entity\Caracter;
use Symfony\Contracts\EventDispatcher\Event;

class CaracterEvent extends Event
{
    // Constante pour le nom de l'event, nommage par convention
    public const CHARACTER_CREATED = 'app.character.created';
    public const CHARACTER_MODIFIED = 'app.character.modified';
    public const CHARACTER_CREATED_POST_DATABASE = 'app.character.created.post.database';
    // Injection de l'objet

    private Caracter $character;
    public function __construct(
        Caracter $character
    ) {
        $this->character = $character;
    }
    // Getter pour l'objet
    public function getCharacter(): Caracter
    {
        return $this->character;
    }
}
