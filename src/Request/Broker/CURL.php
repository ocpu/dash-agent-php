<?php

namespace ocpu\Request\Broker;

use ocpu\Request\IRequestBrokerSingle;

class CURL implements IRequestBrokerSingle
{
    private $request;

    public function init()
    {
        $this->request = \curl_init();
        \curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
    }

    public function setUrl(string $url)
    {
        curl_setopt($this->request, CURLOPT_URL, $url);
    }

    public function setOpt(int $option, $value): bool
    {
        return \curl_setopt($this->request, $option, $value);
    }

    public function exec(): string
    {
        return \curl_exec($this->request);
    }

    public function close(): void
    {
        \curl_close($this->request);
    }

    public function getHandle()
    {
        return $this->request;
    }
}
