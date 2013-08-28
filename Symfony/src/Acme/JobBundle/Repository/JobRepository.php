<?php
namespace Acme\JobBundle\Repository;
use Doctrine\ORM\EntityRepository;
 
class JobRepository extends EntityRepository
{
  public function getActiveJobs($category_id = null)
  {
    $qb = $this->createQueryBuilder('j')
      ->where('j.expiresAt > :date')
      ->setParameter('date', date('Y-m-d H:i:s', time()))
      ->orderBy('j.expiresAat', 'DESC');
 
    if($category_id)
    {
      $qb->andWhere('j.category = :category_id')
         ->setParameter('category_id', $category_id);
    }
 
    $query = $qb->getQuery();
 
    return $query->getResult();
  }
}
?>
