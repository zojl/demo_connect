<?php

namespace App\Connection\Infrastructure\Adapter;

use App\Connection\Domain\ConnectionResult;

interface CallServiceAdapterInterface
{
    /**
     * @param string $from
     * @param string $to
     * @return ConnectionResult
     */
    public function call(string $from, string $to): ConnectionResult;
}