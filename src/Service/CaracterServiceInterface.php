<?php

namespace App\Service;


interface CaracterServiceInterface
{
   
    public function create();

    # Finds all the characters
    public function findAll();
}