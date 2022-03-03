<?php

namespace App\SDKMocks;

class CheapSmsAndCalls
{
    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function call(string $from, string $to): bool
    {
        return (int)$from > (int)$to;
    }

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