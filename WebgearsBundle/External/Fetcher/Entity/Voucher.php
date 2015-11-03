<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 13.10.15
 * Time: 21:29
 */

namespace WebgearsBundle\External\Fetcher\Entity;

class Voucher {
    public $id;
    public $shop_id;
    public $code;
    public $value;
    public $valid_from;
    public $expire_date;
    public $url;
    public $hash;
} 