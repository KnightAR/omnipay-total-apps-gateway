<?php

namespace Omnipay\TotalAppsGateway\Message\Transaction;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\TotalAppsGateway\Message\AbstractRequest;
use Omnipay\TotalAppsGateway\Message\Response\Response;

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
     * @return array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('amount');
        $data = $this->getBaseData();

        $data['orderid'] = $this->getTransactionReference();
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
     * @param array $data
     */
    protected function setCardCredentials(Array &$data)
    {
        $data['currency'] = $this->getCurrency();
        
        $card = $this->getCard();
        $card->validate();

        $data['ccnumber'] = $card->getNumber();
        $data['ccexp'] = $card->getExpiryDate('my');
        $data['cvv'] = $card->getCvv();
    }

    /**
     * @param array $data
     */
    protected function setShippingCredentials(Array &$data)
    {
        $card = $this->getCard();

        $data['shipping_firstname'] = $card->getShippingFirstName();
        $data['shipping_lastname'] = $card->getShippingLastName();
        $data['shipping_company'] = $card->getShippingCompany();
        $data['shipping_country'] = $card->getShippingCountry();
        $data['shipping_address1'] = $card->getShippingAddress1();
        $data['shipping_address2'] = $card->getShippingAddress2();
        $data['shipping_city'] = $card->getShippingCity();
        $data['shipping_state'] = $card->getShippingState();
        $data['shipping_zip'] = $card->getShippingPostcode();
        $data['shipping_phone'] = $card->getShippingPhone();
        $data['shipping_fax'] = $card->getShippingFax();
        $data['shipping_email'] = $card->getEmail();
    }

    /**
     * @param array $data
     */
    protected function setCardHolderCredentials(Array &$data)
    {
        $card = $this->getCard();

        $data['first_name'] = $card->getFirstName();
        $data['last_name'] = $card->getLastName();
        $data['company'] = $card->getCompany();
        $data['country'] = $card->getCountry();
        $data['address1'] = $card->getAddress1();
        $data['address2'] = $card->getAddress2();
        $data['city'] = $card->getCity();
        $data['state'] = $card->getState();
        $data['zip'] = $card->getPostcode();
        $data['phone'] = $card->getPhone();
        $data['fax'] = $card->getBillingFax();
        $data['email'] = $card->getEmail();
    }
    

    /**
     * @param array $data
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
     * @param array $data
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
     * @param array $data
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
