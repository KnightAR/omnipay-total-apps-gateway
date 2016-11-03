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
        $data['sec_code'] = 'WEB';
        
        $this->setBankCredentials($data);
        $this->setBankShippingCredentials($data);
        $this->setBankHolderCredentials($data);
        
        return $data;
    }
}
