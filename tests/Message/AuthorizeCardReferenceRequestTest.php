<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest;

class AuthorizeCardReferenceRequestTest extends AuthorizeRequestTest
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array_merge($this->getOptions(), array(
            'card'          => null,
            'cardReference' => '1376993339'
        )));
    }

    public function testTransactionData()
    {
        $data = $this->request->getData();

        $this->assertNull($this->request->getCard());
        
        $this->assertSame('auth', (string)$data['type']);
        
        $this->assertSame('123', (string)$data['orderid']);
        $this->assertSame('12.00', (string)$data['amount']);
        $this->assertSame('1376993339', (string)$data['customer_vault_id']);
    }

    public function testGetDataCustomerDetails()
    {
        //
    }
}
