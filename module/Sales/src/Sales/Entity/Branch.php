<?php

namespace Sales\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A sales branch entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="sa_branch")
 */
class Branch
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=24, name="branch_number")
     */
    protected $branchNumber;

    /**
     * @ORM\Column(type="string", length=128, name="company_name")
     */
    protected $companyName;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $address;
    
    /**
     * @ORM\Column(type="string", length=8, name="post_code")
     */
    protected $postcode;
    
    public function getId() {
        return $this->id;
    }

    public function getBranchNumber() {
        return $this->branchNumber;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getPostcode() {
        return $this->postcode;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setBranchNumber($branchNumber) {
        $this->branchNumber = $branchNumber;
        return $this;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
        return $this;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    public function setPostcode($postcode) {
        $this->postcode = $postcode;
        return $this;
    }


}