<?php


namespace Omnipay\Paysafecard\Message\Response;


use Omnipay\Common\Message\AbstractResponse;

class AbstractPaysafecardResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return true;
    }
}
