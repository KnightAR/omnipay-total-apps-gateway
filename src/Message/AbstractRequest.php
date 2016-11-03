<?php

namespace Omnipay\TotalAppsGateway\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\TotalAppsGateway\Message\Response\Response;
use Omnipay\TotalAppsGateway\ACH;

/**
 * Abstract Request
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * @var string
     */
    protected $liveEndpoint = 'https://secure.total-apps-gateway.com/api/transact.php';

    /**
     * @var string
     */
    protected $testEndpoint = 'https://secure.total-apps-gateway.com/api/transact.php';

    /**
     * @return string
     */
    abstract public function getType();

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
     * @return AbstractRestRequest provides a fluent interface.
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
    * @return string
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

    /**
     * @return Array
     */
    public function getBaseData()
    {
        $this->validate('username', 'password');
        
        $data = array();
        $data['type'] = $this->getType();
        $data['username'] = $this->getUsername();
        $data['password'] = $this->getPassword();
        return $data;
    }

    /**
     * @param SimpleXMLElement $data
     * @return Response
     */
    public function sendData($data)
    {
        $headers      = array();
        $httpResponse = $this->httpClient->get($this->getEndpoint() .'?' . http_build_query($data), $headers)->send();
        return $this->createResponse($httpResponse->getBody());
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @param string $data
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
