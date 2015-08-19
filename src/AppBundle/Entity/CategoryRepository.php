<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

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
}
