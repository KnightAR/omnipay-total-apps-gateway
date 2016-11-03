<?php

namespace Omnipay\TotalAppsGateway\Message\Vault;

use Omnipay\TotalAppsGateway\Message\Transaction\AuthorizeRequest;
use Omnipay\TotalAppsGateway\Message\Response\VaultCustomerListRecordsResponse;

class VaultCustomerListRecordsRequest extends AuthorizeRequest
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'list_customer';
    }
    
    /**
     * @return Array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $data = $this->getBaseData();
        
        $data['customerHash'] = $this->getCardReference();
        $data['firstName'] = $this->getFirstName();
        $data['lastName'] = $this->getLastName();
        $data['email'] = $this->getEmail();
        $data['last4cc'] = $this->getLast4cc();
        
        return $data;
    }
    
    /**
     * @param string $data
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new VaultCustomerListRecordsResponse($this, $data);
    }
    
    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }
    
    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getParameter('lastName');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLastName($value)
    {
        return $this->setParameter('lastName', $value);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * @return string
     */
    public function getLast4cc()
    {
        return $this->getParameter('last4cc');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLast4cc($value)
    {
        return $this->setParameter('last4cc', $value);
    }
}
