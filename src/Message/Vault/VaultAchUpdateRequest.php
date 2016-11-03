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
        
        $this->setBankCredentials($data);
        $this->setShippingCredentials($data);
        $this->setBankHolderCredentials($data);
        
        return $data;
    }
}
