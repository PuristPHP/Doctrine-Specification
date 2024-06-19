<?php

namespace spec\Purist\Specification\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Specification\Doctrine\Exception\LogicException;
use Purist\Specification\Doctrine\Result\ModifierInterface;
use Purist\Specification\Doctrine\SpecificationInterface;
use Purist\Specification\Doctrine\SpecificationRepository;

class SpecificationRepositorySpec extends ObjectBehavior
{
    private string $dqlAlias = 'e';

    private string $expression = 'expr';

    private string $result = 'result';

    public function let(EntityManager $entityManager, ClassMetadata $classMetadata): void
    {
        $classMetadata->name = 'foo';
        $this->beAnInstanceOf(SpecificationRepository::class);
        $this->beConstructedWith($entityManager, $classMetadata);
    }

    public function it_matches_a_specification_without_result_modifier(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        Query $query,
    ): void {
        $this->prepare($specification, $entityManager, $queryBuilder, $query);

        $specification->isSatisfiedBy(Argument::any())->willReturn(true);
        $specification->modify($queryBuilder, $this->dqlAlias)->shouldBeCalled();

        $this->match($specification)->shouldReturn($query);

        $query->execute();
    }

    public function it_matches_a_specification_with_result_modifier(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        Query $query,
        ModifierInterface $modifier,
    ): void {
        $this->prepare($specification, $entityManager, $queryBuilder, $query);
        $specification->isSatisfiedBy(Argument::any())
            ->willReturn(true);

        $specification->modify($queryBuilder, $this->dqlAlias)
            ->shouldBeCalled();
        $modifier->modify($query)
            ->shouldBeCalled();

        $this->match($specification, $modifier)
            ->shouldReturn($query);

        $query->execute();
    }

    public function it_should_throw_logic_exception_if_spec_not_supported(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        Query $query,
    ): void {
        $this->prepare($specification, $entityManager, $queryBuilder, $query);
        $specification->isSatisfiedBy(Argument::any())
            ->willReturn(false);

        $this->shouldThrow(LogicException::class)
            ->during('match', [$specification, null]);
    }

    public function it_should_accept_specification_with_only_query_modifiers(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        Query $query,
    ): void {
        $entityManager->createQueryBuilder()->willReturn($queryBuilder);

        $queryBuilder->select($this->dqlAlias)->willReturn($queryBuilder);
        $queryBuilder->from(Argument::any(), $this->dqlAlias, Argument::any())->willReturn($queryBuilder);
        $queryBuilder->where(Argument::any())->shouldNotBeCalled();
        $queryBuilder->getQuery()->willReturn($query);

        $specification->modify($queryBuilder, $this->dqlAlias, Argument::any())->shouldBeCalled();
        $specification->isSatisfiedBy(Argument::any())->willReturn(true);
        $specification->modify($queryBuilder, $this->dqlAlias, Argument::any())->willReturn('');

        $this->match($specification);
    }

    /**
     * Prepare mocks.
     */
    private function prepare(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        Query $query,
    ): void {
        $entityManager->createQueryBuilder()->willReturn($queryBuilder);

        $specification->modify($queryBuilder, $this->dqlAlias)->willReturn($this->expression);

        $queryBuilder->select($this->dqlAlias)->willreturn($queryBuilder);
        $queryBuilder->from(Argument::any(), $this->dqlAlias, Argument::any())->willReturn($queryBuilder);
        $queryBuilder->where($this->expression)->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);

        $query->execute()->willReturn($this->result);
    }
}
