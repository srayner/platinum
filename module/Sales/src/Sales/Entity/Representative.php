<?php

namespace Sales\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A sales representative entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="sa_representative")
 */
class Representative
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=24)
     */
    protected $firstname;
    
    /**
     * @ORM\Column(type="string", length=24)
     */
    protected $lastname;
    
    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $description;
    
    public function getId()
    {
        return $this->id;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
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

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}