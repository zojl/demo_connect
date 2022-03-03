<?php

namespace App\Connection\Domain\ConnectionAdapter;

use App\Connection\Domain\ConnectionResult;
use App\Connection\Infrastructure\Adapter\CallServiceAdapterInterface;
use App\Connection\Infrastructure\Adapter\SmsServiceAdapterInterface;
use App\SDKMocks\CheapSmsAndCalls;

/**
 * Adapter for CheapSmsAndCalls service sdk
 * @see https://api.cheap-sms-and-calls.test/documentation Service documentation
 */
class CheapSmsAndCallsAdapter extends AbstractConnectionServiceAdapter implements CallServiceAdapterInterface, SmsServiceAdapterInterface
{
    public function __construct()
    {
        $this->connectionService = new CheapSmsAndCalls();
    }

    /**
     * @inheritDoc
     */
    public function call($from, $to): ConnectionResult
    {
        return self::makeConnectionResult(
            $this->connectionService->call($from, $to),
            'Unexpected call error via CheapSmsAndCalls'
        );
    }

    /**
     * @inheritDoc
     */
    public function sendSms(string $from, string $to, string $text): ConnectionResult
    {
        return self::makeConnectionResult(
            $this->connectionService->sendSMS($from, $to, $text),
            'Unexpected sms sending error via CheapSmsAndCalls'
        );
    }
}