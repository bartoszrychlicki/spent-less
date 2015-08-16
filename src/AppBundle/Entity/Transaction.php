<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DoctrineExtensions\Taggable\Taggable;


/**
 * Transaction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TransactionRepository")
 */
class Transaction implements Taggable
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     * @Assert\GreaterThan(value=0)
     * @Assert\NotNull
     * @Assert\Type(type="numeric")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @Assert\NotNull
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="memo", type="text", nullable=true)
     */
    private $memo;

    /**
     * @var string
     *
     * @ORM\Column(name="payee", type="string", length=255, nullable=true)
     */
    private $payee;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="account", type="object", nullable=true)
     */
    private $account;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isFlagged", type="boolean")
     */
    private $isFlagged;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $isExpense;
    
    private $tags;
    
    public function __construct()
    {
        $this->isFlagged = false;
        $this->account = null;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isExpense = true;
        //return parent::__construct();
    }


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
     * Set amount
     *
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Transaction
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Transaction
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set category
     *
     * @param \stdClass $category
     * @return Transaction
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \stdClass 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set memo
     *
     * @param string $memo
     * @return Transaction
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * Get memo
     *
     * @return string 
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Set payee
     *
     * @param string $payee
     * @return Transaction
     */
    public function setPayee($payee)
    {
        $this->payee = $payee;

        return $this;
    }

    /**
     * Get payee
     *
     * @return string 
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Set account
     *
     * @param \stdClass $account
     * @return Transaction
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \stdClass 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set isFlagged
     *
     * @param boolean $isFlagged
     * @return Transaction
     */
    public function setIsFlagged($isFlagged)
    {
        $this->isFlagged = $isFlagged;

        return $this;
    }

    /**
     * Get isFlagged
     *
     * @return boolean 
     */
    public function getIsFlagged()
    {
        return $this->isFlagged;
    }
    
    public function setIsExpense($isExpense)
    {
        $this->isExpense = $isExpense;
        return $this;
    }
    
    public function getIsExpense()
    {
        return $this->isExpense;
    }
    
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return 'acme_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }    
}
