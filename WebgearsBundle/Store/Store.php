<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 13.10.15
 * Time: 20:54
 */

namespace WebgearsBundle\Store;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Config\Definition\Exception\Exception;
use WebgearsBundle\Entity\Shop;
use WebgearsBundle\Entity\Voucher;

/**
 * Class Store - used for storing data into database
 * @package WebgearsBundle\Store
 */
class Store {

    protected $entityManager;
    protected $logger;

    protected $shops = null;
    protected $vouchers = array(
        'to_update' => null,
        'to_insert' => null,
    );

    /**
     * Constructor class
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->entityManager = $em;
        $this->logger = $logger;
        return $this;
    }

    /**
     * Prepare shops to insert to database
     * @param array $shops
     */
    public function prepareShops(array &$shops)
    {
        if (!empty($shops))
        {
            $shopIds = array_keys($shops);
            foreach ($this->entityManager->getRepository('WebgearsBundle:Shop')->selectShopsByIds($shopIds) as $entry)
            {
                unset($shops[$entry['id']]);
            }
            $this->shops = $shops;
        }
    }

    /**
     * Insert all new shops into database
     */
    public function insertShops()
    {
        if (is_null($this->shops))
        {
            throw new Exception(sprintf('There are no shops to insert. Did you run \'prepareShops\' beforehand?'));
        }

        $i = 0;

        foreach ($this->shops as $shop)
        {
            $shopEntity = new Shop();
            $shopEntity->assignFields($shop);

            $this->entityManager->persist($shopEntity);

            $metadata = $this->entityManager->getClassMetaData(get_class($shopEntity));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);


            $this->entityManager->flush();

            ++$i;
        }
        $this->logger->info(sprintf('Finished inserting shops into database. New positions: %d.', $i));

        return $this;
    }

    /**
     * Prepare vouchers to insert to database
     *
     * @param array $vouchers
     */
    public function prepareVouchers(array &$vouchers)
    {
        if (!empty($vouchers))
        {
            // Removed vouchers that haven't changed
            $vouchersHashes = array_keys($vouchers);
            foreach($this->entityManager->getRepository('WebgearsBundle:Voucher')->selectVouchersByHashes($vouchersHashes) as $entry)
            {
                unset($vouchers[$entry['hash']]);
            }
            unset($vouchersHashes);

            // Split vouchers to insert from those to update
            $vouchersIds = array();
            $idToHashMap = array();
            foreach ($vouchers as $entry)
            {
                $vouchersIds[] = $entry->id;
                $idToHashMap[$entry->id] = $entry->hash;
            }

            foreach($this->entityManager->getRepository('WebgearsBundle:Voucher')->selectVouchersToUpdate($vouchersIds) as $entry)
            {
                if (is_null($this->vouchers['to_update']))
                {
                    $this->vouchers['to_update'] = array();
                }
                $this->vouchers['to_update'][] = $vouchers[$idToHashMap[$entry['id']]];
                unset($vouchers[$idToHashMap[$entry['id']]]);
            }
            unset($idToHashMap);
            unset($vouchersIds);

            $this->vouchers['to_insert'] = $vouchers;
        }
    }

    /**
     * Insert all new vouchers into database
     */
    public function insertAndUpdateVouchers()
    {
        if (is_null($this->vouchers['to_insert']) && is_null($this->vouchers['to_update']))
        {
            throw new Exception(sprintf('There are no vouchers to insert. Did you run \'prepareVouchers\' beforehand?'));
        }

        if (empty($this->vouchers['to_insert']) && empty($this->vouchers['to_update']))
        {
            $this->logger->info(sprintf('No new or updated entries.'));
            return $this;
        }

        $i = 0;

        // Insert new vouchers
        if (!is_null($this->vouchers['to_insert']))
        {
            foreach ($this->vouchers['to_insert'] as $voucher)
            {
                $voucherEntity = new Voucher();
                $voucherEntity->assignFields($voucher);
                $shop = $this->entityManager->getRepository('WebgearsBundle:Shop')->find($voucher->shop_id);
                $voucherEntity->setShop($shop);
                $voucherEntity->setFoundDate(new \DateTime());

                $this->entityManager->persist($voucherEntity);

                $metadata = $this->entityManager->getClassMetaData(get_class($voucherEntity));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);


                $this->entityManager->flush();

                ++$i;
            }
        }
        $this->logger->info(sprintf('Finished inserting vouchers into database. New positions: %d.', $i));

        // Update existing vouchers
        $i = 0;
        if (!is_null($this->vouchers['to_update']))
        {
            foreach($this->vouchers['to_update'] as $voucher)
            {
                $voucherEntity = $this->entityManager->getRepository('WebgearsBundle:Voucher')->find($voucher->id);
                $voucherEntity->assignFields($voucher);
                $shop = $this->entityManager->getRepository('WebgearsBundle:Shop')->find($voucher->shop_id);
                $voucherEntity->setShop($shop);
                $voucherEntity->setChecked(0);
                $voucherEntity->setFoundDate(new \DateTime());

                $this->entityManager->flush();

                ++$i;
            }
        }
        $this->logger->info(sprintf('Finished updating vouchers into database. Updated positions: %d.', $i));

        return $this;
    }
} 