<?php

namespace ocpu\Request;

use ocpu\Request\Broker\CURL;
use ocpu\Request\Broker\CURLBulk;

class RequestBuilder
{
    /**@var string */
    private $method;
    /**@var string */
    private $host;
    /**@var array */
    private $query;
    /**@var array */
    private $headers;
    /**@var bool */
    private $secure;
    /**@var IRequestBroker */
    private $requester;

    public function __construct(string $method, string $host)
    {
        $this->method = $method;
        $this->host = $host;
        $this->query = [];
        $this->headers = [];
        $this->secure = true;
        $this->requester = new CURL();
    }

    /**
     * @param IRequestBroker $requester
     * @return RequestBuilder
     */
    public function setRequester(IRequestBroker $requester): RequestBuilder
    {
        $this->requester = $requester;
        return $this;
    }

    /**
     * @param string $host
     * @return RequestBuilder
     * @SuppressWarnings(PHPMD)
     */
    public static function get(string $host): RequestBuilder
    {
        return new RequestBuilder("GET", $host);
    }

    /**
     * @param string $host
     * @return RequestBuilder
     * @SuppressWarnings(PHPMD)
     */
    public static function post(string $host): RequestBuilder
    {
        return new RequestBuilder("POST", $host);
    }

    public static function multi(array $requests, $broker = null): RequestBuilder
    {
        $req = new RequestBuilder("", "");
        $broker = $broker ?? new CURLBulk();
        $broker->init();
        foreach ($requests as $request) {
            if ($request instanceof RequestBuilder) {
                $request->preSend();
                $broker->addRequest($request->requester);
            }
        }
        $req->setRequester($broker);
        return $req;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function secure(): RequestBuilder
    {
        $this->secure = true;
        return $this;
    }

    public function unSecure(): RequestBuilder
    {
        $this->secure = false;
        return $this;
    }

    public function setHeader(string $name, string $value): RequestBuilder
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setQuery(string $name, ?string $value = null): RequestBuilder
    {
        $this->query[$name] = $value;
        return $this;
    }

    private function preSend()
    {
        $url = $this->buildURL();
        $headers = [];
        foreach ($this->headers as $name => $value) {
            $headers[] = "$name: $value";
        }

        $this->requester->init();
        $this->requester->setUrl($url);
        $this->requester->setOpt(CURLOPT_HEADER, $headers);
    }

    public function send()
    {
        if (!($this->requester instanceof IRequestBrokerMulti))
            $this->preSend();

        $res = $this->requester->exec();
        $this->requester->close();

        return $res;
    }

    public function buildURL(): string
    {
        $scheme = $this->secure ? "https://" : "http://";
        $queryString = "";
        foreach ($this->query as $name => $value) {
            $queryString .= rawurlencode($name);
            if ($value !== null) {
                $queryString .= "=" . rawurlencode($value) . "&";
            }
        }
        $queryString = rtrim($queryString, "&");

        return $scheme . $this->host . (strlen($queryString) > 0 ? "?$queryString" : "");
    }

    public function getAsJSON()
    {
        $res = $this->send();
        if (is_array($res)) {
            return json_decode("[".implode(",", $res)."]");
        }
        return json_decode($res);
    }
}
