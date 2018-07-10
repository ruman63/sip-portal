<?php 
namespace App\BseStar;

abstract class BseClient extends \SoapClient
{
    const PRODUCTION_BASE_URL = 'http://bsestarmf.in';
    const DEVELOPMENT_BASE_URL = 'http://bsestarmfdemo.bseindia.com';
    protected $src;
    protected $action;
    protected $toBasic;
    protected $toSecure;

    public function __construct()
    {
        parent::SoapClient($this->resolveUrl($this->src) . '?wsdl', [
            'soap_version' => SOAP_1_2,
            'exceptions' => !config('services.bsestarmf.production'),
            'trace' => !config('services.bsestarmf.production'),
            // 'cache_wsdl' => WSDL_CACHE_NONE,
        ]);
    }

    protected function resolveUrl($url)
    {
        if (preg_match('~^http\://~', $url)) {
            return $url;
        }
        if (config('services.bsestarmf.production')) {
            return self::PRODUCTION_BASE_URL . $url;
        }
        return self::DEVELOPMENT_BASE_URL . $url;
    }

    public function __soapCall($name, $arguments, $options = null, $input_headers = null, &$output_headers = null)
    {
        $this->__setSoapHeaders($this->wsaHeaders($name));
        $response = parent::__soapCall($name, $arguments, $options, $input_headers, $output_headers);
        return explode('|', $response->{$name . 'Result'});
    }

    private function wsaHeaders($method)
    {
        $action = $this->resolveUrl($this->action . $method);
        $to = $this->resolveUrl(config('services.bsestarmf.production') ? $this->toSecure : $this->toBasic);
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
                $to,
                $mustUnderstand
            ),
        ];
    }
}
