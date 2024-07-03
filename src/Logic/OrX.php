<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Logic;

use Purist\Specification\Doctrine\SpecificationInterface;

class OrX extends Composite
{
    public function __construct(SpecificationInterface ...$specification)
    {
        parent::__construct(self::OR_X, $specification);
    }
}
