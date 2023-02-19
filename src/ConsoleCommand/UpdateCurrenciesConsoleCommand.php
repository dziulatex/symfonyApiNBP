<?php

namespace App\ConsoleCommand;

use App\ApiRequest\GetAllCurrenciesFromNBPApiRequest;
use App\ApiRequest\GetSingleCurrencyFromNBPApiRequest;
use App\Service\CurrencyExchangeRateApis;
use App\Service\CurrencyExchangeRateNBPApi;
use App\Service\CurrencyExchangeRateUpdateInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:update:currencies')]
class UpdateCurrenciesConsoleCommand extends Command
{
    private CurrencyExchangeRateUpdateInterface $currencyExchangeRateUpdate;
    private LoggerInterface $logger;
    private CurrencyExchangeRateApis $currencyExchangeRateApis;

    /**
     * @param CurrencyExchangeRateUpdateInterface $currencyExchangeRateUpdate
     * @param LoggerInterface $logger
     * @param CurrencyExchangeRateApis $currencyExchangeRateApis
     */
    public function __construct(
        CurrencyExchangeRateUpdateInterface $currencyExchangeRateUpdate,
        LoggerInterface $logger,
        CurrencyExchangeRateApis $currencyExchangeRateApis
    ) {
        parent::__construct();
        $this->currencyExchangeRateUpdate = $currencyExchangeRateUpdate;
        $this->logger = $logger;
        $this->currencyExchangeRateApis = $currencyExchangeRateApis;
    }


    /**
     * @return \App\DTO\CurrencyDTO[]
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    //this method can be easily extended, to get multiple types of currency rates from more apis, just currencyDTO should be extended with some "sourceType" attribute or something, along with db entity.
    // i think sourceType, if added to this symfony app, should be enum or declared static somewhere.
    //later on all current calls for getAllByCodes, should be swapped with getAllByCodesAndType, and i think everything would be gucci.And if you would add sourceType, you should clear db, or make migration to add sourceType for earlier records which were createn without that column.


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $currencyDTOs = $this->currencyExchangeRateApis->getAllCurrenciesFromAllApis();
            $this->currencyExchangeRateUpdate->updateFromArrayOfDTOs($currencyDTOs);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
