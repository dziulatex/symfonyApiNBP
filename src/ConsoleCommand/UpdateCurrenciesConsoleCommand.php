<?php

namespace App\ConsoleCommand;

use App\ApiRequest\GetAllCurrenciesFromNBPApiRequest;
use App\ApiRequest\GetSingleCurrencyFromNBPApiRequest;
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
    private CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi;
    private LoggerInterface $logger;

    /**
     * @param CurrencyExchangeRateUpdateInterface $currencyExchangeRateUpdate
     * @param CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi
     * @param LoggerInterface $logger
     */
    public function __construct(
        CurrencyExchangeRateUpdateInterface $currencyExchangeRateUpdate,
        CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->currencyExchangeRateUpdate = $currencyExchangeRateUpdate;
        $this->currencyExchangeRateNBPApi = $currencyExchangeRateNBPApi;
        $this->logger = $logger;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $currencyDTOs = $this->currencyExchangeRateNBPApi->getAllCurrencies();
            $this->currencyExchangeRateUpdate->updateFromArrayOfDTOs($currencyDTOs);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
