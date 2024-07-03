# Doctrine Specification

Doctrine [Specification pattern][specification_pattern] for building queries dynamically and with re-usable classes for
composition.

This library started out as an adaptation of Benjamin Eberlei's [blog post][blog_post]. I was also inspired by
the [Happyr Doctrine-Specification][happyr_spec] code, however this library has some small differences.
The main one is that SpecificationRepository->match() does not return the results directly, but returns the query
object.

Since I like Doctrine's Paginator object, I wanted to be able to use that in combination with the Specification pattern.

__Note:__ In versions prior to 1.2 it was required to extend the SpecificationRepository class. This is no longer needed
since we provide a SpecificationRepositoryTrait that you can use instead.
The class is still provided for backwards compatibility reasons. There is also the SpecificationAwareInterface that you
can use if you need it.

## Usage

Install the latest version with `composer require purist/doctrine-specification`

```php
// Not using the lib
// Note: Advertisement repository is an instance of the Doctrine default repository class
$qb = $this->em->getRepository('Advertisement')
    ->createQueryBuilder('r');

return $qb->where('r.ended = 0')
    ->andWhere(
        $qb->expr()->orX(
            'r.endDate < :now',
            $qb->expr()->andX(
                'r.endDate IS NULL',
                'r.startDate < :timeLimit'
            )
        )
    )
    ->setParameter('now', new \DateTime())
    ->setParameter('timeLimit', new \DateTime('-4weeks'))
    ->getQuery()
    ->getResult();
```

```php
use Purist\Specification\Doctrine\Condition\Equals;
use Purist\Specification\Doctrine\Condition\IsNull;
use Purist\Specification\Doctrine\Condition\LessThan;
use Purist\Specification\Doctrine\Logic\AndX;
use Purist\Specification\Doctrine\Logic\OrX;
use Purist\Specification\Doctrine\Specification;

// Using the lib
$spec = new Specification([
    new Equals('ended', 0),
    new OrX(
        new LessThan('endDate', new \DateTime()),
        new AndX(
            new IsNull('endDate'),
            new LessThan('startDate', new \DateTime('-4weeks'))
        )
    )
]);

// Note: Advertisement repository is an instance that uses the SpecificationRepositoryTrait
return $this->em->getRepository('Advertisement')->match($spec)->execute();
```

## Composition

A bonus of this pattern is composition, which makes specifications very reusable:

```php

use Entity\Advertisement;

class ExpiredAds extends Specification
{
    public function __construct()
    {
        $specs = [
            new Equals('ended', 0),
            new OrX(
                new LessThan('endDate', new \DateTime()),
                new AndX(
                    new IsNull('endDate'),
                    new LessThan('startDate', new \DateTime('-4weeks'))
                )
            )
        ];
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value === Advertisement::class;
    }
}

use Entity\User;

class AdsByUser extends Specification
{
    public function __construct(User $user)
    {
        $specs = [
            new Select('u'),
            new Join('user', 'u'),
            new Equals('id', $user->getId(), 'u'),
        ];

        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value == Advertisement::class && parent::isSatisfiedBy($value);
    }
}

class SomeService
{
    /**
     * Fetch Adverts that we should close but only for a specific company
     */
    public function myQuery(User $user)
    {
        $spec = new Specification([
            new ExpiredAds(),
            new AdsByUser($user),
        ]);

        return $this->em->getRepository('Advertisement')->match($spec)->execute();
    }

    /**
     * Fetch adverts paginated by Doctrine Paginator with joins intact.
     * A paginator can be iterated over like a normal array or Doctrine Collection
     */
    public function myPaginatedQuery(User $user, $page = 1, $size = 10)
    {
        $spec = new Specification([
            new ExpiredAds(),
            new AdsByUser($user),
        ]);

        $query = $this->em->getRepository('Advertisement')->match($spec);
        $query->setFirstResult(($page - 1) * $size))
            ->setMaxResults($size);
        return new Paginator($query);
    }
}
```

## Requirements

Doctrine-Specification requires:

- PHP 8.3+
- Doctrine 2.2

## License

Doctrine-Specification is licensed under the MIT License - see the `LICENSE` file for details

## Acknowledgements

This library is heavily inspired by Benjamin Eberlei's [blog post][blog_post]
and [Happyr's Doctrine-Specification library][happyr_spec].

[specification_pattern]: http://en.wikipedia.org/wiki/Specification_pattern
[happyr_spec]: https://github.com/Happyr/Doctrine-Specification
[blog_post]: http://www.whitewashing.de/2013/03/04/doctrine_repositories.html
