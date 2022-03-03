<?php

namespace App\Connection\Domain\ConnectionAdapter;

use App\Connection\Domain\ConnectionResult;
use App\Connection\Infrastructure\Adapter\SmsServiceAdapterInterface;
use App\SDKMocks\SmsSender;

/**
 * Adapter for SmsSender service sdk
 * @see https://api.sms-sender.test/documentation Service documentation
 */
class SmsSenderAdapter extends AbstractConnectionServiceAdapter implements SmsServiceAdapterInterface
{
    public function __construct()
    {
        $this->connectionService = new SmsSender();
    }

    /**
     * @inheritDoc
     */
    public function sendSms(string $from, string $to, string $text): ConnectionResult
    {
        return self::makeConnectionResult(
            $this->connectionService->sendSMS($from, $to, $text),
            'Unexpected sms sending error via SmsSender'
        );
    }
}