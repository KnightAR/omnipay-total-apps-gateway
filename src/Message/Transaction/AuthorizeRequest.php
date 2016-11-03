<?php

namespace Omnipay\TotalAppsGateway\Message\Transaction;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\TotalAppsGateway\Message\AbstractRequest;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'auth';
    }

    /**
     * @return Array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBaseData();

        $data['orderid'] = $this->getTransactionId();
        $data['order_description'] = $this->getDescription();
        $data['amount'] = $this->getAmount();

        if ($this->getCardReference()) {
            $data['currency'] = $this->getCurrency();
            $data['customer_vault_id'] = $this->getCardReference();
        } elseif ($this->getBankAccountPayee()) {
            $this->setBankCredentials($data);
            $this->setBankShippingCredentials($data);
            $this->setBankHolderCredentials($data);
        } else {
            $this->setCardCredentials($data);
            $this->setShippingCredentials($data);
            $this->setCardHolderCredentials($data);
        }

        return $data;
    }
    
    /**
     * @param Array $data
     */
    protected function setCardCredentials(Array &$data)
    {
        $data['currency'] = $this->getCurrency();
        
        $card = $this->getCard();

        $card->validate();
        
        if (!$card->getPostcode()) {
            throw new InvalidCreditCardException("The postcode parameter is required");
        }

        $data['creditCardNumber'] = $card->getNumber();
        $data['expirationDate'] = $card->getExpiryDate('my');
        $data['cardSecurityCode'] = $card->getCvv();
        $data['zipPostalCodeCard'] = $card->getPostcode();
    }

    /**
     * @param Array $data
     */
    protected function setShippingCredentials(Array &$data)
    {
        $card = $this->getCard();

        $data['firstNameShipping'] = $card->getShippingFirstName();
        $data['lastNameShipping'] = $card->getShippingLastName();
        $data['companyShipping'] = $card->getShippingCompany();
        $data['countryShipping'] = $card->getShippingCountry();
        $data['addressShipping'] = $card->getShippingAddress1();
        $data['addressContShipping'] = $card->getShippingAddress2();
        $data['cityShipping'] = $card->getShippingCity();
        $data['stateProvinceShipping'] = $card->getShippingState();
        $data['zipPostalCodeShipping'] = $card->getShippingPostcode();
        $data['phoneNumberShipping'] = $card->getShippingPhone();
        $data['faxNumberShipping'] = $card->getShippingFax();
        $data['emailAddressShipping'] = $card->getEmail();
    }

    /**
     * @param Array $data
     */
    protected function setCardHolderCredentials(Array &$data)
    {
        $card = $this->getCard();
        
        $data['firstNameCard'] = $card->getFirstName();
        $data['lastNameCard'] = $card->getLastName();
        $data['companyCard'] = $card->getCompany();
        $data['countryCard'] = $card->getCountry();
        $data['addressCard'] = $card->getAddress1();
        $data['addressContCard'] = $card->getAddress2();
        $data['cityCard'] = $card->getCity();
        $data['stateProvinceCard'] = $card->getState();
        $data['zipPostalCodeCard'] = $card->getPostcode();
        $data['phoneNumberCard'] = $card->getPhone();
        $data['faxNumberCard'] = $card->getBillingFax();
        $data['emailAddressCard'] = $card->getEmail();
    }
    

    /**
     * @param Array $data
     */
    protected function setBankCredentials(Array &$data)
    {
        $data['currency'] = $this->getCurrency();
        
        $payee = $this->getBankAccountPayee();

        $payee->validate('billingAddress1', 'billingCity', 'billingState', 'billingPostcode', 'bankName', 'bankAddress', 'bankPhone', 'billingPhone', 'billingCountry');
        
        $data['checkname'] = $payee->getName();
        $data['checkaba'] = $payee->getRoutingNumber();
        $data['checkaccount'] = $payee->getAccountNumber();
        $data['account_holder_type'] = $payee->getBankHolderAccountType();
        $data['account_type'] = $payee->getBankAccountType();
    }

    /**
     * @param Array $data
     */
    protected function setBankShippingCredentials(Array &$data)
    {
        $payee = $this->getBankAccountPayee();

        $data['shipping_firstname'] = $payee->getShippingFirstName();
        $data['shipping_lastname'] = $payee->getShippingLastName();
        $data['shipping_company'] = $payee->getShippingCompany();
        $data['shipping_country'] = $payee->getShippingCountry();
        $data['shipping_address1'] = $payee->getShippingAddress1();
        $data['shipping_address2'] = $payee->getShippingAddress2();
        $data['shipping_city'] = $payee->getShippingCity();
        $data['shipping_state'] = $payee->getShippingState();
        $data['shipping_zip'] = $payee->getShippingPostcode();
        $data['shipping_phone'] = $payee->getShippingPhone();
        $data['shipping_fax'] = $payee->getShippingFax();
        $data['shipping_email'] = $payee->getEmail();
    }

    /**
     * @param Array $data
     */
    protected function setBankHolderCredentials(Array &$data)
    {
        $payee = $this->getBankAccountPayee();
        
        $data['first_name'] = $payee->getFirstName();
        $data['last_name'] = $payee->getLastName();
        $data['company'] = $payee->getCompany();
        $data['country'] = $payee->getCountry();
        $data['address1'] = $payee->getAddress1();
        $data['address2'] = $payee->getAddress2();
        $data['city'] = $payee->getCity();
        $data['state'] = $payee->getState();
        $data['zip'] = $payee->getPostcode();
        $data['phone'] = $payee->getPhone();
        $data['fax'] = $payee->getBillingFax();
        $data['email'] = $payee->getEmail();
    }
}
