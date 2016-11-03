<?php

namespace Omnipay\TotalAppsGateway\Message\Response;

/**
 * SubscriptionAddResponse
 */
class SubscriptionAddResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data->Response) && is_string($this->data->Response) && $this->data->Response === "Subscription created";
    }
}
