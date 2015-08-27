<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     */
    private $masterCategory;
    
    /**
     * 
      * @ORM\OneToMany(targetEntity="Transaction", mappedBy="category")
      * 
      */
    private $transactions;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set category
     *
     * @param \stdClass $category
     * @return Category
     */
    public function setMasterCategory(Category $category)
    {
        $this->masterCategory = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category 
     */
    public function getMasterCategory()
    {
        return $this->masterCategory;
    }
    
    public function __toString()
    {
        return $this->getName();
    }
}
