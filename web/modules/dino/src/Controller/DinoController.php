<?php

namespace Drupal\dino\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\dino\Jurassic\RoarGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class DinoController extends ControllerBase
{
    /**
     * @var RoarGenerator
     */
    private $roarGenerator;

    /**
     * @var LoggerChannelFactoryInterface
     */
    protected $loggerFactory;

    function __construct(RoarGenerator $roarGenerator, LoggerChannelFactoryInterface $loggerFactory)
    {
        $this->roarGenerator = $roarGenerator;
        $this->loggerFactory = $loggerFactory;
    }

    public function content($count)
    {
        $roar = $this->roarGenerator->roar($count);
        $this->loggerFactory->get('default')->debug($roar);
        return new Response($roar);
    }

    public static function create(ContainerInterface $container)
    {
        $roarGenerator = $container->get('dino.roar_generator');
        $loggerFactory = $container->get('logger.factory');
        return new static($roarGenerator, $loggerFactory);
    }
}
