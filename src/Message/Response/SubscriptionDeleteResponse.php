<?php

namespace Omnipay\TotalAppsGateway\Message\Response;

/**
 * SubscriptionDeleteResponse
 */
class SubscriptionDeleteResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data->Response) && is_string($this->data->Response) && $this->data->Response === "Subscription successfully deleted";
    }
}
