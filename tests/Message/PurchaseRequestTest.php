<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\TotalAppsGateway\Message\Transaction\PurchaseRequest;

class PurchaseRequestTest extends AuthorizeRequestTest
{
    protected $expectedType = 'sale';
    
    public function setUp(): void
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getOptions());
    }
}
