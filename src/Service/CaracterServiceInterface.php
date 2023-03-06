<?php

namespace App\Service;
use App\Entity\Caracter;

interface CaracterServiceInterface
{
   
    public function create(string $data);

    # Checks if the entity has been well filled
    public function isEntityFilled(Caracter $character);

    # Submits the data to hydrate the object
    public function submit(Caracter $character, $formName, $data);

    # Finds all the characters
    public function findAll();

    public function modify(Caracter $character,string $data);

    public function delete(Caracter $character);
}