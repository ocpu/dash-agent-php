<?php

namespace ocpu\Request;

use ocpu\Request\Broker\CURL;

trait TInjectableBroker
{
    private $requestBroker;

    /**
     * @param IRequestBroker $broker
     * @return self
     */
    public function setRequestBroker(IRequestBroker $broker)
    {
        $this->requestBroker = $broker;
        return $this;
    }

    /**
     * @return IRequestBroker
     */
    public function getRequestBroker()
    {
        $this->requestBroker = $this->requestBroker ?? new CURL;
        return $this->requestBroker;
    }
}
