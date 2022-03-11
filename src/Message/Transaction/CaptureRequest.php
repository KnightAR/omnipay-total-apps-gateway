<?php

namespace Omnipay\TotalAppsGateway\Message\Transaction;

class CaptureRequest extends AuthorizeRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'capture';
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'transactionId');

        $data = $this->getBaseData();

        $data['transactionid'] = $this->getTransactionId();
        $data['amount'] = $this->getAmount();

        return $data;
    }
}
