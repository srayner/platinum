<?php

namespace Sales\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A sales order line entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="sa_order_line")
 */
class OrderLine
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="lines")
     * @ORM\JoinColumn(name="sa_order_id", referencedColumnName="id")
     */
    protected $order;
    
    /**
     * @ORM\ManyToOne(targetEntity="Inventory\Entity\Item")
     * @ORM\JoinColumn(name="sc_item_id", referencedColumnName="id")
     */
    protected $item;
    
    /**
     * @ORM\Column(type="integer");
     */
    protected $qty;
    
    /**
     * @ORM\Column(type="string", length=24, name="order_status")
     */
    protected $orderStatus;
    
    public function getId()
    {
        return $this->id;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
        return $this;
    }

    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }
}

