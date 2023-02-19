<?php

namespace App\Entity;


use App\Trait\HasTimestamps;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Repository\CurrencyRepository;

#[ORM\Entity(repositoryClass: CurrencyRepository::class), ORM\HasLifecycleCallbacks, ORM\Table(name: 'currency')]
class Currency
{
    use HasTimestamps;

    #[ORM\Id, ORM\Column(type: 'uuid', unique: true), ORM\GeneratedValue(strategy: 'NONE')]
    private UuidInterface $id;

    #[ORM\Column(name: "name", type: 'string', length: 50, nullable: false)]
    private string $name;

    #[ORM\Column(name: "currency_code", type: 'string', length: 3, nullable: false)]
    private string $currencyCode;
    #[ORM\Column(name: "exchange_rate", type: 'decimal', precision: 8, scale: 7)]
    private float $exchangeRate;

    /**
     * @param string $name
     * @param string $currencyCode
     * @param float $exchangeRate
     */
    public function __construct(string $name, string $currencyCode, float $exchangeRate)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->currencyCode = $currencyCode;
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @return float
     */
    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    /**
     * @param float $exchangeRate
     */
    public function setExchangeRate(float $exchangeRate): void
    {
        $this->exchangeRate = $exchangeRate;
    }


}
