<?php

namespace App\Connection\Domain\ConnectionAdapter;

use App\Connection\Domain\ConnectionResult;
use App\Connection\Infrastructure\Adapter\CallServiceAdapterInterface;
use App\SDKMocks\BestCaller;

/**
 * Adapter for BestCaller service sdk
 * @see https://api.best-caller.test/documentation Service documentation
 */
class BestCallerAdapter extends AbstractConnectionServiceAdapter implements CallServiceAdapterInterface
{
    public function __construct()
    {
        $this->connectionService = new BestCaller();
    }

    /**
     * @inheritDoc
     */
    public function call($from, $to): ConnectionResult
    {
        return self::makeConnectionResult(
            $this->connectionService->startCall($from, $to),
            'Unexpected call error via BestCaller'
        );
    }
}