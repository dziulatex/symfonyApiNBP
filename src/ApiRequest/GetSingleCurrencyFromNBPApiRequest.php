<?php

namespace App\ApiRequest;


use App\DTO\CurrencyDTO;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
class GetSingleCurrencyFromNBPApiRequest
{
    /** @var string */
    private $urlApiNBP;
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getSingleCurrency(string $currencyCode): CurrencyDTO
    {
        $response = $this->client->request(
            'GET',
            $this->urlApiNBP . "/exchangerates/rates/A/$currencyCode/"
        );

        $contentArray = $response->toArray();
        return $this->buildReturnResponse($contentArray);
    }

    #[Required]
    public function setUrlApiNBP(string $urlApiNBP): void
    {
        $this->urlApiNBP = $urlApiNBP;
    }

    /**
     * @param array $contentArray
     * @return CurrencyDTO
     * @throws \Exception
     */
    public function buildReturnResponse(array $contentArray): CurrencyDTO
    {
        $newestCurrencyRateDate = null;
        $newestCurrencyRate = null;
        foreach ($contentArray['rates'] as $currencyRate) {
            $currentEffectiveDate = new \DateTime($currencyRate['effectiveDate']);
            if (!$newestCurrencyRateDate && !$newestCurrencyRate) {
                $newestCurrencyRateDate = $currentEffectiveDate;
                $newestCurrencyRate = $currencyRate['mid'];
            } else {
                if ($newestCurrencyRate < $currentEffectiveDate) {
                    $newestCurrencyRateDate = $currentEffectiveDate;
                    $newestCurrencyRate = $currencyRate['mid'];
                }
            }
        }
        $currencyDTO = new CurrencyDTO($contentArray['currency'], $contentArray['code'], $newestCurrencyRate);
        return $currencyDTO;
    }
}
