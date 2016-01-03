<?php

namespace Sales\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A sales account entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="sa_account")
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=24, name="account_number")
     */
    protected $accountNumber;

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

    public function getAccountNumber() {
        return $this->accountNumber;
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

    public function setAccountNumber($accountNumber) {
        $this->accountNumber = $accountNumber;
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

