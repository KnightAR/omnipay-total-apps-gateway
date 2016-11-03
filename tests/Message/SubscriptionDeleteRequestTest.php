<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\TotalAppsGateway\Message\Subscription\SubscriptionDeleteRequest;
use Omnipay\Tests\TestCase;

class SubscriptionRequestTest extends BaseRequestTest
{
    /**
     * @return array
     */
    protected function getOptions()
    {
        return array_merge($this->getBaseOptions(), array(
            'subscriptionId' => '1234567890',
        ));
    }

    public function setUp()
    {
        parent::setUp();

        $this->request = new SubscriptionDeleteRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getOptions());
    }

    public function testBaseData()
    {
        $data = $this->request->getData();

        $this->assertSame('delete_sub', (string)$data['type']);
        $this->assertSame('6ef44f261a4a1595cd377d3ca7b57b92', (string)$data['token']);
        $this->assertSame('abcdefg1234567', (string)$data['processorId']);
    }

    public function testSubscriptionData()
    {
        $data = $this->request->getData();

        $this->assertSame('1234567890', (string)$data['subscriptionId']);
    }
}
