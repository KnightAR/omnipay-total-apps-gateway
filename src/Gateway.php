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
     * @codeCoverageIgnore
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
     * DEPRECATED: Use createCard() with bankAccountPayee argument
     * @param array $parameters
     * @return Message\Vault\VaultCreateRequest
     * Vault ACH Create = add_customer
     */
    public function createACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultCreateRequest', $parameters);
    }
    
    /**
     * DEPRECATED: Use updateCard() with bankAccountPayee argument
     * @param array $parameters
     * @return Message\Vault\VaultUpdateRequest
     * Vault ACH Update = update_customer
     */
    public function updateACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultUpdateRequest', $parameters);
    }
    
    /**
     * DEPRECATED: Use deleteCard() with bankAccountPayee argument
     * @param array $parameters
     * @return Message\Vault\VaultDeleteRequest
     * Vault ACH Delete = delete_customer
     */
    public function deleteACH(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TotalAppsGateway\Message\Vault\VaultDeleteRequest', $parameters);
    }
}
