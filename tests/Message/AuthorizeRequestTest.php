<?php

namespace Omnipay\TotalAppsGateway\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest;
use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends BaseRequestTest
{
    protected $expectedType = 'auth';
    
    /**
     * @return array
     */
    protected function getOptions()
    {
        return array_merge($this->getBaseOptions(), array(
            'amount'         => '12.00',
            'currency'       => 'USD',
            'transactionId'  => '123',
            'card'           => $this->getValidCard()
        ));
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getOptions());
    }

    public function testBaseData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->expectedType, (string)$data['type']);
        $this->assertSame('6ef44f261a4a1595cd377d3ca7b57b92', (string)$data['password']);
        $this->assertSame('abcdefg1234567', (string)$data['username']);
    }

    public function testTransactionData()
    {
        /** @var CreditCard $card */
        $card = $this->request->getCard();
        $data = $this->request->getData();

        $this->assertSame('123', (string)$data['orderid']);
        $this->assertSame('12.00', (string)$data['amount']);
        $this->assertSame('USD', (string)$data['currency']);

        $this->assertSame($card->getNumber(), (string)$data['ccnumber']);
        $this->assertSame($card->getExpiryDate('my'), $data['ccexp']);
        $this->assertSame((string)$card->getCvv(), (string)$data['cvv']);
    }

    public function testGetDataCustomerDetails()
    {
        /** @var CreditCard $card */
        $card = $this->request->getCard();
        $data = $this->request->getData();

        $this->assertSame((string)$card->getShippingFirstName(), (string)$data['shipping_firstname']);
        $this->assertSame((string)$card->getShippingLastName(), (string)$data['shipping_lastname']);
        $this->assertSame((string)$card->getShippingCompany(), (string)$data['shipping_company']);
        $this->assertSame((string)$card->getShippingCountry(), (string)$data['shipping_country']);
        $this->assertSame((string)$card->getShippingAddress1(), (string)$data['shipping_address1']);
        $this->assertSame((string)$card->getShippingAddress2(), (string)$data['shipping_address2']);
        $this->assertSame((string)$card->getShippingCity(), (string)$data['shipping_city']);
        $this->assertSame((string)$card->getShippingState(), (string)$data['shipping_state']);
        $this->assertSame((string)$card->getShippingPostcode(), (string)$data['shipping_zip']);
        $this->assertSame((string)$card->getShippingPhone(), (string)$data['shipping_phone']);
        $this->assertSame((string)$card->getShippingFax(), (string)$data['shipping_fax']);
        $this->assertSame((string)$card->getEmail(), (string)$data['shipping_email']);

        $this->assertSame((string)$card->getFirstName(), (string)$data['first_name']);
        $this->assertSame((string)$card->getLastName(), (string)$data['last_name']);
        $this->assertSame((string)$card->getCompany(), (string)$data['company']);
        $this->assertSame((string)$card->getCountry(), (string)$data['country']);
        $this->assertSame((string)$card->getAddress1(), (string)$data['address1']);
        $this->assertSame((string)$card->getAddress2(), (string)$data['address2']);
        $this->assertSame((string)$card->getCity(), (string)$data['city']);
        $this->assertSame((string)$card->getState(), (string)$data['state']);
        $this->assertSame((string)$card->getPostcode(), (string)$data['zip']);
        $this->assertSame((string)$card->getPhone(), (string)$data['phone']);
        $this->assertSame((string)$card->getBillingFax(), (string)$data['fax']);
        $this->assertSame((string)$card->getEmail(), (string)$data['email']);
    }
}
