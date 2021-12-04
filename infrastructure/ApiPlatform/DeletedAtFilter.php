<?php

namespace Infrastructure\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

class DeletedAtFilter extends AbstractFilter
{
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $alias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf("%s.deletedAt is null", $alias));
    }

    public function getDescription(string $resourceClass): array
    {
        return [];
    }
}
