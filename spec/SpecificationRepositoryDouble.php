<?php

declare(strict_types=1);

namespace spec\Purist\Specification\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Purist\Specification\Doctrine\SpecificationRepositoryTrait;

class SpecificationRepositoryDouble extends EntityRepository
{
    use SpecificationRepositoryTrait;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }
}
