<?php

namespace App\Connection\Domain\ConnectionAdapter;

use App\Connection\Domain\ConnectionResult;

abstract class AbstractConnectionServiceAdapter
{
    /** @var object $connectionService */
    protected object $connectionService;

    abstract public function __construct();

    /**
     * @param bool $isSuccess
     * @param string $defaultErrorMessage
     * @return ConnectionResult
     */
    protected static function makeConnectionResult(bool $isSuccess, string $defaultErrorMessage = ''): ConnectionResult
    {
        $result = new ConnectionResult($isSuccess);
        if (!$isSuccess) {
            $result->setErrorMessage($defaultErrorMessage);
        }

        return $result;
    }
}