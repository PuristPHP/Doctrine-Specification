<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Logic;

use Purist\Specification\Doctrine\SpecificationInterface;

/**
 * AndX specification lets you compose a new Specification with other specification classes.
 */
class AndX extends Composite
{
    public function __construct(SpecificationInterface ...$specification)
    {
        parent::__construct(self::AND_X, $specification);
    }
}
