<?php namespace Sb89\Recaptcha;

define("RECAPTCHA_VERIFY_SERVER", "www.google.com");

/**
 * Object that handles sending and receiving a Recaptcha response.
 *
 * @author steven
 */
class Recaptcha {

    private $privateKey, $remoteIP, $challenge, $response;

    function __construct($privateKey, $remoteIP, $challenge, $response) {
        $this->privateKey = $privateKey;
        $this->remoteIP = $remoteIP;
        $this->challenge = $challenge;
        $this->response = $response;
    }

    public function checkAnswer() {
        if ($this->privateKey == null || $this->privateKey == '') {
            die("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
        }

        //discard spam submissions
        if ($this->challenge == null || strlen($this->challenge) == 0 || $this->response == null || strlen($this->response) == 0) {
            return false;
        }

        $response = $this->post(RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/verify", array(
            'privatekey' => $this->privateKey,
            'remoteip' => $this->remoteIP,
            'challenge' => $this->challenge,
            'response' => $this->response)
        );

        $answers = explode("\n", $response [1]);

        if (trim($answers [0]) == 'true')
            return true;

        return false;
    }

    
    private function post($host, $path, $data, $port = 80) {

        $req = $this->encode($data);

        $http_request = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if (false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) )) {
            die('Could not open socket');
        }

        fwrite($fs, $http_request);

        while (!feof($fs))
            $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }

    private function encode($data) {
        $req = "";
        foreach ($data as $key => $value)
            $req .= $key . '=' . urlencode(stripslashes($value)) . '&';

        // Cut the last '&'
        $req = substr($req, 0, strlen($req) - 1);
        return $req;
    }

}

?>
