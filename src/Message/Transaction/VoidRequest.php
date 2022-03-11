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
        $this->validate('transactionId');

        $data = $this->getBaseData();

        $data['transactionid'] = $this->getTransactionId();

        return $data;
    }
}
