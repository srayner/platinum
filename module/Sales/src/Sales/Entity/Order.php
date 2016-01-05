<?php

namespace Sales\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A sales order entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="sa_order")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=24, name="order_status")
     */
    protected $orderStatus;
    
    /**
     * @ORM\Column(type="date", name="account_date")
     */
    protected $orderDate;
    
    /**
     * @ORM\Column(type="string", length=24, name="customer_ref")
     */
    protected $customerRef;
    
    /**
     * @ORM\ManyToOne (targetEntity="Account")
     * @ORM\JoinColumn(name="sa_account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\ManyToOne (targetEntity="Branch")
     * @ORM\JoinColumn(name="sa_branch_id", referencedColumnName="id")
     */
    protected $branch;
    
    /**
     * @OneToMany(targetEntity="OrderLine", mappedBy="order")
     */
    protected $lines;
    
    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    public function getOrderDate()
    {
        return $this->orderDate;
    }

    public function getCustomerRef()
    {
        return $this->customerRef;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getBranch()
    {
        return $this->branch;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    public function setCustomerRef($customerRef)
    {
        $this->customerRef = $customerRef;
        return $this;
    }

    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }
}

