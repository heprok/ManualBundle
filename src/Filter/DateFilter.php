<?php

namespace Tlc\ManualBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use DateTime;
use Doctrine\ORM\QueryBuilder;

final class DateFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        // otherwise filter is applied to order and page as well
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass)
            ) {
                return;
            }
        $dates = explode('...', $value);
        $start = new DateTime($dates[0]);
        $end = new DateTime($dates[1]);

        if(count($dates) != 2 && !$start && !$end) 
            return;

        $queryBuilder
            ->andWhere(sprintf('o.%s BETWEEN :start AND :end', $property ))
            ->setParameter('start', $start->format(DATE_ATOM))
            ->setParameter('end', $end->format(DATE_ATOM));

        // dump($queryBuilder->getQuery()->getResult());
    }

    protected function splitPropertyParts(string $property): array
    {
        // dd($property);
        return [];
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["$property"] = [
                'property' => $property,
                'type' => 'array',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter using a dates',
                    'name' => 'Custom name to use in the Swagger documentation',
                    'type' => 'Will appear below the name in the Swagger documentation',
                ],
            ];
        }

        return $description;
    }
}
