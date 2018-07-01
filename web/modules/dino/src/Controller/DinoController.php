<?php

namespace Drupal\dino\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class DinoController extends ControllerBase
{
    public function content($count)
    {
        return new Response('hello from din' . str_repeat('o', $count) . '!');
    }
}
