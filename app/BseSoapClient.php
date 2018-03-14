<?php
namespace App;

class BseSoapClient extends \SoapClient {

    private $url = "http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc";
    private $action = "http://bsestarmf.in/MFOrderEntry/";

    public function __construct()
    {
        parent::SoapClient($this->url . '?wsdl', [
            'soap_version' => SOAP_1_2,
            'exceptions' => true,
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);
    }

    public function __call($name, $arguments) {
        return $this->__soapCall($name, [ 'params' => $arguments[0] ]);
    }
    
    public function __soapCall($name, $arguments, $options = NULL, $input_headers = NULL, &$output_headers = NULL)
    {
        $this->__setSoapHeaders( $this->wsaHeaders($name) );
        return parent::__soapCall($name, $arguments, $options, $input_headers, $output_headers);
    }


    private function wsaHeaders($method) {

        $action = $this->action . $method;
                
        return [
            new \SoapHeader(
                'http://www.w3.org/2005/08/addressing', 
                'Action', 
                $action,
                $mustUnderstand = true
            ),
            new \SoapHeader(
                'http://www.w3.org/2005/08/addressing', 
                'To', 
                $this->url,
                $mustUnderstand
            ),
        ];
    }
}