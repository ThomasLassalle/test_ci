<?php

declare(strict_types=1);

namespace App\Handlers;


use App\Entity\Customer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ResetPasswordHandler implements MessageHandlerInterface
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * ResetPasswordHandler constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Customer $customer)
    {
        $this->logger->warning('Changement de mot de passe', ['customer' => $customer]);
    }
}
