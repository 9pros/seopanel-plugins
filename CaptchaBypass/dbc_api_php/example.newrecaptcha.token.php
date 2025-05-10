<?php
/**
 * Death by Captcha PHP API recaptcha token image usage example
 *
 * @package DBCAPI
 * @subpackage PHP
 */

/**
 * DBC API clients
 */
require_once 'deathbycaptcha.php';

// Put your DBC username & password here.
$username = "username";
$password = "password";
// Use DeathByCaptcha_HttpClient() class if you want to use HTTP API.
$client = new DeathByCaptcha_HttpClient($username, $password);
$client->is_verbose = true;

echo "Your balance is {$client->balance} US cents\n";

// To use recaptcha_Token
// Set the proxy and reCaptcha token data
$data = array(
    'proxy'      => 'http://user:password@127.0.0.1:1234',
    'proxytype'    => 'HTTP',
    'googlekey'       => '6Lc2fhwTAAAAAGatXTzFYfvlQMI2T7B6ji8UVV_b',
    'pageurl' => 'http://google.com');
//Create a json string
$json = json_encode($data);

//Put the type and the json payload
$extra = [
    'type'=>4,
    'token_params'=> $json,  # banner img
    ];

// Put null the first parameter and add the extra payload
if ($captcha = $client->decode(null, $extra)) {
    echo "CAPTCHA {$captcha['captcha']} uploaded\n";

    sleep(DeathByCaptcha_Client::DEFAULT_TIMEOUT);

        // Poll for CAPTCHA indexes:
        if ($text = $client->get_text($captcha['captcha'])) {
            echo "CAPTCHA {$captcha['captcha']} solved: {$text}\n";

            // Report an incorrectly solved CAPTCHA.
            // Make sure the CAPTCHA was in fact incorrectly solved!
            //$client->report($captcha['captcha']);
        }
    }
?>
