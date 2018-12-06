<?php

namespace ocpu\Request;

interface IRequestBrokerMulti extends IRequestBroker
{
    public function addRequest(IRequestBroker $request);
}