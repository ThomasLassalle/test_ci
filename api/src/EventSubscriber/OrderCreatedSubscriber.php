<?php

declare(strict_types=1);

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\MacDonaldOrder;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class OrderCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendLog', EventPriorities::POST_WRITE]
        ];
    }

    public function sendLog(ViewEvent $event)
    {
        $object = $event->getControllerResult();

        if (!$object instanceof MacDonaldOrder) {
            return;
        }

        $this->logger->info('Order Created', ['order' => $object]);
    }
}
