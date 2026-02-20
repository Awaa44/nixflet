<?php

namespace App\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class SlashTransformer implements DataTransformerInterface
{

    public function transform(mixed $value): mixed
    {
        //si pas de valeur alors tableau vide
        if(!$value){
            return [];
        }

        return explode('/', $value);
    }

    public function reverseTransform(mixed $value): mixed
    {
        //si pas de valeur alors tableau vide
        if(!$value){
            return null;
        }

        return implode('/', $value);
    }
}
