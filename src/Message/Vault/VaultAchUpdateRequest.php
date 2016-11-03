<?php

namespace Omnipay\TotalAppsGateway\Message\Vault;

class VaultAchUpdateRequest extends VaultAchCreateRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'update_customer';
    }
    
    /**
     * @return Array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('currency', 'cardReference');
        
        $data = $this->getBaseData();
        unset($data['type']);
        $data['customer_vault'] = $this->getType();
        $data['customer_vault_id'] = $this->getCardReference();
        $data['sec_code'] = 'WEB';
        
        $this->setBankCredentials($data);
        $this->setBankShippingCredentials($data);
        $this->setBankHolderCredentials($data);
        
        return $data;
    }
}
