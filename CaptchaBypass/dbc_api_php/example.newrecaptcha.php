<?php
/**
 * Death by Captcha PHP API recaptcha coordinates usage example
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
$captcha_filename = "test.jpg"; # your image here
$extra = ['type'=>2];   # extra parameters in an array
// Use DeathByCaptcha_HttpClient() class if you want to use HTTP API.
$client = new DeathByCaptcha_SocketClient($username, $password);
$client->is_verbose = true;

echo "Your balance is {$client->balance} US cents\n";

// Put your CAPTCHA image file name, file resource, or vector of bytes,
// and optional solving timeout (in seconds) here; you'll get CAPTCHA
// details array on success.
if ($captcha = $client->decode($captcha_filename, $extra)) {
    echo "CAPTCHA {$captcha['captcha']} uploaded\n";

    sleep(DeathByCaptcha_Client::DEFAULT_TIMEOUT);

        // Poll for CAPTCHA coordinates:
        if ($text = $client->get_text($captcha['captcha'])) {
            echo "CAPTCHA {$captcha['captcha']} solved: {$text}\n";

            // Report an incorrectly solved CAPTCHA.
            // Make sure the CAPTCHA was in fact incorrectly solved!
            //$client->report($captcha['captcha']);
        }
    }

// To use recaptcha_image_group
// follow do the same just updating the extra parameters

$captcha_filename = "test2.jpg";
$extra = [
    'type'=>3,
    'banner'=> 'banner.jpg',  # banner img
    'banner_text'=> "select all pizza:"  # banner text 
     #'grid' => "3x2" #optional paramater for specifying what grid 
                      #images are aligned to. 
                      #If ommitted, dbc would try to autodetect the grid.
];
if ($captcha = $client->decode($captcha_filename, $extra)) {
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
