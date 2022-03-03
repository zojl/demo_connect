<?php

namespace App\Connection\Infrastructure\Adapter;

use App\Connection\Domain\ConnectionResult;

interface SmsServiceAdapterInterface
{
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @return ConnectionResult
     */
    public function sendSms(string $from, string $to, string $text): ConnectionResult;
}