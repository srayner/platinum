<?php

namespace Sales\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A sales area entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="sa_area")
 */
class Area
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $code;
    
    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $description;
    
    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}