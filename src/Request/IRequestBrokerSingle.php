<?php

namespace ocpu\Request;

interface IRequestBrokerSingle extends IRequestBroker
{
    public function setUrl(string $url);
    public function setOpt(int $option, $value): bool;
}
