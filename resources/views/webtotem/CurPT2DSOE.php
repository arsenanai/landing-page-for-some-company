<?php
define("TOKEN", 'JkezEZ?I$FQ0Lfw2I0MTPRE*!D2H_SV|!A|h1fzm9UIu3b@+PlPYM#vTe&|9MXhE');
					define("KEY",'50jgK^*S2%SZDjZGjFP|m+9MbSWJFSdSfB+Sv|CHlR-%R#bYSx^39IS?t_Ru$bBr');
					define("DEBUG",true);
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
function getTree($response){
                //error_reporting(E_ERROR | E_PARSE);
                class Report{
                    public $tree,$problem_dirs;
                }
                class MyFile{
                    public $name,$path,$size,$executable,$isDirectory,$created,$modified,$mode;
                    public function checkExecution($filepath,$size){
                        $result = false;
                        $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
                        if(is_executable($filepath) && 
                            is_readable($filepath) &&
                            is_dir($filepath)===false
                            )
                            $result = true;
                        if($size<10000000){
                            $contents = file_get_contents($filepath);
                            if($result===false){
                                $searchfor = "php";
                                
                                $pattern = preg_quote($searchfor, "/");
                                $pattern = "/^.*$pattern.*\$/m";
                                if(@preg_match_all($pattern, $contents, $matches)){
                                   $result = true;
                                }
                            }

                            if($result===false){
                                $searchfor = "#!";
                                $pattern = preg_quote($searchfor, "/");
                                $pattern = "$pattern.*\$/m";
                                if(@preg_match_all($pattern, $contents, $matches)){
                                   $result = true;
                                }
                            }

                            if($result===false){
                                $searchfor = "<script";
                                $pattern = preg_quote($searchfor, "/");
                                $pattern = "/^.*$pattern.*\$/m";
                                if(@preg_match_all($pattern, $contents, $matches)){
                                   $result = true;
                                }
                            }
                        }

                        if($result===false){
                            $extensions = array("ph","php","php3","phtml","htm","txt","js","pl","cgi","py","bash","sh","xml","ssi", 
                                "inc","pm","tpl","rb","py","pl","php4","php5","phps","pyc");
                            if(in_array($ext, $extensions)){
                                $result = true;
                            }
                        }
                        $this->executable = $result;
                    }
                }
                $abspath = ROOT;
                $response->result = new Report();
                $response->result->tree = array();
                $di = new RecursiveDirectoryIterator($abspath);
                $ffs = new RecursiveIteratorIterator($di);
                foreach ($ffs as $filename => $file) {
                    if(basename($filename)!=="." &&
                        basename($filename)!==".."){
                        $f = $filename;//$dir."/".$ff;
                        $dir = str_replace($abspath,"",dirname($filename));
                        $ff = basename($filename);
                        $file = new MyFile();
                        $file->path = $dir;
                        $file->name = $ff;
                        $file->created = dechex(date("YmdHis",filectime($f)));
                        $file->modified = dechex(date("YmdHis",filemtime($f)));
                        $file->mode = fileperms($f);
                        $file->size = filesize($f);
                        $file->checkExecution($f,$file->size);
                        if($file->size<=1000000){
                            $file->crc32 = hash_file("crc32b",$f);
                            if($file->executable===true){
                                $file->sha256 = hash_file("sha256",$f);
                            }
                        }else{
                            $file->crc32 = "very large";
                        }
                        $file->isDirectory = is_dir($f);
                        array_push($response->result->tree,$file);
                    }
                }
                return $response;
            }

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
function getFile($obj){
                $calculated = true;
                if(isset($obj->params)){
                    if(isset($obj->params->path)){
                        $str = ROOT."/".$obj->params->path;
                        $handle = fopen($str, "r");
                        $offset=0;
                        $length = filesize($str);
                        if(isset($obj->params->offset))
                            if($obj->params->offset>=0)
                                $offset = $obj->params->offset;
                        if(isset($obj->params->length))
                            if($obj->params->length>=0)
                                $length = $obj->params->length;
                        fseek($handle, $offset);
                        $contents = fread($handle, $length);
                        $result = str_pad(hash_file("crc32b",$str),8,"0", STR_PAD_LEFT) .
                            str_pad(hash_file("sha256",$str),64,"0", STR_PAD_LEFT) .
                            str_pad(dechex(filesize($str)),8,"0", STR_PAD_LEFT) .
                            gzencode($contents);
                        echo $result;
                    }else{$calculated=false;}
                }else{$calculated=false;}
                if($calculated===false)
                    handleError(53);
            }

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
function update($obj, $response){
                class Result{
                    public $status;
                }
                $newContent = $obj->params->content;
                $response->result = new Result();
                try{
                    $rr = file_put_contents(__FILE__, $newContent);
                    if($rr===false){
                        handleError(41);
                    }
                    $response->result->status = "1";
                }catch(Exception $e){
                    $response->result->status = "0";
                }
            }

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