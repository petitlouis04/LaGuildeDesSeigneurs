<?php

namespace App\Service;
use App\Entity\Player;

interface PlayerServiceInterface
{
   
    //public function create();

    # Finds all the characters
    public function findAll();

    public function createPlayer();

    //public function modify(Player $player);

    //public function delete(Player $player);
}