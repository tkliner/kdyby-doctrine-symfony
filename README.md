Kdyby/Doctrine QueryObject for Symfony
======

Introduction
------------

[Kdyby/Doctrine](https://github.com/Kdyby/Doctrine) is nice extension to provide integration of Doctrine 2 ORM into Nette Framework. This extension is a lite version of Kdyby/Doctrine extension for Symfony Framework and contains the most essential classes for working with [Kdyby\Doctrine QueryObjects](https://github.com/kdyby/doctrine/blob/master/docs/en/resultset.md) in Symfony.

Requirements
------------

Kdyby/Doctrine QueryObject requires PHP 5.4.

- [Nette Utils](https://github.com/nette/utils)

Installation
------------

The best way how to install Kdyby/Doctrine QueryObject is using  [Composer](http://getcomposer.org/):

Add to your composer.json above require section:

```
"repositories": [
        {
            "type": "git",
            "url": "https://github.com/tkliner/kdyby-doctrine-symfony.git"
        }
    ]
```    

And to "require" section add:

```
"kdyby/kdyby-doctrine-symfony": "dev-master"
```

Now you can update composer

Usage
------------

**Replace default Doctrine Entity Manager to Kdydy\Doctrine\EntityManager**

Add new elements to config.yml in parameters and in orm

```
parameters:
    ...
    doctrine.orm.entity_manager.class: Kdyby\Doctrine\EntityManager
    ...
```

```
orm:
    ...
    default_entity_manager: Kdyby\Doctrine\EntityManager
    ...
```

**Create QueryObject**

```
// src/AppBundle/Entity/UserQuery

namespace AppBundle\Entity;

use Kdyby;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Persistence\Queryable;

class UserQuery extends **Kdyby\Doctrine\QueryObject**
{

    /**
     * @var array|\Closure[]
     */
    private $filter = [];

    /**
     * @var array|\Closure[]
     */
    private $select = [];


    public function inCategory($categoryId = NULL)
    {
        $this->filter[] = function (QueryBuilder $qb) use ($categoryId) {
            $qb->andWhere('User.category = :cat')->setParameter('cat', $categoryId);
        };
        return $this;
    }
    
    public function withStatus($status = NULL)
    {
        $this->filter[] = function (QueryBuilder $qb) use ($status) {
            $qb->andWhere('User.status = :status')->setParameter('status', $status);
        };
        return $this;
    }

    /**
     * @param \Kdyby\Persistence\Queryable $repository
     * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
     */
    protected function doCreateQuery(Queryable $repository)
    {
        $qb = $this->createBasicDql($repository);

        return $qb;
    }


    protected function doCreateCountQuery(Queryable $repository)
    {
        return $this->createBasicDql($repository)->select('COUNT(User.id)');
    }


    private function createBasicDql(Queryable $repository)
    {
        $qb = $repository->createQueryBuilder()->select('User')->from(User::class, 'User');

        foreach ($this->filter as $modifier) {
            $modifier($qb);
        }

        return $qb;
    }

}
```

**In Controller**

```
namespace AppBundle\Controller;

use Nette\Utils\Paginator;
use AppBundle\Entity\UserQuery;

public function indexAction($page, Request $request)
{

  $paginator = new Paginator;
  $paginator->setPage($page);
  
  $repo = $this->getDoctrine()->getManager();
  
  $userQuery = new UserQuery();
  $userQuery->inCategory(1);
  $userQuery->withStatus(1)
  
  $users = $repo->fetch($userQuery)->applySorting(array('User.id' => 'DESC'))->applyPaginator($paginator, 1);
  
  $numberRows = $users->getTotalCount();
  $paginator->setItemCount($numberRows);
  
   return $this->render('default/index.html.twig', [
            'pagination' => $paginator,
   ]);
}  
```


QueryObject you can generate and transmit in repository, but they should extends from Kdyby\Doctrine\EntityRepository.


```
namespace AppBundle\Entity;

use Kdyby\Doctrine\EntityRepository;

class UserRepository extends EntityRepository
{
    public function returnQuery()
    {
        return new UserQuery();
    }
}
```


Now you can change UserQuery Object follows:


```
private function createBasicDql(Queryable $repository)
    {
        $qb = $repository->createQueryBuilder('User');

        foreach ($this->filter as $modifier) {
            $modifier($qb);
        }

        return $qb;
    }
```


And in controller you can change logic:


```
$repo = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:User');

$userQuery = $repo->returnQuery();
$userQuery->inCategory(1);

$users = $repo->fetch($userQuery)
              ->applySorting(array('User.id' => 'DESC'))
              ->applyPaginator($paginator, 2);
```

Conclusions
------------

This is an experimental version without test and users' experience. Use at your own risk :)
