<?php

namespace App\SDKMocks;

class BestCaller
{
    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function startCall(string $from, string $to): bool
    {
        return (int)$from > (int)$to;
    }
}