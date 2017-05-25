<?php

namespace Omnipay\TotalAppsGateway\Test;

use Omnipay\TotalAppsGateway\ACH;
use Omnipay\TotalAppsGateway\Gateway;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\TotalAppsGateway\Message\Response\VaultCustomerListRecordsResponse;
use Omnipay\TotalAppsGateway\Message\Response\VaultCustomerRecordResponse;

class GatewayTest extends GatewayTestCase
{

    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $purchaseOptions;

    /**
     * @var array
     */
    protected $captureOptions;

    public static function getValidACH()
    {
        return array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'accountnumber' => '123123123',
            'routingnumber' => '123123123',
            'bankName' => "National Bank",
            'bankAddress' => '123 Billing St',
            'bankPhone' => '(555) 123-4567',
            'bankAccountType' => ACH::ACCOUNT_TYPE_CHECKING,
            'bankHolderAccountType' => ACH::ACCOUNT_HOLDER_TYPE_PERSONAL,
            'company' => 'DAB2LLC',
            'billingAddress1' => '123 Billing St',
            'billingAddress2' => 'Billsville',
            'billingCity' => 'Billstown',
            'billingPostcode' => '12345',
            'billingState' => 'CA',
            'billingCountry' => 'US',
            'billingPhone' => '(555) 123-4567',
            'shippingAddress1' => '123 Shipping St',
            'shippingAddress2' => 'Shipsville',
            'shippingCity' => 'Shipstown',
            'shippingPostcode' => '54321',
            'shippingState' => 'NY',
            'shippingCountry' => 'US',
            'shippingPhone' => '(555) 987-6543',
        );
    }
    
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setUsername('abcdefg1234567');
        $this->gateway->setPassword('6ef44f261a4a1595cd377d3ca7b57b92');
        $this->gateway->setTestMode(true);        

        $this->purchaseOptions = array(
            'amount'    => '10.00',
            'orderId'   => '123',
            'card'      => $this->getValidCard(),
        );

        $this->captureOptions  = array(
            'amount'               => '10.00',
            'transactionReference' => '3244053957',
        );
        $this->cardCreateReferenceOptions = array(
             'card' => $this->getValidCard()
        );
        $this->cardUpdateReferenceOptions = array(
             'cardReference' => '1376993339',
             'card'          => $this->getValidCard()
        );
        $this->cardDeleteReferenceOptions = array(
             'cardReference' => '1376993339'
        );
        $this->subscriptionAdd = array(
            "cardReference"          => "3247070784",
            "planId"                 => "1804286062",
            "subscriptionStartDay"   => 1,
            "subscriptionStartMonth" => 9,
            "subscriptionStartYear"  => 2016
        );
        $this->purchaseOptionsRef = array(
            'amount'        => '10.00',
            'orderId'       => '123',
            'cardReference' => '2108887363',
        );
        
        
        /* ACH */
        $this->ACHCreateReferenceOptions = array(
            'currency' => 'USD',
            'bankAccountPayee' => new ACH(GatewayTest::getValidACH())
        );
        $this->ACHUpdateReferenceOptions = array(
            'currency' => 'USD',
            'bankAccountPayee' => new ACH(GatewayTest::getValidACH()),
            'cardReference' => '784732899'
        );
        $this->ACHDeleteReferenceOptions = array(
            'cardReference' => '784732899'
        );
        $this->creditOptionsRef = array(
            'amount'        => '10.00',
            'cardReference' => '784732899'
        );
        $this->purchaseACHOptions = array(
            'amount'    => '10.00',
            'orderId'   => '123',
            'bankAccountPayee' => new ACH(GatewayTest::getValidACH())
        );
    }

    public function testGatewaySettersGetters()
    {
        $this->assertSame('abcdefg1234567', $this->gateway->getUsername());
        $this->assertSame('6ef44f261a4a1595cd377d3ca7b57b92', $this->gateway->getPassword());
        $this->assertSame(true, $this->gateway->getTestMode());
    }

    public function testAccountPayee() {
        $ach = new ACH();
        $this->gateway->setBankAccountPayee($ach);
        $this->assertSame($ach, $this->gateway->getBankAccountPayee());
    }

    public function testMerchantEndpoint() {
        $this->gateway->setMerchantEndpoint('other.endpoint.domain');
        $this->assertSame('other.endpoint.domain', $this->gateway->getMerchantEndpoint());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidCreditCardException
     * @expectedExceptionMessage The postcode parameter is required
     */
    public function testValidateMissingPostcode()
    {        
        $args = array(
            'amount'    => '10.00',
            'orderId'   => '123',
            'card'      => new \Omnipay\Common\CreditCard($this->getValidCard())
        );
        $args['card']->setPostcode(null);
        $this->setMockHttpResponse('CreditACHSuccess.txt');
        $response = $this->gateway->credit($args)->send();
    }

    /*public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');
        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('SUCCESS', $response->getResponseText());
        $this->assertSame('3244053957', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }
    
    public function testAuthorizeByRefSuccess()
    {
        $this->setMockHttpResponse('AuthorizeByRefSuccess.txt');
        $response = $this->gateway->authorize($this->purchaseOptionsRef)->send();
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('SUCCESS', $response->getResponseText());
        $this->assertSame('3247216792', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertSame('2108887363', $response->getCardReference());
    }
    
    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');
        $response = $this->gateway->authorize($this->purchaseOptions)->send();
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Transaction was rejected by gateway.; Duplicate transaction REFID:3188339697', $response->getMessage());
        $this->assertSame('Duplicate transaction REFID:3188339697', $response->getResponseText());
        $this->assertSame('Transaction was rejected by gateway.', $response->getCodeText());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(300, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }
    
    public function testCreditFailureNotEnabled()
    {
        $this->setMockHttpResponse('CreditFailureNotEnabled.txt');
        $response = $this->gateway->credit($this->purchaseOptions)->send();
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Transaction was rejected by gateway.; Credits are not enabled REFID:3188565408', $response->getMessage());
        $this->assertSame('Credits are not enabled REFID:3188565408', $response->getResponseText());
        $this->assertSame('Transaction was rejected by gateway.', $response->getCodeText());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(300, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }
    
    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');
        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('SUCCESS', $response->getResponseText());
        $this->assertSame('3244053957', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');
        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Transaction was rejected by gateway.; Duplicate transaction REFID:3188339697', $response->getMessage());
        $this->assertSame('Duplicate transaction REFID:3188339697', $response->getResponseText());
        $this->assertSame('Transaction was rejected by gateway.', $response->getCodeText());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(300, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }
    
    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');
        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('SUCCESS', $response->getResponseText());
        $this->assertSame('3244053957', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');
        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Transaction was rejected by gateway.; The specified amount of 5.00 exceeds the authorization amount of 1.00 REFID:3188494937', $response->getMessage());
        $this->assertSame('The specified amount of 5.00 exceeds the authorization amount of 1.00 REFID:3188494937', $response->getResponseText());
        $this->assertSame('Transaction was rejected by gateway.', $response->getCodeText());
        $this->assertSame('3244053957', $response->getTransactionReference());
        $this->assertSame(300, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->gateway->refund($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('SUCCESS', $response->getResponseText());
        $this->assertSame('3246990413', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }

    public function testRefundFailure()
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->gateway->refund($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Transaction was rejected by gateway.; Invalid Transaction ID / Object ID specified:  REFID:3188495243', $response->getMessage());
        $this->assertSame('Invalid Transaction ID / Object ID specified:  REFID:3188495243', $response->getResponseText());
        $this->assertSame('Transaction was rejected by gateway.', $response->getCodeText());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(300, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }

    public function testVoidSuccess()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');
        $response = $this->gateway->void($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('Transaction Void Successful', $response->getResponseText());
        $this->assertSame('3246978902', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }

    public function testVoidFailure()
    {
        $this->setMockHttpResponse('VoidFailure.txt');
        $response = $this->gateway->void($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Transaction was rejected by gateway.; Only transactions pending settlement can be voided REFID:3188494189', $response->getMessage());
        $this->assertSame('Only transactions pending settlement can be voided REFID:3188494189', $response->getResponseText());
        $this->assertSame('Transaction was rejected by gateway.', $response->getCodeText());
        $this->assertSame('3246978902', $response->getTransactionReference());
        $this->assertSame(300, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
    }
    */

    public function testCardCreateSuccess()
    {
        $this->setMockHttpResponse('CardCreateSuccess.txt');
        $response = $this->gateway->createCard($this->cardCreateReferenceOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertSame('784732899', $response->getCardReference());
    }
    
    public function testCardDeleteSuccess()
    {
        $this->setMockHttpResponse('CardCreateSuccess.txt');
        $response = $this->gateway->deleteCard($this->cardDeleteReferenceOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertSame('784732899', $response->getCardReference());
    }    
    
    public function testCardUpdateFailure()
    {
        $this->setMockHttpResponse('CardCreateFailure.txt');
        $response = $this->gateway->updateCard($this->cardUpdateReferenceOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        //$this->assertSame("Customer Hash missing", $response->getMessage());
        $this->assertSame('Transaction was declined by processor.', $response->getCodeText());
        $this->assertSame('DECLINED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(200, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Transaction was declined by processor.; DECLINED', $response->getMessage());
    }
    
    public function testCardUpdateSuccess()
    {
        $this->setMockHttpResponse('CardCreateSuccess.txt');
        $response = $this->gateway->updateCard($this->cardUpdateReferenceOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertSame('784732899', $response->getCardReference());
    }

    public function testAchCardCreateSuccess()
    {
        $this->setMockHttpResponse('CardCreateSuccess.txt');
        $response = $this->gateway->createACH($this->ACHCreateReferenceOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertSame('784732899', $response->getCardReference());
    }

    public function testAchCardUpdateSuccess()
    {
        $this->setMockHttpResponse('CardCreateSuccess.txt');
        $response = $this->gateway->updateACH($this->ACHUpdateReferenceOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertSame('784732899', $response->getCardReference());
    }

    public function testAchCardDeleteSuccess()
    {
        $this->setMockHttpResponse('CardCreateSuccess.txt');
        $response = $this->gateway->deleteACH($this->ACHUpdateReferenceOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('Transaction was approved.', $response->getCodeText());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertSame('784732899', $response->getCardReference());
    }

    public function testCreditSuccess()
    {
        $this->setMockHttpResponse('CreditACHSuccess.txt');
        $this->gateway->setMerchantEndpoint('other.endpoint.domain');
        $response = $this->gateway->credit($this->creditOptionsRef)->send();
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
        $this->assertSame('784732899', $response->getCardReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseACHSuccess.txt');
        $response = $this->gateway->purchase($this->purchaseACHOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('APPROVED', $response->getResponseText());
        $this->assertSame('3348271664', $response->getTransactionReference());
        $this->assertSame(100, $response->getCode());
    }
}
