<?php

namespace Omnipay\TotalAppsGateway\Test;

use Omnipay\TotalAppsGateway\ACH;
use Omnipay\TotalAppsGateway\Gateway;
use Omnipay\Tests\TestCase;
use Omnipay\TotalAppsGateway\Message\Response\VaultCustomerListRecordsResponse;
use Omnipay\TotalAppsGateway\Message\Response\VaultCustomerRecordResponse;

class ACHTest extends TestCase
{

    /**
     * @var ACH
     */
    protected $ach;

    public function setUp(): void
    {
        parent::setUp();

        $this->ach = new ACH();
        $this->ach->setAccountNumber("1234-567890");
        $this->ach->setRoutingNumber("1234-56789");
        $this->ach->setBankName("National Bank");
        $this->ach->setBankAccountType(ACH::ACCOUNT_TYPE_CHECKING);
        $this->ach->setBankHolderAccountType(ACH::ACCOUNT_HOLDER_TYPE_PERSONAL);
        $this->ach->setBillingFirstName("John");
        $this->ach->setBillingLastName("Doe");
        $this->ach->setName("John Doe");
        $this->ach->setPhone("11234567890");
        $this->ach->setBillingAddress1("15505 Pennsylvania Ave.");
        $this->ach->setBillingCity("Washington DC");
        $this->ach->setBillingName("FED-Payor");
        $this->ach->setBillingPostcode("20003");
        $this->ach->setBillingState("DC, NE");
        $this->ach->setCompany("DAB2LLC");
        $this->ach->validate();
    }

    public function testValidateFixture() {
        $this->assertInstanceOf('Omnipay\TotalAppsGateway\ACH', $this->ach);
        $this->assertSame(null, $this->ach->validate());
    }

    public function testValidateBankAccountTypeRequired()
    {
        $this->expectExceptionMessage("The bank account type is not in the supported list.");
        $this->expectException(\Omnipay\TotalAppsGateway\InvalidACHException::class);
        $this->ach->setBankAccountType(null);
        $this->ach->validate();
    }

    public function testValidateBankHolderAccountTypeRequired()
    {
        $this->expectExceptionMessage("The bank holder type is not in the supported list.");
        $this->expectException(\Omnipay\TotalAppsGateway\InvalidACHException::class);
        $this->ach->setBankHolderAccountType(null);
        $this->ach->validate();
    }

    public function testValidateAccountNumberRequired()
    {
        $this->expectExceptionMessage("The account number is required.");
        $this->expectException(\Omnipay\TotalAppsGateway\InvalidACHException::class);
        $this->ach->setAccountNumber(null);
        $this->ach->validate();
    }

    public function testValidateRountingNumberRequired()
    {
        $this->expectExceptionMessage("The routing number is required.");
        $this->expectException(\Omnipay\TotalAppsGateway\InvalidACHException::class);
        $this->ach->setRoutingNumber(null);
        $this->ach->validate();
    }

    public function testValidateAccountName()
    {
        $this->expectExceptionMessage("The account name is required.");
        $this->expectException(\Omnipay\TotalAppsGateway\InvalidACHException::class);
        $this->ach->setName(null);
        $this->ach->validate();
    }

    public function testValidateNumber()
    {
        $this->expectExceptionMessage("The bank name is required.");
        $this->expectException(\Omnipay\TotalAppsGateway\InvalidACHException::class);
        $this->ach->setBankName(null);
        $this->ach->validate();
    }

    public function testValidateParamatersFailure()
    {
        $this->expectExceptionMessage("The value parameter is required");
        $this->expectException(\Omnipay\TotalAppsGateway\InvalidACHException::class);
        $this->ach->validate('value');
    }

    public function testConstructWithParams()
    {
        $ach = new ACH(array('name' => 'Test Customer'));
        $this->assertSame('Test Customer', $ach->getName());
    }

    public function testInitializeWithParams()
    {
        $ach = new ACH;
        $ach->initialize(array('name' => 'Test Customer'));
        $this->assertSame('Test Customer', $ach->getName());
    }

    public function testGetParamters()
    {
        $ach = new ACH(array(
            'name' => 'Example Customer',
            'accountnumber' => '1234',
            'routingnumber' => '5678',
            'bankaccounttype' => ACH::ACCOUNT_TYPE_CHECKING,
            'bankholderaccounttype' => ACH::ACCOUNT_HOLDER_TYPE_PERSONAL
        ));

        $parameters = $ach->getParameters();
        $this->assertSame('Example', $parameters['billingFirstName']);
        $this->assertSame('Customer', $parameters['billingLastName']);
        $this->assertSame('1234', $parameters['accountNumber']);
        $this->assertSame('5678', $parameters['routingNumber']);
        $this->assertSame(ACH::ACCOUNT_TYPE_CHECKING, $parameters['bankAccountType']);
        $this->assertSame(ACH::ACCOUNT_HOLDER_TYPE_PERSONAL, $parameters['bankHolderAccountType']);
    }

    public function testFirstName()
    {
        $this->ach->setFirstName('Bob');
        $this->assertEquals('Bob', $this->ach->getFirstName());
    }

    public function testLastName()
    {
        $this->ach->setLastName('Smith');
        $this->assertEquals('Smith', $this->ach->getLastName());
    }

    public function testGetName()
    {
        $this->ach->setFirstName('Bob');
        $this->ach->setLastName('Smith');
        $this->assertEquals('Bob Smith', $this->ach->getName());
    }

    public function testSetName()
    {
        $this->ach->setName('Bob Smith');
        $this->assertEquals('Bob', $this->ach->getFirstName());
        $this->assertEquals('Smith', $this->ach->getLastName());
    }

    public function testSetNameWithOneName()
    {
        $this->ach->setName('Bob');
        $this->assertEquals('Bob', $this->ach->getFirstName());
        $this->assertEquals('', $this->ach->getLastName());
    }

    public function testSetNameWithMultipleNames()
    {
        $this->ach->setName('Bob John Smith');
        $this->assertEquals('Bob', $this->ach->getFirstName());
        $this->assertEquals('John Smith', $this->ach->getLastName());
    }

    /* Account Number */
    public function testGetAccountNumberLastFourNull()
    {
        $this->ach->setAccountNumber(null);
        $this->assertNull($this->ach->getNumberLastFour());
    }

    public function testGetAccountNumberLastFour()
    {
        $this->ach->setAccountNumber('4000000000001234');
        $this->assertSame('1234', $this->ach->getNumberLastFour());
    }

    public function testGetAccountNumberLastFourNonDigits()
    {
        $this->ach->setAccountNumber('4000 0000 0000 12x34');
        $this->assertSame('1234', $this->ach->getNumberLastFour());
    }

    /* Bank Phone */
    public function testBankPhone()
    {
        $this->ach->setBankPhone('12345');
        $this->assertSame('12345', $this->ach->getBankPhone());       
    }

    /* Bank Address */
    public function testBankAddress()
    {
        $this->ach->setBankAddress('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->ach->getBankAddress());
    }    

    /* Issuer Number */
    public function testIssueNumber()
    {
        $this->ach->setIssueNumber('1234567890');
        $this->assertEquals('1234567890', $this->ach->getIssueNumber());
    }

    /* Billing Company */
    public function testCompany()
    {
        $this->ach->setCompany('FooBar');
        $this->assertEquals('FooBar', $this->ach->getCompany());
        $this->assertEquals('FooBar', $this->ach->getBillingCompany());
        $this->assertEquals('FooBar', $this->ach->getShippingCompany());
    }

    public function testBillingCompany()
    {
        $this->ach->setBillingCompany('SuperSoft');
        $this->assertEquals('SuperSoft', $this->ach->getBillingCompany());
        $this->assertEquals('SuperSoft', $this->ach->getCompany());
    }

    public function testBillingAddress1()
    {
        $this->ach->setBillingAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->ach->getBillingAddress1());
        $this->assertEquals('31 Spooner St', $this->ach->getAddress1());
    }

    public function testBillingAddress2()
    {
        $this->ach->setBillingAddress2('Suburb');
        $this->assertEquals('Suburb', $this->ach->getBillingAddress2());
        $this->assertEquals('Suburb', $this->ach->getAddress2());
    }

    public function testBillingCity()
    {
        $this->ach->setBillingCity('Quahog');
        $this->assertEquals('Quahog', $this->ach->getBillingCity());
        $this->assertEquals('Quahog', $this->ach->getCity());
    }

    public function testBillingPostcode()
    {
        $this->ach->setBillingPostcode('12345');
        $this->assertEquals('12345', $this->ach->getBillingPostcode());
        $this->assertEquals('12345', $this->ach->getPostcode());
    }

    public function testBillingState()
    {
        $this->ach->setBillingState('RI');
        $this->assertEquals('RI', $this->ach->getBillingState());
        $this->assertEquals('RI', $this->ach->getState());
    }

    public function testBillingCountry()
    {
        $this->ach->setBillingCountry('US');
        $this->assertEquals('US', $this->ach->getBillingCountry());
        $this->assertEquals('US', $this->ach->getCountry());
    }

    public function testBillingPhone()
    {
        $this->ach->setBillingPhone('12345');
        $this->assertSame('12345', $this->ach->getBillingPhone());
        $this->assertSame('12345', $this->ach->getPhone());
    }

    public function testBillingFax()
    {
        $this->ach->setBillingFax('54321');
        $this->assertSame('54321', $this->ach->getBillingFax());
        $this->assertSame('54321', $this->ach->getFax());
    }

    public function testShippingFirstName()
    {
        $this->ach->setShippingFirstName('James');
        $this->assertEquals('James', $this->ach->getShippingFirstName());
    }

    public function testShippingLastName()
    {
        $this->ach->setShippingLastName('Doctor');
        $this->assertEquals('Doctor', $this->ach->getShippingLastName());
    }

    public function testShippingName()
    {
        $this->ach->setShippingFirstName('Bob');
        $this->ach->setShippingLastName('Smith');
        $this->assertEquals('Bob Smith', $this->ach->getShippingName());

        $this->ach->setShippingName('John Foo');
        $this->assertEquals('John', $this->ach->getShippingFirstName());
        $this->assertEquals('Foo', $this->ach->getShippingLastName());
    }

    public function testShippingCompany()
    {
        $this->ach->setShippingCompany('SuperSoft');
        $this->assertEquals('SuperSoft', $this->ach->getShippingCompany());
    }

    public function testShippingAddress1()
    {
        $this->ach->setShippingAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->ach->getShippingAddress1());
    }

    public function testShippingAddress2()
    {
        $this->ach->setShippingAddress2('Suburb');
        $this->assertEquals('Suburb', $this->ach->getShippingAddress2());
    }

    public function testShippingCity()
    {
        $this->ach->setShippingCity('Quahog');
        $this->assertEquals('Quahog', $this->ach->getShippingCity());
    }

    public function testShippingPostcode()
    {
        $this->ach->setShippingPostcode('12345');
        $this->assertEquals('12345', $this->ach->getShippingPostcode());
    }

    public function testShippingState()
    {
        $this->ach->setShippingState('RI');
        $this->assertEquals('RI', $this->ach->getShippingState());
    }

    public function testShippingCountry()
    {
        $this->ach->setShippingCountry('US');
        $this->assertEquals('US', $this->ach->getShippingCountry());
    }

    public function testShippingPhone()
    {
        $this->ach->setShippingPhone('12345');
        $this->assertEquals('12345', $this->ach->getShippingPhone());
    }

    public function testShippingFax()
    {
        $this->ach->setShippingFax('54321');
        $this->assertEquals('54321', $this->ach->getShippingFax());
    }

    public function testAddress1()
    {
        $this->ach->setAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->ach->getAddress1());
        $this->assertEquals('31 Spooner St', $this->ach->getBillingAddress1());
        $this->assertEquals('31 Spooner St', $this->ach->getShippingAddress1());
    }

    public function testAddress2()
    {
        $this->ach->setAddress2('Suburb');
        $this->assertEquals('Suburb', $this->ach->getAddress2());
        $this->assertEquals('Suburb', $this->ach->getBillingAddress2());
        $this->assertEquals('Suburb', $this->ach->getShippingAddress2());
    }

    public function testCity()
    {
        $this->ach->setCity('Quahog');
        $this->assertEquals('Quahog', $this->ach->getCity());
        $this->assertEquals('Quahog', $this->ach->getBillingCity());
        $this->assertEquals('Quahog', $this->ach->getShippingCity());
    }

    public function testPostcode()
    {
        $this->ach->setPostcode('12345');
        $this->assertEquals('12345', $this->ach->getPostcode());
        $this->assertEquals('12345', $this->ach->getBillingPostcode());
        $this->assertEquals('12345', $this->ach->getShippingPostcode());
    }

    public function testState()
    {
        $this->ach->setState('RI');
        $this->assertEquals('RI', $this->ach->getState());
        $this->assertEquals('RI', $this->ach->getBillingState());
        $this->assertEquals('RI', $this->ach->getShippingState());
    }

    public function testCountry()
    {
        $this->ach->setCountry('US');
        $this->assertEquals('US', $this->ach->getCountry());
        $this->assertEquals('US', $this->ach->getBillingCountry());
        $this->assertEquals('US', $this->ach->getShippingCountry());
    }

    public function testPhone()
    {
        $this->ach->setPhone('12345');
        $this->assertEquals('12345', $this->ach->getPhone());
        $this->assertEquals('12345', $this->ach->getBillingPhone());
        $this->assertEquals('12345', $this->ach->getShippingPhone());
    }

    public function testFax()
    {
        $this->ach->setFax('54321');
        $this->assertEquals('54321', $this->ach->getFax());
        $this->assertEquals('54321', $this->ach->getBillingFax());
        $this->assertEquals('54321', $this->ach->getShippingFax());
    }

    public function testEmail()
    {
        $this->ach->setEmail('adrian@example.com');
        $this->assertEquals('adrian@example.com', $this->ach->getEmail());
    }

    public function testBirthday()
    {
        $this->ach->setBirthday('01-02-2000');
        $this->assertEquals('2000-02-01', $this->ach->getBirthday());
        $this->assertEquals('01/02/2000', $this->ach->getBirthday('d/m/Y'));
    }

    public function testBirthdayEmpty()
    {
        $this->ach->setBirthday('');
        $this->assertNull($this->ach->getBirthday());
    }

    public function testGender()
    {
        $this->ach->setGender('female');
        $this->assertEquals('female', $this->ach->getGender());
    }

    /* ACH account types */
    public function testAccountHolderTypeBusinessChecking()
    {
        $this->assertSame(ACH::ACCOUNT_HOLDER_TYPE_BUSINESS, $this->ach->getAccountHolderTypeBusinessChecking());
    }

    public function testAccountHolderTypePersonalChecking()
    {
        $this->assertSame(ACH::ACCOUNT_HOLDER_TYPE_PERSONAL, $this->ach->getAccountHolderTypePersonalChecking());
    }

    public function testAccountTypeSavings()
    {
        $this->assertSame(ACH::ACCOUNT_TYPE_SAVINGS, $this->ach->getAccountTypeSavings());
    }

    public function testAccountTypeChecking()
    {
        $this->assertSame(ACH::ACCOUNT_TYPE_CHECKING, $this->ach->getAccountTypeChecking());
    }

    public function testGetSupportedAccountTypes()
    {
        $types = array_flip($this->ach->getSupportedAccountType());
        $this->assertIsArray($types);
        $this->assertArrayHasKey(ACH::ACCOUNT_TYPE_CHECKING, $types);
    }

    public function testGetSupportedHolderAccountTypes()
    {
        $types = array_flip($this->ach->getSupportedHolderAccountType());
        $this->assertIsArray($types);
        $this->assertArrayHasKey(ACH::ACCOUNT_HOLDER_TYPE_BUSINESS, $types);
    }
}