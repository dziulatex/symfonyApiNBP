<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function save(Currency $currency)
    {
        $this->_em->persist($currency);
        $this->_em->flush();
    }

    /**
     * @param string $currencyCode
     * @return Currency|null
     */
    public function getSingleByCode(string $currencyCode): ?Currency
    {
        return $this->findOneBy(['currencyCode' => $currencyCode]);
    }

    /**
     * @param array $currencyCodes
     * @return array
     */
    public function getAllByCodes(array $currencyCodes): array
    {
        return $this->findBy(['currencyCode' => $currencyCodes]);
    }
}
