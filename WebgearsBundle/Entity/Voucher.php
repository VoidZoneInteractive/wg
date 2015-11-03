<?php

namespace WebgearsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class for manage vouchers in database
 *
 * @ORM\Table(name="voucher", indexes={@ORM\Index(name="hash_idx", columns={"hash"}), @ORM\Index(name="shop_idx", columns={"shop_id"})})
 * @ORM\Entity(repositoryClass="WebgearsBundle\Entity\VoucherRepository")
 */
class Voucher {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Checked voucher
     *
     * @ORM\Column(type="smallint", options={"default" = 0})
     */
    protected $checked;

    /**
     * Shop id
     *
     * @ORM\Column(type="integer")
     */
    protected $shop_id;

    /**
     * Voucher code
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $code;

    /**
     * Voucher value
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $value;

    /**
     * Url to product
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $url;

    /**
     * @ORM\Column(type="datetimetz")
     */
    protected $valid_from;

    /**
     * @ORM\Column(type="datetimetz")
     */
    protected $expire_date;

    /**
     * @ORM\Column(type="datetimetz")
     */
    protected $found_date;

    /**
     * Calculated hash shortcut for Voucher entity
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $hash;

    /**
     * @ORM\ManyToOne(targetEntity="Shop")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
     */
    protected $shop;

    /**
     * Assign fields in Voucher Entity
     *
     * @param \WebgearsBundle\External\Fetcher\Entity\Voucher $voucherEntity
     */
    public function assignFields(\WebgearsBundle\External\Fetcher\Entity\Voucher $voucher)
    {
        $this->id          = $voucher->id;
        $this->checked     = 0;
        $this->code        = $voucher->code;
        $this->value       = $voucher->value;
        $this->url         = $voucher->url;
        $this->valid_from  = $voucher->valid_from;
        $this->expire_date = $voucher->expire_date;
        $this->hash        = $voucher->hash;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Voucher
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
     * Set shopId
     *
     * @param integer $shopId
     *
     * @return Voucher
     */
    public function setShopId($shopId)
    {
        $this->shop_id = $shopId;

        return $this;
    }

    /**
     * Get shopId
     *
     * @return integer
     */
    public function getShopId()
    {
        return $this->shop_id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Voucher
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Voucher
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Voucher
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set validFrom
     *
     * @param \DateTime $validFrom
     *
     * @return Voucher
     */
    public function setValidFrom($validFrom)
    {
        $this->valid_from = $validFrom;

        return $this;
    }

    /**
     * Get validFrom
     *
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->valid_from;
    }

    /**
     * Set expireDate
     *
     * @param \DateTime $expireDate
     *
     * @return Voucher
     */
    public function setExpireDate($expireDate)
    {
        $this->expire_date = $expireDate;

        return $this;
    }

    /**
     * Get expireDate
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expire_date;
    }

    /**
     * Set foundDate
     *
     * @param \DateTime $foundDate
     *
     * @return Voucher
     */
    public function setFoundDate($foundDate)
    {
        $this->found_date = $foundDate;

        return $this;
    }

    /**
     * Get foundDate
     *
     * @return \DateTime
     */
    public function getFoundDate()
    {
        return $this->found_date;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Voucher
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set shop
     *
     * @param \WebgearsBundle\Entity\Shop $shop
     *
     * @return Voucher
     */
    public function setShop(\WebgearsBundle\Entity\Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \WebgearsBundle\Entity\Shop
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     *
     * @return Voucher
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return integer
     */
    public function getChecked()
    {
        return $this->checked;
    }
}
