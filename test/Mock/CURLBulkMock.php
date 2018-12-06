<?php

namespace ocpu\Request;

class CURLBulkMock implements IRequestBrokerMulti
{
    public $closed;
    public $requests;

    public function init()
    {
        $this->requests = [];
        $this->closed = false;
    }

    public function addRequest(IRequestBroker $request)
    {
        $this->requests[] = $request->getHandle();
    }

    public function exec()
    {
        $responses = [];

        foreach ($this->requests as $request) {
            $responses[] = $request->exec();
        }

        return $responses;
    }

    public function close(): void
    {
        $this->closed = true;
    }

    public function getHandle()
    {
        return $this;
    }
}
