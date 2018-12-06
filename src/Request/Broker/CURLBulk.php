<?php

namespace ocpu\Request\Broker;

use ocpu\Request\IRequestBroker;
use ocpu\Request\IRequestBrokerMulti;

class CURLBulk implements IRequestBrokerMulti
{
    private $request;
    private $requests;

    public function init()
    {
        $this->request = \curl_multi_init();
        $this->requests = [];
    }

    public function exec()
    {
        $index = null;
        do {
            curl_multi_exec($this->request, $index);
        } while ($index > 0);
        $responses = [];
        foreach ($this->requests as $i => $request) {
            $responses[$i] = \curl_multi_getcontent($request);
        }
        return $responses;
    }

    public function close(): void
    {
        foreach ($this->requests as $request) {
            \curl_multi_remove_handle($this->request, $request);
        }
        curl_multi_close($this->request);
    }

    public function addRequest(IRequestBroker $request)
    {
        $req = $request->getHandle();
        $this->requests[] = $req;
        \curl_multi_add_handle($this->request, $req);
    }

    public function getHandle()
    {
        return $this->request;
    }
}
