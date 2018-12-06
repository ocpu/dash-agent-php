<?php

namespace ocpu\Request;

interface IRequestBroker
{
    public function init();
    public function exec();
    public function close(): void;
    public function getHandle();
}
