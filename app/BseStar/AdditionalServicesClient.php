<?php
namespace App\BseStar;

use App\Exceptions\BseServicesException;

class AdditionalServicesClient extends BseClient
{
    protected $src = '/MFUploadService/MFUploadService.svc';
    protected $action = '/2016/01/IMFUploadService/';
    protected $toBasic = '/MFUploadService/MFUploadService.svc/Basic';
    protected $toSecure = '/MFUploadService/MFUploadService.svc/Secure';

    public function getPassword()
    {
        $response = $this->__soapCall('getPassword', ['params' => [
            'UserId' => config('services.bsestarmf.userId'),
            'MemberId' => config('services.bsestarmf.memberId'),
            'Password' => config('services.bsestarmf.password'),
            'PassKey' => $key = str_limit(md5(str_random(10)), 10, ''),
        ]]);

        if ($response[0] != '100') {
            throw new BseServicesException($response[1]);
        }
        return $response[1];
    }

    public function __call($name, $arguments)
    {
        if (!($flag = $this->getMethodFlag($name))) {
            throw new BseServicesException("Method '{$name}' is not available on this service");
        }
        $response = $this->__soapCall('MFAPI', ['params' => [
            'Flag' => $flag,
            'UserId' => config('services.bsestarmf.userId'),
            'EncryptedPassword' => $this->getPassword(),
            'param' => implode('|', $arguments[0]),
        ]]);
        if ($response[0] != '100') {
            throw new BseServicesException($response[1]);
        }
        return $response[1];
    }

    private function getMethodFlag($method)
    {
        if (!array_key_exists($method, $this->methodsFlag())) {
            return false;
        }
        return $this->methodsFlag()[$method];
    }

    private function methodsFlag()
    {
        return [
            'fatcaUpload' => '01',
            'createClient' => '02',
            'paymentGateway' => '03',
            'changePassword' => '04',
            'createMfiClient' => '05',
            'mandateRegistration' => '06',
            'stpRegistration' => '07',
            'swpRegistration' => '08',
            'orderPaymentStatus' => '11',
            'redemptionSMSAuthentication' => '12',
            'ckycUpload' => '13',
            'mandateStatus' => '14',
            'systematicPlanAuthentication' => '15',
        ];
    }
}
