<?php

namespace Drupal\dino\Jurassic;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DinoListener implements EventSubscriberInterface
{
    /**
     * @var LoggerChannelFactoryInterface
     */
    private $loggerFactory;

    function __construct(LoggerChannelFactoryInterface $loggerFactory)
    {
        $this->loggerFactory = $loggerFactory;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $shouldRoar = $request->get('roar');

        if($shouldRoar){
            $this->loggerFactory->get('default')
                ->debug('Rooooar from the request');
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }
}
