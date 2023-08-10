<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require FCPATH . 'vendor/autoload.php';

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSLoader;


class Everify extends Frontend
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $decryptedtoken1='eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiIzZmQ3MzgwOS0wY2I3LTRjZDAtOGNkMi03NDE3MGY1OTRkYzYiLCJwd2RfYXV0aF9zdGF0dXMiOiJ0cnVlIiwiZ2VuZGVyIjoiTSIsInNlc3Npb25faWQiOiI5OGQ0ZjkwNC1kMDFhLTRhNjEtOTFmMy1lYTFhNTVlOGQwZDAiLCJzc29faWQiOiIzZmQ3MzgwOS0wY2I3LTRjZDAtOGNkMi03NDE3MGY1OTRkYzYiLCJkb2IiOiIwMVwvMDdcLzE5OTciLCJuYW1lIjoiUmFrc2hpdCBKb3NoaSIsInNvdHBfYXV0aF9zdGF0dXMiOiJudWxsIiwiZXhwIjoxNjU5NTA1NDczLCJtb2JpbGVfbnVtYmVyIjoiODk2MDUzMzY4OCIsImlhdCI6MTY1OTUwNTQ3MywianRpIjoiOThkNGY5MDQtZDAxYS00YTYxLTkxZjMtZWExYTU1ZThkMGQwIiwidXNlcm5hbWUiOiJyYWtzaGl0In0.RUEThIr1KbGtE2QiIQUCtuyjx41bLrCEK5QPdLooAgt1rMp98LhQQDbJgG1LazWI_oOo1dtbzIsVMEYV6j_zyXMifyqLMsIbqKcPO2zXSdnrSV18YD2cd1-YHqNux9bQYRSMt-X__NlGJSsXboAB_Crb4KY-R5flTPRUs6lPip6eyY3HzgItPD9yIB9B_zRFtBmolBEjVP72XSfmYYp1AYz_beNQg9Mvk_DgXgBOvKnen3RUXd04d96CLJ3W9-0p6-216j99rp2uc-DAfFLZAAfg82Y7yHKMUpr1ITJF0gullmLDpBYhzlLx-oiOpmDk6O4BzRk_cCTSPXHWaOaI8Q';
        // $decryptedtoken1  = $_COOKIE["decryptedtoken_c"];
        echo '<br>';
        echo $decryptedtoken1;
        echo '<br>';
        // The algorithm manager with the HS256 algorithm.
        $algorithmManager = new AlgorithmManager([
            new RS256(),
        ]);

        // We instantiate our JWS Verifier.
        $jwsVerifier = new JWSVerifier(
            $algorithmManager
        );

        $key = JWKFactory::createFromCertificateFile(
            'D:\epramaanprod2016.cer', // The filename
            [
                'use' => 'sig',         // Additional parameters
            ]
        );

        $serializerManager = new JWSSerializerManager([
            new CompactSerializer(),
        ]);

        $jws = $serializerManager->unserialize($decryptedtoken1);
        // // We verify the signature. This method does NOT check the header.
        // // The arguments are:
        // // - The JWS object,
        // // - The key,
        // // - The index of the signature to check. See 
        $isVerified = $jwsVerifier->verifyWithKey($jws, $key, 0);
        //var_dump($isVerified);

        echo '<br>';


        $jwsLoader = new JWSLoader(
            $serializerManager,
            $jwsVerifier,
            null
        );

        $jws = $jwsLoader->loadAndVerifyWithKey($decryptedtoken1, $key, $signature);
        //var_dump($jws);

        $payload = $jws->getPayload();
        echo $payload;
        echo '<br>';
        echo '<br>';
        $data = json_decode($payload);
        echo 'Name--' . $data->name;
        echo '<br>';
        echo 'Username--' . $data->username;
        echo '<br>';
    }
}
