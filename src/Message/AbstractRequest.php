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
    protected $liveEndpoint = 'https://core.mojopay.com/merchantapi';

    /**
     * @var string
     */
    protected $testEndpoint = 'https://stagingcore.mojopay.com:80/merchantapi';

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return $this
     */
    public function getProcessorId()
    {
        return $this->getParameter('processorId');
    }
    
    /**
     * @param string $value
     * @return $this
     */
    public function setProcessorId($value)
    {
        return $this->setParameter('processorId', $value);
    }

    /**
     * @return Array
     */
    public function getBaseData()
    {
        $this->validate('token', 'processorId');
        
        $data = array();
        $data['type'] = $this->getType();
        $data['token'] = $this->getToken();
        $data['processorId'] = $this->getProcessorId();
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
