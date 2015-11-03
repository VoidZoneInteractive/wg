<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 11.10.15
 * Time: 21:35
 */

namespace WebgearsBundle\External\Fetcher;

use Symfony\Component\Config\Definition\Exception\Exception;
use WebgearsBundle\External\Fetcher\Entity\Shop;
use WebgearsBundle\External\Fetcher\Entity\Voucher;
use WebgearsBundle\External\Fetcher\Partner\Validation;

class Partner implements \WebgearsBundle\Interfaces\ExternalSource {

    /**
     * Url to feed
     *
     * @var string
     */
    public $api_url;

    /**
     * @var array
     */
    private $data;

    /**
     * Final formatted output data
     *
     * @var array
     */
    private $result;

    private static $validationRules = array(
        'id' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_INT,
        ),
        'code' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_STRING,
        ),
        'discount' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_STRING,
        ),
        'programId' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_INT,
        ),
        'program_name' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_STRING,
        ),
        'destinationUrl' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_STRING,
            Validation::VALIDATION_IS_URL,
        ),
        'startDate' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_STRING,
            Validation::VALIDATION_IS_DATE,
        ),
        'expiryDate' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_STRING,
            Validation::VALIDATION_IS_DATE,
        ),
        'commissionValueFormatted' => array(
            Validation::VALIDATION_NOT_EMPTY,
            Validation::VALIDATION_IS_STRING,
        ),
    );

    /**
     * Constructor method for Partner class
     *
     * @param $url
     */
    public function __construct()
    {
        $this->result = new \stdClass();
        $this->result->shops = array();
        $this->result->vouchers = array();

        return $this;
    }

    /**
     * Obtain data from external source
     */
    private function fetchDataFromSource()
    {
        $curlHandle = curl_init($this->api_url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        $rawData = curl_exec($curlHandle);

        $errno = curl_errno($curlHandle);

        // Throw exception if there was something wrong with getting data via curl
        if ($errno)
        {
            throw new Exception(sprintf('There was an error while doing curl request (Error Code: %d)', $errno));
        }

        $this->data = json_decode($rawData);

        // Throw exception if the collected JSON string is corrupted
        if (json_last_error())
        {
            throw new Exception(sprintf('There was an error while decoding JSON data (Error Code: %d)', json_last_error()));
        }

        return $this;
    }

    /**
     * Validate and sanitize entity to make sure that all required parameters exists and are in correct format
     */
    private function validateAndSanitizeEntity(\stdClass &$entity)
    {
        // First we validate
        $validation = new Validation(self::$validationRules);
        $validation->validate($entity);

        // Next we sanitize data
        $entity->code = trim($entity->code);
        $entity->discount = trim($entity->discount);
        $entity->program_name = trim($entity->program_name);
        $entity->startDate = new \DateTime($entity->startDate);
        $entity->expiryDate = new \DateTime($entity->expiryDate);
    }

    /**
     * Format incoming data
     */
    private function formatData()
    {
        // Throw exception when there is no data or corrupted data to parse
        if (empty($this->data) || !is_array($this->data))
        {
            throw new Exception(sprintf('There is no data to parse or data is corrupted. Did you use \'FetchDataFromSource\' beforehand?'));
        }

        foreach ($this->data as $entity)
        {
            $this->validateAndSanitizeEntity($entity);

            // Voucher
            $voucherEntity = new Voucher();
            $voucherEntity->id      = $entity->id;
            $voucherEntity->code    = $entity->code;
            $voucherEntity->shop_id = $entity->programId;
            // Not quite sure if that is desired result
            if ($entity->commissionValueFormatted == 'Default')
            {
                $voucherEntity->value = $entity->discount;
            }
            else
            {
                $voucherEntity->value = $entity->commissionValueFormatted;
            }
            $voucherEntity->valid_from = $entity->startDate;
            $voucherEntity->expire_date = $entity->expiryDate;
            $voucherEntity->url = $entity->destinationUrl;
            $voucherEntity->hash = md5(serialize($entity));

            $this->result->vouchers[$voucherEntity->hash] = $voucherEntity;

            // Shop
            $shopEntity = new Shop();
            $shopEntity->id = $entity->programId;
            $shopEntity->name = $entity->program_name;

            $this->result->shops[$shopEntity->id] = $shopEntity;
        }

        return $this;
    }

    /**
     * Construct method
     */
    public function construct()
    {
        $this->fetchDataFromSource();

        $this->formatData();
    }

    /**
     * Return fetched content
     */
    public function getResult()
    {
        return $this->result;
    }
} 