<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 12.10.15
 * Time: 00:19
 */

namespace WebgearsBundle\Interfaces;


interface ExternalSource {
    /**
     * Fetch results from external API
     *
     * @return mixed
     */
    public function getResult();
} 