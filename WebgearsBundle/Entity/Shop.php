<?php

namespace WebgearsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class for manage shops in database.
 * @ORM\Table(name="shop")
 * @ORM\Entity(repositoryClass="WebgearsBundle\Entity\ShopRepository")
 */
class Shop {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Shop name
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Voucher", mappedBy="shop_id")
     */
    protected $vouchers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vouchers = new ArrayCollection();
    }

    /**
     * Assign fields in Shop Entity
     *
     * @param \WebgearsBundle\External\Fetcher\Entity\Shop $shopEntity
     */
    public function assignFields(\WebgearsBundle\External\Fetcher\Entity\Shop $shopEntity)
    {
        $this->id = $shopEntity->id;
        $this->name = $shopEntity->name;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Shop
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return Shop
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
     * Add voucher
     *
     * @param \WebgearsBundle\Entity\Voucher $voucher
     *
     * @return Shop
     */
    public function addVoucher(\WebgearsBundle\Entity\Voucher $voucher)
    {
        $this->vouchers[] = $voucher;

        return $this;
    }

    /**
     * Remove voucher
     *
     * @param \WebgearsBundle\Entity\Voucher $voucher
     */
    public function removeVoucher(\WebgearsBundle\Entity\Voucher $voucher)
    {
        $this->vouchers->removeElement($voucher);
    }

    /**
     * Get vouchers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVouchers()
    {
        return $this->vouchers;
    }

    /**
     * Set vouchers
     *
     * @param \WebgearsBundle\Entity\Voucher $vouchers
     *
     * @return Shop
     */
    public function setVouchers(\WebgearsBundle\Entity\Voucher $vouchers = null)
    {
        $this->vouchers = $vouchers;

        return $this;
    }
}
