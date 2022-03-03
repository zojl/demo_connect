<?php

namespace App\Connection\Domain;

use App\Connection\Domain\ConnectionAdapter\AbstractConnectionServiceAdapter;
use App\Connection\Domain\ConnectionAdapter\BestCallerAdapter;
use App\Connection\Domain\ConnectionAdapter\CheapSmsAndCallsAdapter;
use App\Connection\Domain\ConnectionAdapter\SmsSenderAdapter;
use App\Connection\Infrastructure\Adapter\CallServiceAdapterInterface;
use App\Connection\Infrastructure\Adapter\SmsServiceAdapterInterface;
use Exception;
use InvalidArgumentException;

class ConnectionAdapterFactory
{
    const SERVICE_TYPE_CALL = 'call';
    const SERVICE_TYPE_SMS = 'sms';

    /**
     * @return string[]
     */
    private function getAvailableCallServices(): array
    {
        return [
            'BestCaller' => BestCallerAdapter::class,
            'CheapSmsAndCalls' => CheapSmsAndCallsAdapter::class,
        ];
    }

    /**
     * @return string[]
     */
    private function getAvailableSmsServices(): array
    {
        return [
            'CheapSmsAndCalls' => CheapSmsAndCallsAdapter::class,
            'SmsSender' => SmsSenderAdapter::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getAvailableCallServicesCodes(): array
    {
        return array_keys($this->getAvailableCallServices());
    }

    /**
     * @return string[]
     */
    public function getAvailableSmsServicesCodes(): array
    {
        return array_keys($this->getAvailableSmsServices());
    }

    /**
     * @param string $serviceCode
     * @param string|null $serviceType
     * @return AbstractConnectionServiceAdapter
     * @throws Exception
     */
    public function createConnection(string $serviceCode, string $serviceType = null): AbstractConnectionServiceAdapter
    {
        $connectionClasses = array_merge($this->getAvailableSmsServices(), $this->getAvailableCallServices());
        if (!array_key_exists($serviceCode, $connectionClasses)) {
            throw new InvalidArgumentException(
                sprintf('Connection service with code %s is not exists', $serviceCode));
        }

        $connection = new $connectionClasses[$serviceCode]();
        if (!($connection instanceof AbstractConnectionServiceAdapter)) {
            throw new Exception(sprintf('%s is not valid connection service adapter', $connection[$serviceCode]));
        }

        if (!is_null($serviceType)) {
            if (!in_array($serviceType, [self::SERVICE_TYPE_CALL, self::SERVICE_TYPE_SMS])) {
                throw new InvalidArgumentException(sprintf('Invalid connection type %s', $serviceType));
            }

            if ($serviceType == self::SERVICE_TYPE_CALL && !($connection instanceof CallServiceAdapterInterface)) {
                throw new InvalidArgumentException(sprintf('Calls via %s service are not available', $serviceCode));
            }

            if ($serviceType == self::SERVICE_TYPE_SMS && !($connection instanceof SmsServiceAdapterInterface)) {
                throw new InvalidArgumentException(sprintf('Sms sending via %s service is not available', $serviceCode));
            }
        }

        return $connection;
    }
}