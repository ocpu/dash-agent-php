<?php

namespace ocpu\Request;

use PHPUnit\Framework\TestCase;

class RequestBuilderTest extends TestCase
{
    public function testInstantiate()
    {
        $req = new RequestBuilder("GET", "ocpu.me");
        $this->assertInstanceOf("\ocpu\Request\RequestBuilder", $req);
        $this->assertEquals($req->getMethod(), "GET");
        $this->assertEquals($req->getHost(), "ocpu.me");
        $req = new RequestBuilder("POST", "ocpu.me");
        $this->assertEquals($req->getMethod(), "POST");
        $req = RequestBuilder::get("ocpu.me");
        $this->assertEquals($req->getMethod(), "GET");
        $req = RequestBuilder::post("ocpu.me");
        $this->assertEquals($req->getMethod(), "POST");
    }

    public function testSecure()
    {
        $req = RequestBuilder::get("ocpu.me");
        $this->assertEquals("https://ocpu.me", $req->buildURL());
        $req->secure();
        $this->assertEquals("https://ocpu.me", $req->buildURL());
    }

    public function testUnSecure()
    {
        $req = RequestBuilder::get("ocpu.me");
        $req->unSecure();
        $this->assertEquals("http://ocpu.me", $req->buildURL());
    }

    public function testHeaders()
    {
        $req = RequestBuilder::get("ocpu.me");
        $req->setHeader("Content-Type", "text/plain");
        $this->assertArrayHasKey("Content-Type", $req->getHeaders());
        $this->assertArrayNotHasKey("Content-Length", $req->getHeaders());
    }

    public function testQueryString()
    {
        $req = RequestBuilder::get("ocpu.me");
        $req->setQuery("param1", "text plain");
        $req->setQuery("param2", "1");
        $req->setQuery("noval");
        $this->assertEquals("https://ocpu.me?param1=text%20plain&param2=1&noval", $req->buildURL());
    }

    public function testSentHeaders()
    {
        $broker = new CURLMock();
        RequestBuilder::get("ocpu.me")
            ->setRequester($broker)
            ->setHeader("Content-Type", "text/plain")
            ->send();
        $this->assertArrayHasKey(CURLOPT_HEADER, $broker->options);
        $this->assertEquals(1, count($broker->options[CURLOPT_HEADER]));
        $this->assertEquals("Content-Type: text/plain", $broker->options[CURLOPT_HEADER][0]);
    }

    public function testRequestAsJson()
    {
        $broker = new CURLMock();
        $broker->return = '{"status":"ok"}';
        $res = RequestBuilder::get("ocpu.me")->setRequester($broker)->getAsJSON();

        $this->assertObjectHasAttribute("status", $res);
        $this->assertEquals("ok", $res->status);
    }

    public function testMultiRequests()
    {
        $brokerSingle = new CURLMock();
        $brokerSingle->return = "ok";
        $brokerMulti = new CURLBulkMock();
        $res = RequestBuilder::multi([
            RequestBuilder::get("ocpu.me")->setRequester($brokerSingle)
        ], $brokerMulti)->send();

        $this->assertEquals(1, count($res));
        $this->assertEquals("ok", $res[0]);
    }

    public function testMultiRequestsAsJson()
    {
        $brokerSingle1 = new CURLMock();
        $brokerSingle2 = new CURLMock();
        $brokerSingle1->return = '{"status":"ok"}';
        $brokerSingle2->return = '{"status":"error"}';
        $brokerMulti = new CURLBulkMock();
        $res = RequestBuilder::multi([
            RequestBuilder::get("ocpu.me")->setRequester($brokerSingle1),
            RequestBuilder::get("ocpu.me")->setRequester($brokerSingle2)
        ], $brokerMulti)->getAsJSON();

        $this->assertEquals(2, count($res));
        $this->assertObjectHasAttribute("status", $res[0]);
        $this->assertEquals("ok", $res[0]->status);
        $this->assertObjectHasAttribute("status", $res[1]);
        $this->assertEquals("error", $res[1]->status);
    }
}
