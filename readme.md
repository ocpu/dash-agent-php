[![Build Status](https://img.shields.io/travis/ocpu/dash-agent-php/master.svg?style=flat-square)](https://travis-ci.org/ocpu/dash-agent-php)
[![Build Status](https://img.shields.io/circleci/project/github/ocpu/dash-agent-php/master.svg?style=flat-square)](https://circleci.com/gh/ocpu/dash-agent-php)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ocpu/dash-agent-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/ocpu/dash-agent-php/?branch=master)
[![Code Quality](https://img.shields.io/scrutinizer/g/ocpu/dash-agent-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/ocpu/dash-agent-php/?branch=master)
[![Maintainability](https://img.shields.io/codeclimate/maintainability/ocpu/dash-agent-php.svg?style=flat-square)](https://codeclimate.com/github/ocpu/dash-agent-php)

# dash-agent-php

Dash Agent is a request abstraction to make requests easier to do.

## Install

```
composer require ocpu/dash-agent
```

## Shortly about the API

This abstraction has a central class `RequestBuilder`. That class is the key to the whole api. You can initialize it with `new RequestBuilder(method, host)`, `RequestBuilder::get(host)` or `RequestBuilder::post(host)`. The `host` parameter is a url without the protocol and query string. 
