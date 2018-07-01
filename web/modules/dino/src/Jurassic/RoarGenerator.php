<?php

    namespace Drupal\dino\Jurassic;

class RoarGenerator
{
    public function roar($length)
    {
        return 'hello from din' . str_repeat('o', $length) . '!';
    }
}
