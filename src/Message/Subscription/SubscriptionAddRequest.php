<?php

namespace Omnipay\TotalAppsGateway\Message\Subscription;

use Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest;
use Omnipay\TotalAppsGateway\Message\Response\SubscriptionAddResponse;

class SubscriptionAddRequest extends AuthorizeRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'add_sub';
    }
    
    /**
     * @return Array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('cardReference', 'planId', 'subscriptionStartDay', 'subscriptionStartMonth', 'subscriptionStartYear');
        
        $data = $this->getBaseData();

        $data['planId'] = $this->getPlanId();
        $data['customerHash'] = $this->getCardReference();
        $data['startDate'] = $this->getSubscriptionStartDate('Ymd');
        $data['orderId'] = $this->getTransactionId();
        $data['orderDescription'] = $this->getDescription();
        
        return $data;
    }

    /**
     * @return string
     */
    public function getPlanId()
    {
        return $this->getParameter('planId');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPlanId($value)
    {
        return $this->setParameter('planId', $value);
    }
        
    /**
     * @return string
     */
    public function getSubscriptionId()
    {
        return $this->getParameter('subscriptionId');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSubscriptionId($value)
    {
        return $this->setParameter('subscriptionId', $value);
    }
    
    /**
     * @param string $data
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new SubscriptionAddResponse($this, $data);
    }
    
    /**
     * Get the card start month.
     *
     * @return string
     */
    public function getSubscriptionStartDay()
    {
        return $this->getParameter('subscriptionStartDay');
    }
    
    /**
     * Sets the subscription start month.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
    public function setSubscriptionStartDay($value)
    {
        return $this->setParameter('subscriptionStartDay', (int) $value);
    }

    /**
     * Get the card start month.
     *
     * @return string
     */
    public function getSubscriptionStartMonth()
    {
        return $this->getParameter('subscriptionStartMonth');
    }
    
    /**
     * Sets the subscription start month.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
    public function setSubscriptionStartMonth($value)
    {
        return $this->setParameter('subscriptionStartMonth', (int) $value);
    }
    
    /**
     * Get the subscription start year.
     *
     * @return string
     */
    public function getSubscriptionStartYear()
    {
        return $this->getParameter('subscriptionStartYear');
    }
    
    /**
     * Sets the subscription start year.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
    public function setSubscriptionStartYear($value)
    {
        return $this->setParameter('subscriptionStartYear', $value);
    }
    
    /**
     * Get the subscription start date, using the specified date format string
     *
     * @param string $format
     *
     * @return string
     */
    public function getSubscriptionStartDate($format)
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->getSubscriptionStartMonth(), $this->getSubscriptionStartDay(), $this->getSubscriptionStartYear()));
    }
}
