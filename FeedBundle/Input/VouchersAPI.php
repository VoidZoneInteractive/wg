<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 11.10.15
 * Time: 22:56
 */

namespace FeedBundle\Input;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;

/**
 * Class VouchersAPI used to get data from external API
 * @package FeedBundle\Input
 */
class VouchersAPI implements \FeedBundle\Interfaces\VouchersAPI {

    /**
     * Relative path to directory with vouchers json data.
     *
     */
    const PATH_TO_DATA = '/../feed';

    /**
     * @var string[] We store output data here.
     */
    public $data;

    /**
     * This method will return all vouchers from our partner in a JSON format.
     * Our partner will deliver the result of
     * input1.json on the first call and
     * input2.json on every subsequent call
     * @param Controller $controller
     * @return string JSON-formatted string
     */
    public function getVouchers(Controller &$controller)
    {
        $this->data = array();

        $dir = $controller->get('kernel')->getRootDir() . self::PATH_TO_DATA;
        $finder = new Finder();
        $finder->files()->in($dir);

        foreach ($finder as $file)
        {
            $this->data[$file->getFilename()] = $file->getContents();
        }

        if ($this->isFirstCall())
        {
            return $this->data['input1.json'];
        }
        else
        {
            $data = json_decode($this->data['input2.json'],1);
//            unset($data[4]);
            $this->data['input2.json'] = json_encode(array_values($data));
            return $this->data['input2.json'];
        }
    }

    /**
     * Helper function used to determine if there was already request to API.
     * @return bool
     */
    private function isFirstCall()
    {
        if (apc_fetch('was_request_called'))
        {
            return false;
        }

        apc_store('was_request_called', true);
        return true;
    }
} 