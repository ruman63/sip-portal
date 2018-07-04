<?php
namespace App\BseStar;

class OrderEntryClient extends BseClient
{
    protected $src = '/MFOrderEntry/MFOrder.svc';
    protected $action = 'http://bsestarmf.in/MFOrderEntry/';
    protected $toBasic = '/MFOrderEntry/MFOrder.svc';
    protected $toSecure = '/MFOrderEntry/MFOrder.svc';

    public function __call($name, $arguments)
    {
        return $this->__soapCall($name, ['params' => $arguments[0]]);
    }
}
