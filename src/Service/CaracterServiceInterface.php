<?php

namespace App\Service;
use App\Entity\Caracter;

interface CaracterServiceInterface
{
   
    public function create();

    # Finds all the characters
    public function findAll();

    public function modify(Caracter $character);

    public function delete(Caracter $character);
}