<?php

namespace Omnipay\TotalAppsGateway\Message\Subscription;

use Omnipay\TotalAppsGateway\Message\Response\SubscriptionDeleteResponse;

class SubscriptionDeleteRequest extends SubscriptionAddRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'delete_sub';
    }
    
    /**
     * @return Array
     */
    public function getData()
    {
        $data = $this->getBaseData();
        if ($this->getSubscriptionId()) {
            $this->validate('subscriptionId');
            $data['subscriptionId'] = $this->getSubscriptionId();
        } else {
            $this->validate('cardReference', 'planId');
            $data['customerHash'] = $this->getCardReference();
            $data['planId'] = $this->getPlanId();
        }
        return $data;
    }
    
    /**
     * @param string $data
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new SubscriptionDeleteResponse($this, $data);
    }
}
