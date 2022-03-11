<?php

namespace Omnipay\TotalAppsGateway\Message\Transaction;

class VoidRequest extends AuthorizeRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'void';
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('transactionReference');

        $data = $this->getBaseData();

        $data['transactionid'] = $this->getTransactionReference();

        return $data;
    }
}
