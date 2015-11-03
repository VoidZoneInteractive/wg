<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 13.10.15
 * Time: 22:41
 */

namespace WebgearsBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
 * Class ShopRepository
 * @package WebgearsBundle\Entity
 */
class ShopRepository extends EntityRepository {
    /**
     * Find shops by provided id list and return matched ids
     *
     * @param array $shopIds
     * @return array
     */
    public function selectShopsByIds(array $shopIds)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT s.id
    FROM WebgearsBundle:Shop s
    WHERE s.id IN (:ids)'
        )->setParameter('ids', $shopIds);

        return $query->getResult();
    }
} 