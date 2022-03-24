<?php
// AJ's ToolBox

class AJsToolBox {

    public function load_view($filename,$data=null) {
        $filename=dirname(__FILE__).'/../views/'.$filename.'.php';
        //var_dump($filename);
        if (is_file($filename)) {
            ob_start();
            if($data!==null){
                extract($data);
            }
            include $filename;
            return ob_get_clean();
        }
        return false;
    }
    
    public function curl( $url, $request_type, $data = array(), $json=true ) {
        if( $request_type == 'GET' )
                $url .= '?' . http_build_query($data);

        $mch = curl_init();
        $headers = array(
                //'Content-Type: application/json',
                //'Authorization: Basic '.base64_encode( 'user:'. $api_key )
        );
        
        if($json){
            $headers = array(
                'Content-Type: application/json',
            );
        }
        
        //curl_setopt($mch, CURLOPT_USERPWD, "anyuser:$api_key");
        curl_setopt($mch, CURLOPT_URL, $url );
        curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
        curl_setopt($mch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
        curl_setopt($mch, CURLOPT_TIMEOUT, 30000);
        curl_setopt($mch, CURLOPT_CONNECTTIMEOUT, 30000);
        curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection

        curl_setopt($mch, CURLOPT_HEADER, 1);

        if( $request_type != 'GET' ) {
                curl_setopt($mch, CURLOPT_POST, true);
                if($json){
                    curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
                }else{
                    curl_setopt($mch, CURLOPT_POSTFIELDS, $data); // send data in raw
                }
        }

        //return curl_exec($mch);
        
        $raw_response=curl_exec($mch); 
        
        $header_size = curl_getinfo($mch, CURLINFO_HEADER_SIZE);
        $httpcode = curl_getinfo($mch, CURLINFO_HTTP_CODE);
        $header = substr($raw_response, 0, $header_size);
        $body = substr($raw_response, $header_size);
        
        $response=array(
                        'header'=>$header,
                        'http_code'=>$httpcode,
                        'body'=>$body,
                    );
        curl_close($mch);
        
        //pre_var_dump($response);
        
        return $response;
        
    }
    
    public function api_get($url,$request=array(),$content='text/xml') {
        $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: $content"));
        //curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            //echo '<hr/>Curl error: ' . curl_error($ch) . '<hr/>';
        }
        curl_close($ch);
        return json_decode($response,true);
    }
    
    
    
}

function pre_var_dump($var){
    if(current_user_can('administrator')){
        echo '<pre style="height:400px;overflow-y:scroll;">';
        var_dump($var);
        echo '</pre>';
    }
}

function shorten_chart_label($string, $length)
{
    if(strlen($string) > $length)
    {
            $string = trim(substr($string, 0, $length)).'...';
    }
    return $string;
}

function getPercentageChange($oldNumber, $newNumber){
    // (v2-v1)/|v1|*100

    $decreaseValue = $newNumber - $oldNumber;

    if($oldNumber==0 and $newNumber==0){
        return 0;
    }
    if($oldNumber==0){
        return 'infinity';
    }
    return ($decreaseValue / $oldNumber) * 100;
}

function number_format_locale($number,$decimals=0) {
    global $LBRYworm;
    
    $thousandsSeparator=$LBRYworm->user_settings['thousandsSeparator']??',';
    $decimalSeparator=$LBRYworm->user_settings['decimalSeparator']??'.';
    
    return number_format(
                $number,$decimals,
                $decimalSeparator,
                $thousandsSeparator
            );
}

function format_number_kmb($n){
    if ($n < 1000) {
        // Anything less than 1 K
        $n_format = number_format($n);
    } else if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n / 1000, 1) . ' K';
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, 1) . ' M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, 1) . ' B';
    }
    return $n_format;
}


/* Convert hexdec color string to rgb(a) string */
function hex2rgba($color, $opacity = false) {
 
	$default = 'rgb(0,0,0)';
 
	//Return default if no color provided
	if(empty($color))
          return $default; 
 
	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity!==false){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}

function fileSizeInfo($filesize,$decimals) {
    $bytes = array('KB', 'KB', 'MB', 'GB', 'TB');

    if ($filesize < 1024) 
        $filesize = 1;

    for ($i = 0; $filesize > 1024; $i++) 
        $filesize /= 1024;

    $dbSizeInfo['size'] = round($filesize, $decimals);
    $dbSizeInfo['type'] = $bytes[$i];

    return $dbSizeInfo;
}

function databaseSize($decimals=2) {
    global $wpdb;
    $dbsize = 0;

    $rows = $wpdb->get_results("SHOW table STATUS");

    foreach($rows as $row) 
        $dbsize += $row->Data_length + $row->Index_length;

    return fileSizeInfo($dbsize,$decimals); 
}

