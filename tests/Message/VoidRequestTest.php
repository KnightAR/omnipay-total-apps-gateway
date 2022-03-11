<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\TotalAppsGateway\Message\Transaction\VoidRequest;
use Omnipay\Tests\TestCase;

class VoidRequestTest extends BaseRequestTest
{
    /**
     * @var VoidRequest
     */
    protected $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array_merge($this->getBaseOptions(), array(
            'transactionReference' => '3244053957',
        )));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('void', (string)$data['type']);
        $this->assertSame('3244053957', (string)$data['transactionid']);
    }
}
