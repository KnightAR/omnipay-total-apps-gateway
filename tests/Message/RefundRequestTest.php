<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\TotalAppsGateway\Message\Transaction\RefundRequest;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends BaseRequestTest
{
    /**
     * @var RefundRequest
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array_merge($this->getBaseOptions(), array(
            'amount'               => '12.00',
            'transactionReference' => '3244053957',
        )));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('refund', (string)$data['type']);
        $this->assertSame('3244053957', (string)$data['transactionid']);
        $this->assertSame('12.00', (string)$data['amount']);
    }
}
