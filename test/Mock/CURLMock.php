<?php

namespace ocpu\Request;

class CURLMock implements IRequestBroker
{
    public $url;
    public $options;
    public $return;
    public $returns = [];
    public $closed;

    public function init()
    {
        $this->url = null;
        $this->options = [];
        $this->closed = false;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function setOpt(int $option, $value): bool
    {
        $this->options[$option] = $value;
        return true;
    }

    public function exec(): string
    {
        return $this->return ?? $this->returns[$this->url] ?? "";
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
