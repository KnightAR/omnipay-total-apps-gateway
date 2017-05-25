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
        $this->validate('cardReference');
        $data = parent::getBaseData();
        $data['customer_vault_id'] = $this->getCardReference();
        return $data;
    }
}
