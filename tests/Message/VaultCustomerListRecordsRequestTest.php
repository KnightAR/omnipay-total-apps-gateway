<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\TotalAppsGateway\Message\Vault\VaultCustomerListRecordsRequest;
use Omnipay\Tests\TestCase;

class VaultCustomerListRecordsRequestTest extends BaseRequestTest
{
    /**
     * @return array
     */
    protected function getOptions()
    {
        return array_merge($this->getBaseOptions(), array(
            'cardReference' => '1234567890', # The hash to identify the customer in the vault
            'firstName'     => 'John', # Portion of cardholder's first name.
            'lastName'      => 'Doe', # Portion of cardholder's last name.
            'email'         => 'jon@doe.com', # Portion of billing email address.
            'last4cc'       => '1234'  # Last 4 digits of credit card number.
        ));
    }

    public function setUp()
    {
        parent::setUp();

        $this->request = new VaultCustomerListRecordsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getOptions());
    }

    public function testBaseData()
    {
        $data = $this->request->getData();

        $this->assertSame('list_customer', (string)$data['type']);
        $this->assertSame('6ef44f261a4a1595cd377d3ca7b57b92', (string)$data['password']);
        $this->assertSame('abcdefg1234567', (string)$data['username']);
    }

    public function testCustomerRequestData()
    {
        $data = $this->request->getData();

        $this->assertSame('1234567890', (string)$data['customerHash']);
        $this->assertSame('John', (string)$data['firstName']);
        $this->assertSame('Doe', (string)$data['lastName']);
        $this->assertSame('jon@doe.com', (string)$data['email']);
        $this->assertSame('1234', (string)$data['last4cc']);
    }
}
