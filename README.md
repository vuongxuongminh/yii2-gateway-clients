# YII2 Gateway Clients
**Skeleton for build rest api client (1-n).**

[![Latest Stable Version](https://poser.pugx.org/vxm/yii2-gateway-clients/v/stable)](https://packagist.org/packages/vxm/yii2-gateway-clients)
[![Total Downloads](https://poser.pugx.org/vxm/yii2-gateway-clients/downloads)](https://packagist.org/packages/vxm/yii2-gateway-clients)
[![Build Status](https://travis-ci.org/vuongxuongminh/yii2-gateway-clients.svg?branch=1.0.0)](https://travis-ci.org/vuongxuongminh/yii2-gateway-clients)
[![Code Coverage](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-gateway-clients/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-gateway-clients/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-gateway-clients/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-gateway-clients/?branch=master)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

This Yii2 extension is an abstraction layer provide the skeleton for build  an api client (1-n).

## Requirements

* [PHP >= 7.1](http://php.net)
* [yiisoft/yii2 ~ 2.0.13](https://github.com/yiisoft/yii2)
* [yiisoft/yii2-httpclient ~ 2.0.0](https://github.com/yiisoft/yii2-httpclient)
* [vxm/gateway-clients ~ 1.0.0](https://github.com/vuongxuongminh/gateway-clients)


## Installation

The preferred way to install this yii2-extension is through [composer](http://getcomposer.org/download/).

```sh
composer require "vxm/yii2-gateway-clients"
```

or add

```json
"vxm/yii2-gateway-clients": "*"
```

to the require section of your composer.json.

## Usage

This is an abstraction layer, you MUST be create your own classes implements it. It designed for DRY principle when you need to build rest api client.

## Abstract Classes Introduce

An abstract classes have been designed for implemented an interfaces. You should extends it for easier implements interfaces on your own classes.

|Abstract Class | Details| 
|------|--------|
|[**BaseGateway**](src/BaseGateway.php)|Implemented [**GatewayInterface**](src/GatewayInterface.php), abstract method your own class must create: `requestInternal`, `getBaseUrl`.
|[**BaseClient**](src/BaseClient.php)|Implemented [**ClientInterface**](src/ClientInterface.php), it not have an abstract method your own class should add properties an information for access gateway server api.
|[**ResponseData**](src/ResponseData.php)|Abstract method your own class must create is `getIsOk` for end-user checking response data get from gateway server api is valid or not.

## Need an example? 

Click [**here**](examples/example.php) to read simple example.


## Project using it

* [yiiviet/yii2-esms](https://github.com/yiiviet/yii2-esms)
* [yiiviet/yii2-payment](https://github.com/yiiviet/yii2-payment)
