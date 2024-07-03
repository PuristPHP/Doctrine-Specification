<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Logic;

use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Specification;
use Purist\Specification\Doctrine\SpecificationInterface;

class Composite extends Specification
{
    /**
     * @param SpecificationInterface[] $children
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $type, array $children = [])
    {
        parent::__construct($children);

        $this->setType($type);
    }
}
