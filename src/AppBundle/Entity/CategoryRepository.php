<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Category as Category;


/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    /**
     * 
     */
    public function getCategoriesAsTree()
    {
        $categories = $this->findAll();
        $array = [];

        foreach($categories as $cat) {
            if($cat->getMasterCategory() != null) {
                $array[$cat->getMasterCategory()->getName()][$cat->getId()] = $cat->getName();
            }
        }
        return $array;
    }
    
    public function getMostPopularCategories($isExpense = true, $limit = 3) 
    {
        
//SELECT f, COUNT(p) as qtd FROM AcmeStatsBundle:Federation f LEFT JOIN f.people p WHERE p.attr = :some_value GROUP BY f.id ORDER BY qtr        
        
        $dql = "SELECT c, count(t) as HIDDEN s FROM AppBundle\Entity\Category c 
        LEFT JOIN c.transactions t 
        WHERE c.masterCategory is not null AND t.isExpense = ?1
        GROUP BY c.id
        ORDER BY s DESC
        ";

        $em = $this->getEntityManager();

        $query = $em->createQuery($dql);
        $query->setParameter(1, $isExpense);
        $query->setMaxResults($limit);

            
        return $result = $query->getResult();            
    }    
}
