<?php

namespace Omnipay\TotalAppsGateway\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\TotalAppsGateway\Message\Response\Response;

/**
 * Abstract Request
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * @var string
     */
    protected $liveEndpoint = '/api/transact.php';

    /**
     * @var string
     */
    protected $testEndpoint = '/api/transact.php';

    /**
     * @return string
     */
    abstract public function getType();

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
     * @return AbstractRequest provides a fluent interface.
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
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
     * @return AbstractRequest provides a fluent interface.
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
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

    /**
     * Set the gateway password
     *
     * @return AbstractRequest
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getBankAccountPayee()
    {
        return $this->getParameter('bankAccountPayee');
    }

    public function setBankAccountPayee($value)
    {
        return $this->setParameter('bankAccountPayee', $value);
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
     * @return array
     */
    public function getBaseData()
    {
        if ($this->getApiKey()) {
            $this->validate('apiKey');
        } else {
            $this->validate('username', 'password');
        }

        $data = array();

        $data['type'] = $this->getType();
        if ($this->getApiKey()) {
            $data['security_key'] = $this->getApiKey();
        } else {
            $data['username'] = $this->getUsername();
            $data['password'] = $this->getPassword();
        }
        return $data;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            isset($data['security_key']) ? 'POST' : 'GET',
            $this->getEndpoint() . ($this->getApiKey() ? '' : '?' . http_build_query($data)),
            array_filter([
                'User-Agent' => $this->getUserAgent(),
                'Content-Type' => isset($data['security_key']) ? 'application/x-www-form-urlencoded' : ''
            ]),
            isset($data['security_key']) ? http_build_query(array_filter($data), '', '&') : ''
        );
        return $this->createResponse($httpResponse->getBody());
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        $endpoint = 'secure.total-apps-gateway.com';
        if ($this->getMerchantEndpoint()) {
            $endpoint = $this->getMerchantEndpoint();
        }
        return sprintf('https://%s%s', $endpoint, ($this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint));
    }

    /**
     * @param string $data
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    protected function getUserAgent() {
        return 'Omnipay (Omnipay-TotalAppsGateway/'.PHP_VERSION.')';
    }
}