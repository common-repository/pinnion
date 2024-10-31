<?php
//make sure we know what site to talk to
define('PINNION_API_URL', 'https://secure.pinnion.com');//'https://secure.pinnion.com');
// set our endpoints for pinnion, channel, and auth
define('PINNION_ENDPOINT_PINNION', '/ws/v1/pinnion');
define('PINNION_ENDPOINT_CHANNEL', '/ws/v1/channel');
define('PINNION_ENDPOINT_AUTH', '/ws/v1/auth');
// some future / testing endpoints
//define('PINNION_ENDPOINT_PENDING', '/ws/v1/pending');
//define('PINNION_ENDPOINT_USER', '/ws/v1/user');


// testing purposes/
//$p = new PinnionAPI('testuser98', 'testpass98');
//echo '<pre>'; var_dump($p->listPinnions(true));
//echo '<hr>';
//echo '<pre>'; var_dump($p->getPinnion('769'));

class PinnionAPI {

    /**
     * Pinnion API Username
     * @var String
     **/
    private $mUser;

    /**
     * Pinnion API Password
     * @var String
     **/
    private $mPass;


    /**
     * Constructor: Sets up the object
     * @param String $User
     * @param String $Pass
     **/
    public function __construct($User, $Pass) {
        $this->mUser = $User;
        $this->mPass = $Pass;
    }

    /**
     * Retrieves a listing of Pinnions and formats them as an array
     *
     * @return Array
     **/
    public function listPinnions() {

        /** call api for list of pinnions
        /* $p = $this->send(HTTP_METH_GET, '/ws/v1/pinnion')
        /* if in a good json format convert to array and return
        /* else create a loop to go through the pinnions and put it into a good format to return
        **/
        $arr = $this->send('GET', PINNION_ENDPOINT_PINNION);
	$http_code = $arr['http_code'];
	$arr = json_decode($arr['content'], true);
	$arr['http_code'] = $http_code;
        
       
       // $arr = base64_decode($arr[0]['icon']);
        return $arr;
    }
    public function listChannels() {
        $arr = $this->send('GET', PINNION_ENDPOINT_CHANNEL);
        $http_code = $arr['http_code'];
        $arr = json_decode($arr['content'], true);
        $arr['http_code'] = $http_code;
        return $arr;
    }

/**
 * Retrieves the pinnion specified by the pinnion id
 *
 * @param Integer $id - Pinnion ID
 * @return Array
 **/
public function getPinnion($id) {
	// Until the API is fixed this hacky method must be used
	$arr = $this->listPinnions($headers);
	foreach($arr AS $p) {
		if($p['surveyId'] == $id) {
			$p['http_code'] = $arr['http_code'];
			return $p;
		}
	}
	return null; //oops
}
public function getChannel($id){
        //until the API is fixed this hacky method must be used
        $arr = $this->listChannels($headers);
        foreach($arr AS $p) {
                if($p['groupId'] == $id) {
                        $p['http_code'] = $arr['http_code'];
			return $p;

        }
    }
}
    /**
     * Makes the call to the Pinnion API
     *
     * NOTE: There are methods in WordPress to take care of the HTTP calls,
     *       however since this class is meant to be standalone it has all
     *       been reimplemented :(
     *
     * @param String $Method - Use PHP constants HTTP_METH_(GET|PUT|POST|DELETE) - http://php.net/manual/en/http.constants.php
     * @param String $Endpoint
     * @param Mixed $Data - Optional - Query parameters to use - http://www.php.net/manual/en/function.httprequest-setquerydata.php
     * @return Array
     */
    private function send($Method, $Endpoint, $Data = '') {
        $url = PINNION_API_URL . "$Endpoint";
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
         //   CURLOPT_HEADER         => true,    // don't return headers
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "Pinnion WordPress Plugin", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYHOST => '0',
            CURLOPT_SSL_VERIFYHOST => '0',
        );

        $ch      = curl_init( $url );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERPWD, $this->mUser . ":" . $this->mPass);
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
	$header = array();
        $header['http_code']  = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        //die(print_r($header));
        return $header;
    }

}
