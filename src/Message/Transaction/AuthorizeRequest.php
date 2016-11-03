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

        $data['orderId'] = $this->getTransactionId();
        $data['orderDescription'] = $this->getDescription();
        $data['amount'] = $this->getAmount();

        if ($this->getCardReference()) {
            $data['customerHash'] = $this->getCardReference();
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
}
