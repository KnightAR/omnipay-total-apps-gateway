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
        $data = parent::getData();
        
        $this->validate('cardReference');
        
        $data['customer_vault_id'] = $this->getCardReference();
        
        return $data;
    }
}
