<?php

namespace ocpu\Request;

use ocpu\Request\Broker\CURL;
use PHPUnit\Framework\TestCase;

class InjectableTest extends TestCase
{
    public function testCanInject()
    {
        $injectable = new BrokerInjectableMock();
        $this->assertInstanceOf(CURL::class, $injectable->getRequestBroker());
        $broker = new CURLMock();
        $injectable->setRequestBroker($broker);
        $this->assertSame($broker, $injectable->getRequestBroker());
    }
}
