<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest;
use Omnipay\Tests\TestCase;

abstract class BaseRequestTest extends TestCase
{
    /**
     * @var AuthorizeRequest
     */
    protected $request;

    /**
     * @return array
     */
    protected function getBaseOptions()
    {
        return array(
            'username' => 'abcdefg1234567',
            'password' => '6ef44f261a4a1595cd377d3ca7b57b92',
            'testMode' => true
        );
    }
}
