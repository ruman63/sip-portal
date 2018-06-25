<<<<<<< Updated upstream
<?php 
=======
<?php
>>>>>>> Stashed changes
namespace App;

class BseSoapClient extends \SoapClient
{
<<<<<<< Updated upstream
    private $url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
=======
    private $url = 'http://bsestarmf.in/MFOrderEntry/MFOrder.svc';
>>>>>>> Stashed changes
    private $action = 'http://bsestarmf.in/MFOrderEntry/';

    public function __construct()
    {
        parent::SoapClient($this->url . '?wsdl', [
            'soap_version' => SOAP_1_2,
            'exceptions' => true,
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);
    }

    public function __call($name, $arguments)
    {
        return $this->__soapCall($name, ['params' => $arguments[0]]);
    }

    public function __soapCall($name, $arguments, $options = null, $input_headers = null, &$output_headers = null)
    {
        $this->__setSoapHeaders($this->wsaHeaders($name));
<<<<<<< Updated upstream
        return parent::__soapCall($name, $arguments, $options, $input_headers, $output_headers);
=======
        $response = parent::__soapCall($name, $arguments, $options, $input_headers, $output_headers);
        return explode('|', $response->{$name . 'Result'});
>>>>>>> Stashed changes
    }

    private function wsaHeaders($method)
    {
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
