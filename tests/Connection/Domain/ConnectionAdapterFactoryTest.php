<?php

namespace App\Tests\Connection\Domain;

use App\Connection\Domain\ConnectionAdapter\BestCallerAdapter;
use App\Connection\Domain\ConnectionAdapter\CheapSmsAndCallsAdapter;
use App\Connection\Domain\ConnectionAdapter\SmsSenderAdapter;
use App\Connection\Domain\ConnectionAdapterFactory;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use stdClass;

class ConnectionAdapterFactoryTest extends TestCase
{
    public function testServiceCreation()
    {
        $adaptersByServiceCodes = [
            'BestCaller' => BestCallerAdapter::class,
            'CheapSmsAndCalls' => CheapSmsAndCallsAdapter::class,
            'SmsSender' => SmsSenderAdapter::class,
        ];

        $factory = new ConnectionAdapterFactory();
        foreach ($adaptersByServiceCodes as $code => $class) {
            $this->assertTrue($factory->createConnection($code) instanceof $class);
        }

        $this->assertTrue($factory->createConnection('BestCaller', ConnectionAdapterFactory::SERVICE_TYPE_CALL) instanceof BestCallerAdapter);
        $this->assertTrue($factory->createConnection('SmsSender', ConnectionAdapterFactory::SERVICE_TYPE_SMS) instanceof SmsSenderAdapter);
    }

    public function testNonSmsServiceCreationException()
    {
        $this->expectException(InvalidArgumentException::class);
        $factory = new ConnectionAdapterFactory();
        $factory->createConnection('BestCaller', ConnectionAdapterFactory::SERVICE_TYPE_SMS);
    }

    public function testNonCallServiceCreationException()
    {
        $this->expectException(InvalidArgumentException::class);
        $factory = new ConnectionAdapterFactory();
        $factory->createConnection('SmsSender', ConnectionAdapterFactory::SERVICE_TYPE_CALL);
    }

    public function testNonExistentServiceCreationException()
    {
        $this->expectException(InvalidArgumentException::class);
        $factory = new ConnectionAdapterFactory();
        $factory->createConnection('NonExistentService');
    }
}