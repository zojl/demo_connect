<?php

namespace App\SDKMocks;

class SmsSender
{
    /**
     * @param string $from
     * @param string $to
     * @param string $text
     * @return bool
     */
    public function sendSMS(string $from, string $to, string $text): bool
    {
        return (int)$from > (int)$to;
    }
}