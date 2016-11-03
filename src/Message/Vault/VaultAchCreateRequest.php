<?php

namespace Omnipay\TotalAppsGateway\Message\Vault;

use Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest;

class VaultAchCreateRequest extends AuthorizeRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'add_customer';
    }
    
    /**
     * @return Array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('currency');
        
        $data = $this->getBaseData();
        unset($data['type']);
        $data['customer_vault'] = $this->getType();
        
        $this->setBankCredentials($data);
        $this->setShippingCredentials($data);
        $this->setBankHolderCredentials($data);
        
        return $data;
    }
    
    /**
     * @param Array $data
     */
    protected function setBankCredentials(Array &$data)
    {
        $data['currency'] = $this->getCurrency();
        
        $payee = $this->getBankAccountPayee();

        $payee->validate('billingAddress1', 'billingCity', 'billingState', 'billingPostcode', 'bankName', 'bankAddress', 'bankPhone', 'billingPhone');
        
        $data['checkname'] = $payee->getName();
        $data['checkaba'] = $payee->getRoutingNumber();
        $data['checkaccount'] = $payee->getAccountNumber();
        $data['account_holder_type'] = $payee->getBankHolderAccountType();
        $data['account_type'] = $payee->getBankAccountType();
    }

    /**
     * @param Array $data
     */
    protected function setShippingCredentials(Array &$data)
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
