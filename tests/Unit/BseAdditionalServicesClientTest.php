<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BseStar\AdditionalServicesClient;
use App\Exceptions\BseServicesException;

class BseAdditionalServicesClientTest extends TestCase
{
    /** @test */
    public function use_additional_service_getPassword_method()
    {
        $client = new AdditionalServicesClient();
        try {
            $password = $client->getPassword();
            $this->assertNotNull($password);
        } catch (BseServicesException $e) {
            $this->fail("getPassword Service failed! Received Error: {$e->getMessage()}");
        }
    }

    /** @test */
    public function use_create_client_mfd_service()
    {
        $client = new AdditionalServicesClient();
        $param = [
            'CLIENTCODE' => 'TEST1',
            'MODE' => 'SI',
            'TAXSTATUS' => '01',
            'OCCUPATIONCODE' => '07',
            'NAME1' => 'TEST CLIENT',
            'NAME2' => '',
            'NAME3' => '',
            'DOB' => '11/11/1993',
            'GENDER' => 'M',
            'FATHERHUSB' => 'TEST Father',
            'PAN' => 'TESTP0000N',
            'NOMINEE(O)' => '',
            'NOMINEEREL(O)' => '',
            'GUARDIANPAN(O)' => '',
            'CLIENTTYPE(P/D)' => 'D',
            'DEPOSITORY(NSDL-CDSL)' => 'CDSL',
            'CDSLDPID' => '',
            'CDSLCLTID' => '1234567890123456',
            'NSDLDPID' => '',
            'NSDLCLTID' => '',
            'ACCTYPE 1(SB-CB-NE-NO)' => 'SB',
            'ACCCNO 1' => '1234123412341234',
            'MICRNO 1' => '',
            'NEFT/IFSCCODE 1' => 'SBIN0000700',
            'DEFAULT 1 (Y/N)' => 'Y',
            'ACCTYPE 2(SB-CB-NE-NO)' => '',
            'ACCCNO 2' => '',
            'MICRNO 2' => '',
            'NEFT/IFSCCODE 2' => '',
            'DEFAULT 2 (Y/N)' => '',
            'ACCTYPE 3(SB-CB-NE-NO)' => '',
            'ACCCNO 3' => '',
            'MICRNO 3' => '',
            'NEFT/IFSCCODE 3' => '',
            'DEFAULT 3 (Y/N)' => '',
            'ACCTYPE 4(SB-CB-NE-NO)' => '',
            'ACCCNO 4' => '',
            'MICRNO 4' => '',
            'NEFT/IFSCCODE 4' => '',
            'DEFAULT 4 (Y/N)' => '',
            'ACCTYPE 5(SB-CB-NE-NO)' => '',
            'ACCCNO 5' => '',
            'MICRNO 5' => '',
            'NEFT/IFSCCODE 5' => '',
            'DEFAULT 5 (Y/N)' => '',
            'CHEQUENAME' => '',
            'ADDRESS1' => 'TEST H.NO',
            'ADDRESS2' => 'TEST Street',
            'ADDRESS3' => '',
            'CITY' => 'TEST CITY',
            'STATE' => 'ND',
            'PIN' => '110006',
            'COUNTRY' => 'India',
            'RESIPHONE' => '',
            'RESIFAX' => '',
            'OFFPHONE' => '',
            'OFFFAX' => '',
            'EMAIL' => 'test@example.com',
            'COMMODE - P/E ' => 'P',
            'DIVPAYMODE' => '01',
            'PAN2' => '',
            'PAN3' => '',
            'MAPINNO' => '1234',
            'CMFOR_ADDRESS1' => '',
            'CMFRO_ADDRESS2' => '',
            'CM_ADDRESS3' => '',
            'CM_CITY' => '',
            'CM_PIN' => '',
            'CM_STATE' => '',
            'FOR_COUNTRY' => '',
            'FOR_RESIPHONE' => '',
            'for_RESIFAX' => '',
            'FOR_OFFPHONE' => '',
            'FOR_OFFFAx' => '',
            'CM_MOBILE(!)' => '9876543210',
        ];
        try {
            $response = $client->createClient($param);
            $this->assertNotNull($response);
        } catch (BseServicesException $e) {
            $this->fail("createClient service failed with Error: {$e->getMessage()}");
        }
    }
}
