<?php

namespace Omnipay\TotalAppsGateway\Message\Response;

/**
 * DeleteResponse
 */
class DeleteResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data->response) && $this->data->response == 1;
    }
}
