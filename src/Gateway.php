<?php

namespace Omnipay\TotalAppsGateway;

use Omnipay\Common\AbstractGateway;
use Guzzle\Http\Client as HttpClient;

/**
 * TotalAppsGateway Gateway
 */
class Gateway extends AbstractGateway
{
    /**
     * Get the global default HTTP client.
     *
     * @return HttpClient
     */
    protected function getDefaultHttpClient()
    {
        return new HttpClient(
            '',
            array(
                'curl.options' => array(
                    CURLOPT_CONNECTTIMEOUT => 60,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ),
            )
        );
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'TotalAppsGateway';
    }

   /**
     * Get the gateway default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'testMode' => false
        );
    }

    /**
     * Get the gateway username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the gateway username
     *
     * @param string username
     * @return interface.
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Set the getway password
     *
     * @param string password
     * @return interface
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get the gateway password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setBankAccountPayor($value)
    {
        return $this->setParameter('bankAccountPayor', $value);
    }
    
    public function getBankAccountPayor()
    {
        return $this->getParameter('bankAccountPayor');
    }
    
    /**
     * @param array $parameters
     * @return Message\Transaction\AuthorizeRequest
     * Authorize = auth
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\Transaction\PurchaseRequest
     * Purchase = sale
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\PurchaseRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Transaction\CreditRequest
     * Credit = credit
     */
    public function credit(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\CreditRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\Transaction\RefundRequest
     * Refund = refund
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\RefundRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultCreateRequest
     * Vault Create = auth
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultCreateRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultDeleteRequest
     * Vault Delete = delete
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultDeleteRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultUpdateRequest
     * Vault Update = update
     */
    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultUpdateRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultCustomerListRecordsRequest
     * Vault List = list_customer
     */
    public function listCards(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultCustomerListRecordsRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Transaction\CaptureRequest
     * Capture = capture
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\CaptureRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Transaction\VoidRequest
     * Void = void
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\VoidRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Subscription\SubscriptionAddRequest
     * Subscription Add = sub_add
     */
    public function subscription_add(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Subscription\SubscriptionAddRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Subscription\SubscriptionDeleteRequest
     * Subscription Add = delete_sub
     */
    public function subscription_delete(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Subscription\SubscriptionDeleteRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultAchCreateRequest
     * Vault ACH Create = add_customer
     */
    public function createACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultAchCreateRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultAchUpdateRequest
     * Vault ACH Update = update_customer
     */
    public function updateACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultAchUpdateRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultAchDeleteRequest
     * Vault ACH Delete = delete_customer
     */
    public function deleteACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultAchDeleteRequest', $parameters);
    }
    
    
}
