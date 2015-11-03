<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 11.10.15
 * Time: 21:34
 */

namespace WebgearsBundle\External;

use WebgearsBundle\Interfaces\ExternalSource;

/**
 * Fetcher is responsible for obtaining data from various partners API
 * @package WebgearsBundle\External
 */
class Fetcher {

    private $worker;

    /**
     * @param ExternalSource $externalSource
     */
    public function __construct(ExternalSource $externalSource)
    {
        $this->worker = $externalSource;
    }

    public function construct()
    {
        $this->worker->construct();

        return $this;
    }

    public function getResult()
    {
        return $this->worker->getResult();
    }
} 