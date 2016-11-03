<?php

namespace Omnipay\TotalAppsGateway\Message\Transaction;

class RefundRequest extends CaptureRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'refund';
    }
}
