<?php

namespace Omnipay\TotalAppsGateway\Message\Vault;

use Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;

class VaultCreateRequest extends AuthorizeRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'add_customer';
    }
    
    /**
     * @return array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $data = $this->getBaseData();
        unset($data['type']);
        $data['customer_vault'] = $this->getType();
        if ($this->getBankAccountPayee()) {
            $this->validate('currency');
            $data['sec_code'] = 'WEB';

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
}
