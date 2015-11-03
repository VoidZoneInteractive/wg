<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 12.10.15
 * Time: 21:50
 */

namespace WebgearsBundle\External\Fetcher\Partner;
use Symfony\Component\Config\Definition\Exception\Exception;
//use Symfony\Component\Validator\Constraints\DateTime;

/**
 * For validating incoming JSON data
 * @package WebgearsBundle\External\Fetcher\Partner
 */
class Validation {

    const VALIDATION_NOT_EMPTY = 0b00001;
    const VALIDATION_IS_INT    = 0b00010;
    const VALIDATION_IS_STRING = 0b00100;
    const VALIDATION_IS_URL    = 0b01000;
    const VALIDATION_IS_DATE   = 0b10000;

    /**
     * @var array
     */
    private $validationRules;

    public function __construct(array $validationRules)
    {
        $this->validationRules = $validationRules;

        return $this;
    }

    /**
     * Validate and sanitize entity to make sure that all required parameters exists and are in correct format
     */
    public function validate(\stdClass &$entity)
    {
        foreach ($this->validationRules as $field => $validationRules)
        {
            $isValid = true;
            foreach ($validationRules as $rule)
            {
                switch ($rule)
                {
                    case self::VALIDATION_NOT_EMPTY:
                        if (empty($entity->{$field}))
                        {
                            $isValid = false;
                        }
                        break;

                    case self::VALIDATION_IS_STRING:
                        if (!is_string($entity->{$field}))
                        {
                            $isValid = false;
                        }
                        break;

                    case self::VALIDATION_IS_INT:
                        if (!is_int($entity->{$field}))
                        {
                            $isValid = false;
                        }
                        break;

                    case self::VALIDATION_IS_URL:
                        if (filter_var($entity->destinationUrl, FILTER_VALIDATE_URL) === false)
                        {
                            $isValid = false;
                        }
                        break;

                    case self::VALIDATION_IS_DATE:
                        $date = new \DateTime($entity->{$field});
                        if (!$date)
                        {
                            $isValid = false;
                        }
                        break;
                }
            }

            if (!$isValid)
            {
                throw new Exception(sprintf('Invalid type or non-existing parameter \'%s\'.', $field));
            }
        }
    }
} 