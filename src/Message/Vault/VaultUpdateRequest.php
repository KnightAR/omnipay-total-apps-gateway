<?php

namespace Omnipay\TotalAppsGateway\Message\Vault;

class VaultUpdateRequest extends VaultCreateRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'update';
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
        
        $data['customerHash'] = $this->getCardReference();
        
        return $data;
    }
}
