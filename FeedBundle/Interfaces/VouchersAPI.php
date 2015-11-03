<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 11.10.15
 * Time: 22:48
 */

namespace FeedBundle\Interfaces;


/**
 * This method will return all vouchers from our partner in a JSON format.
 * Our partner will deliver the result of
 * input1.json on the first call and
 * input2.json on every subsequent call
 * @param Controller $controller
 * @return string JSON-formatted string
 */
interface VouchersAPI {

} 