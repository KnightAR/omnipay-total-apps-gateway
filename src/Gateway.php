<?php

namespace Omnipay\TotalAppsGateway;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\Client;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\TotalAppsGateway\Message\AbstractRequest;

/**
 * TotalAppsGateway Gateway
 */
class Gateway extends AbstractGateway
{
    /**
     * Get the global default HTTP client.
     *
     * @return ClientInterface
     */
    protected function getDefaultHttpClient()
    {
        return new Client();
//        'curl.options' => array(
//        CURLOPT_CONNECTTIMEOUT => 60,
//        CURLOPT_SSL_VERIFYPEER => false,
//        CURLOPT_SSL_VERIFYHOST => false,
//    ),
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
            'apiKey' => null,
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
     * @return Gateway.
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Set the getway password
     *
     * @param $value
     * @return Gateway
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }


    /**
     * Get the gateway API Key.
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key.
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     *
     * Stripe accounts have test-mode API keys as well as live-mode
     * API keys. These keys can be active at the same time. Data
     * created with test-mode credentials will never hit the credit
     * card networks and will never cost anyone money.
     *
     * Unlike some gateways, there is no test mode endpoint separate
     * to the live mode endpoint, the Stripe API endpoint is the same
     * for test and for live.
     *
     * Setting the testMode flag on this gateway has no effect.  To
     * use test mode just use your test mode API key.
     *
     * @param string $value
     *
     * @return self provides a fluent interface.
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
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

    public function setBankAccountPayee($value)
    {
        return $this->setParameter('bankAccountPayee', $value);
    }

    public function getBankAccountPayee()
    {
        return $this->getParameter('bankAccountPayee');
    }

    public function setMerchantEndpoint($value)
    {
        return $this->setParameter('merchantEndpoint', $value);
    }

    public function getMerchantEndpoint()
    {
        return $this->getParameter('merchantEndpoint');
    }

    /**
     * @param array $parameters
     * @return Message\Transaction\AuthorizeRequest|\Omnipay\Common\Message\AbstractRequest
     * Authorize = auth
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\Transaction\PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     * Purchase = sale
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\PurchaseRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Transaction\CreditRequest|\Omnipay\Common\Message\AbstractRequest
     * Credit = credit
     */
    public function credit(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\CreditRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\Transaction\RefundRequest|\Omnipay\Common\Message\AbstractRequest
     * Refund = refund
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\RefundRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultCreateRequest|\Omnipay\Common\Message\AbstractRequest
     * Vault Create = auth
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultCreateRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultDeleteRequest|\Omnipay\Common\Message\AbstractRequest
     * Vault Delete = delete
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultDeleteRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Vault\VaultUpdateRequest|\Omnipay\Common\Message\AbstractRequest
     * Vault Update = update
     */
    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultUpdateRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\Transaction\CaptureRequest|\Omnipay\Common\Message\AbstractRequest
     * Capture = capture
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\CaptureRequest', $parameters);
    }
    
    /**
     * @param array $parameters
     * @return Message\Transaction\VoidRequest|\Omnipay\Common\Message\AbstractRequest
     * Void = void
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Transaction\VoidRequest', $parameters);
    }

    /**
     * DEPRECATED: Use createCard() with bankAccountPayee argument
     * @param array $parameters
     * @return Message\Vault\VaultCreateRequest|\Omnipay\Common\Message\AbstractRequest
     * Vault ACH Create = add_customer
     */
    public function createACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultCreateRequest', $parameters);
    }
    
    /**
     * DEPRECATED: Use updateCard() with bankAccountPayee argument
     * @param array $parameters
     * @return Message\Vault\VaultUpdateRequest|\Omnipay\Common\Message\AbstractRequest
     * Vault ACH Update = update_customer
     */
    public function updateACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultUpdateRequest', $parameters);
    }
    
    /**
     * DEPRECATED: Use deleteCard() with bankAccountPayee argument
     * @param array $parameters
     * @return Message\Vault\VaultDeleteRequest|\Omnipay\Common\Message\AbstractRequest
     * Vault ACH Delete = delete_customer
     */
    public function deleteACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultDeleteRequest', $parameters);
    }
}
