<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine;

use Doctrine\ORM\AbstractQuery;
use Purist\Specification\Doctrine\Result\ModifierInterface;

/**
 * SpecificationAware can be used to implement custom repository.
 */
interface SpecificationAwareInterface
{
    /**
     * Get the query after matching with given specification.
     */
    public function match(SpecificationInterface $specification, ?ModifierInterface $modifier = null): AbstractQuery;
}
