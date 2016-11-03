<?php

namespace Omnipay\TotalAppsGateway\Message\Response;

use \Omnipay\Common\Message\RequestInterface;
/**
 * DeleteResponse
 */
class VaultCustomerListRecordsResponse extends Response
{
    /**
     * Constructor
     *
     * @param RequestInterface $request the initiating request.
     * @param mixed $data
     */
    public function __construct(RequestInterface $request, $raw_data)
    {
        parent::__construct($request, $raw_data);
        
        $this->data->Response = $this->getCustomerRecords();
    }
    
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data->Response) && is_array($this->data->Response);
    }
    
    /**
     * @return array|null
     */
    private function getCustomerRecords()
    {
        if (isset($this->data->Response) && is_array($this->data->Response)) {
            $output = array();
            foreach($this->data->Response as $data) {
                $output[] = new VaultCustomerRecordResponse($this->request, $data);
            }
            return $output;
        }
    }
    
    /**
     * @return array|null
     */
    public function getResponse()
    {
        return isset($this->data->Response) && is_array($this->data->Response) ? $this->data->Response : null;
    }
}
