<?php

$composerAutoload = dirname(__DIR__) .'/paypal/sdk/autoload.php';
require $composerAutoload;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

date_default_timezone_set(@date_default_timezone_get());

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

// Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/developer/applications/
//Produccion
//$clientId = 'AW_8f84IlNI9Ak-RqlBqdkv4UYy5wHiyeclVF7L2lnYUlW441S0dKv5bVJeLY_Ck1W9oDEy7WVpzikBB';
//$clientSecret = 'EI6dTrjjEIECjqHrvwupq3r-bVNVih4MEDse4RpAIlQQGuydUzAL526CRP88LqXmVvAacvc4TFMKpugn';
//Sandbox
$clientId = 'AS12HwutWE4_6ZHAqMM7o8WeXzomQjlS-uJzygdEJKyst_pPvzXTTeB3s3UferGxVxd-dCc7rzSbByut';
$clientSecret = 'EO-jx_uuIthzBVaf7hxMutSDbqbpFi5k7Nqb4CGPUi5a5DnTs9T7T-sHFfBm-pVfrGbMmxOP85slvUpe';

$apiContext = getApiContext($clientId, $clientSecret);

return $apiContext;

/**
 * Helper method for getting an APIContext for all calls
 * @param string $clientId Client ID
 * @param string $clientSecret Client Secret
 * @return PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret)
{

    $apiContext = new ApiContext(
        new OAuthTokenCredential(
            $clientId,
            $clientSecret
        )
    );

    $apiContext->setConfig(
        array(
            'mode' => 'sandbox', //live | sandbox
            'log.LogEnabled' => true,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'cache.enabled' => true,
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
        )
    );

    return $apiContext;
}
