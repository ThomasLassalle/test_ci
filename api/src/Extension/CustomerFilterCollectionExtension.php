<?php

declare(strict_types=1);

namespace App\Extension;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\MacDonaldOrder;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class CustomerFilterCollectionExtension implements QueryCollectionExtensionInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * CustomerFilterCollectionExtension constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== MacDonaldOrder::class
            || (null === $user = $this->security->getUser())
            || $this->security->isGranted('ROLE_ADMIN', $user)
        ) {
            return;
        }

        // Ajouter une clause where pour limiter les commandes à l'utilisateur connecté
        // utilisateur connecté
        $alias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere("$alias.customer = :customer");
        $queryBuilder->setParameter('customer', $user);
    }
}
