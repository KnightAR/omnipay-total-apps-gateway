<?php

namespace Omnipay\TotalAppsGateway\Message\Vault;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;

class VaultUpdateRequest extends VaultCreateRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'update_customer';
    }
    
    /**
     * @return array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('cardReference');
        $data = parent::getData();
        $data['customer_vault_id'] = $this->getCardReference();
        return $data;
    }
}
