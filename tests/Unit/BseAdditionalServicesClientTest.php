<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BseStar\AdditionalServicesClient;

class BseAdditionalServicesClientTest extends TestCase
{
    /** @test */
    public function use_additional_service_getPassword_method()
    {
        $client = new AdditionalServicesClient();
        $response = $client->getPassword([
            'UserId' => '1821101',
            'MemberId' => '18211',
            'Password' => '123456',
            'PassKey' => $pass = str_random(7),
        ]);
        $this->assertEquals(100, $response[0], $response[1]);
    }

    /** @test */
    public function use_create_client_ucc_mfd_02_service()
    {
        $client = new AdditionalServicesClient();
        $encryptedPassword = $client->getPassword([
            'UserId' => '1821101',
            'MemberId' => '18211',
            'Password' => '123456',
            'PassKey' => $pass = str_random(7),
        ])[1];

        $param = [
            'CLIENTCODE' => 'SW2',
            'MODE' => 'SI',
            'TAXSTATUS' => '01',
            'OCCUPATIONCODE' => '07',
            'NAME1' => 'RUMAN',
            'NAME2' => '',
            'NAME3' => '',
            'DOB' => '11/12/1995',
            'GENDER' => 'M',
            'FATHERHUSB' => 'Saleem',
            'PAN' => 'XYZUV1234W',
            'NOMINEE(O)' => '',
            'NOMINEEREL(O)' => '',
            'GUARDIANPAN(O)' => '',
            'CLIENTTYPE(P/D)' => 'D',
            'DEPOSITORY(NSDL-CDSL)' => 'CDSL',
            'CDSLDPID' => '12345678',
            'CDSLCLTID' => '1234567890123456',
            'NSDLDPID' => '',
            'NSDLCLTID' => '',
            'ACCTYPE 1(SB-CB-NE-NO)' => 'SB',
            'ACCCNO 1' => '32747044805',
            'MICRNO 1' => '123123124',
            'NEFT/IFSCCODE 1' => 'SBIN0000702',
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
            'CHEQUENAME' => 'Ruman',
            'ADDRESS1' => '164/2',
            'ADDRESS2' => 'KALKAJI',
            'ADDRESS3' => '',
            'CITY' => 'NEW DELHI',
            'STATE' => 'ND',
            'PIN' => '110019',
            'COUNTRY' => 'India',
            'RESIPHONE' => '',
            'RESIFAX' => '',
            'OFFPHONE' => '',
            'OFFFAX' => '',
            'EMAIL' => 'ruman63@gmail.com',
            'COMMODE - P/E ' => 'P',
            'DIVPAYMODE' => '01',
            'PAN2' => '',
            'PAN3' => '',
            'MAPINNO' => '324',
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
        $response = $client->MFAPI([
            'Flag' => '02',
            'UserId' => '1821101',
            'EncryptedPassword' => $encryptedPassword,
            'param' => implode('|', $param),
        ]);
        $this->assertEquals('100', $response[0], "Error: {$response[1]}");
    }
}
