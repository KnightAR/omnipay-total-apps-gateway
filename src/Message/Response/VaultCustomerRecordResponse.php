<?php

namespace Omnipay\TotalAppsGateway\Message\Response;

use \Omnipay\Common\Message\RequestInterface;

/**
 * DeleteResponse
 */
class VaultCustomerRecordResponse extends Response
{
    /**
     * Constructor
     *
     * @param RequestInterface $request the initiating request.
     * @param mixed $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }
    
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data->customerHash);
    }
}
