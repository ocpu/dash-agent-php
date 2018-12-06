<?php

namespace ocpu\Request;

interface IInjectableBroker
{
    public function setRequestBroker(IRequestBroker $broker);
}
