<?php

namespace Omnipay\TotalAppsGateway\Message\Response;

use \Omnipay\Common\Message\RequestInterface;

/**
 * DeleteResponse
 */
class VaultCustomerRecordResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return (isset($this->data->response) && $this->data->response == 1 && isset($this->data->customer_vault_id));
    }
    
    /**
     * Response getCustomerBankID
     *
     * @return null|string A response customer_vault_id from the gateway
     */
    public function getCustomerBankID()
    {
        return $this->getCardReference();
    }
}
