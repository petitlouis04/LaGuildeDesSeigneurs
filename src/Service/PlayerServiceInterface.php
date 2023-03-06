<?php

namespace App\Service;
use App\Entity\Player;

interface PlayerServiceInterface
{
    public function createPlayer(string $data);

    # Checks if the entity has been well filled
    public function isEntityFilled(Player $player);

    # Submits the data to hydrate the object
    public function submit(Player $player, $formName, $data);

    # Finds all the characters
    public function findAll();

    public function modify(Player $player,string $data);

    //public function delete(Player $player);
}