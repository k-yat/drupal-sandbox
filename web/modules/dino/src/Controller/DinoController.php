<?php

namespace Drupal\dino\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dino\Jurassic\RoarGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class DinoController extends ControllerBase
{
    /**
     * @var RoarGenerator
     */
    private $roarGenerator;

    function __construct(RoarGenerator $roarGenerator)
    {
        $this->roarGenerator = $roarGenerator;
    }

    public function content($count)
    {
        $roar = $this->roarGenerator->roar($count);
        return new Response($roar);
    }

    public static function create(ContainerInterface $container)
    {
        $roarGenerator = $container->get('dino.roar_generator');
        return new static($roarGenerator);
    }
}
