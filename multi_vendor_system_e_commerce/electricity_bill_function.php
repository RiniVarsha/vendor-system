<?php
#Base Functions
function electricity_bill_include($file_name){	return include_once($file_name);}

function electricity_bill_debug($line_number,$file_name,$function,$var = NULL)
{
	/*	if(isset($_SESSION)){
	    	$somecontent = "[".gmdate('Y-m-d H:i:s')."]  \t ".$file_name."\t".$function."\t Line_number:".$line_number."\t".$var."\t".electricity_bill_json_converter($_SESSION)."\r\n";
		}else{
			$somecontent = "[".gmdate('Y-m-d H:i:s')."]  \t ".$file_name."\t".$function."\t Line_number:".$line_number."\t".$var."\t\r\n";
		}
		$filename = DEFINE_PATH_LOGS . date('Y-m-d').'.log';
		if (!$handle = fopen($filename, 'a')) { echo "Cannot open file ($filename)"; exit; }
		if (fwrite($handle, $somecontent) === FALSE) { echo "Cannot write to file ($filename)"; exit; }*/

}

function electricity_bill_print_query($line_number,$file_name,$query,$msc)
{
	
		$somecontent = "[".date('Y-m-d H:i:s')."]  \t ".$file_name."\t Line_number:".$line_number."\t Time:".$msc."\t".$query."\r\n";
		$filename = dirname(__FILE__) . '/query_log/'.date('Y-m-d').'.txt';
		$filename = date('Y-m-d').'.txt';
		if (!$handle = fopen($filename, 'a')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}
	
		if (fwrite($handle, $somecontent) === FALSE) {
			echo "Cannot write to file ($filename)";
			exit;
		}
	
}


function electricity_bill_send_sms($line,$file,$no,$otp) { 
			$phone_no = '91'.$no;
			$sms = 'Your verification code for BillPro is '.$otp.'. For any support contact us at rjio.support@billpro.online or call us at: 9073655327. Please download Google Authenticator App from https://bit.ly/19dDzPR (for Android) or from https://apple.co/2Y8KdSK (for ios)';
			$curl_var = curl_init();
			$full_json = curl_setopt($curl_var, CURLOPT_URL, "http://websms.codez.in:8080/bulksms/bulksms?username=coz1-codez&password=g6RgP7nM&type=0&dlr=Z&destination=".$phone_no."&source=BILPRO&message=".urlencode($sms));
			curl_setopt( $curl_var, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($curl_var, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt( $curl_var, CURLOPT_RETURNTRANSFER, true );
		
			# Send request.
			$result = curl_exec($curl_var);
			if(curl_errno($curl_var)) {
				echo 'Scraper error: ' . curl_error($curl_var);
				exit;
			}
			
			curl_close($curl_var);
			return $result;	
}

function electricity_bill_json_converter() {
		$numargs = func_num_args();
		$parameters = array();
		for($index=0;$index < $numargs;$index++) { $parameters['parameter_'.($index+1)] = func_get_arg($index); }
		return json_encode($parameters);;
	}
	

#Base Functions

#Session Functions
function electricity_bill_get_session($line,$file,$session_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($session_name));	return $_SESSION[$session_name];}
function electricity_bill_session_isset($line,$file,$session_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($session_name));	return isset($_SESSION[$session_name]);}
function electricity_bill_unset_session($line,$file,$session_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($session_name));unset($_SESSION[$session_name]);}
function electricity_bill_set_session( $line,$file,$session_name,$session_value ){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($session_name,$session_value)); $_SESSION[$session_name] = $session_value;}
function electricity_bill_all_unset_session( $line,$file ){electricity_bill_debug($line,$file,__FUNCTION__); session_unset();}
function electricity_bill_destroy_session( $line,$file ){electricity_bill_debug($line,$file,__FUNCTION__); session_destroy();}
#Session Functions

#Database Related Functions
function electricity_bill_commit_off($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);global $con;mysqli_autocommit($con, FALSE); }
function electricity_bill_commit_on($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);global $con;mysqli_autocommit($con, TRUE); }
function electricity_bill_commit($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);global $con;mysqli_commit($con);}
function electricity_bill_rollback($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);global $con;mysqli_rollback($con);}

function electricity_bill_query($line,$file,$query){
		electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($query));
		 global $con;
		 $msc = microtime(true);
		 $result = mysqli_query($con,$query);
		 $msc = microtime(true)-$msc;
		 electricity_bill_print_query($line,$file,$query,$msc);
		 if($result == false){
		    $t = microtime(true);
		    $micro = sprintf("%06d",($t - floor($t)) * 1000000);
		    $datetime = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
			$template_data_array = array('DATE_TIME','Query_EXEC_TIME','FILE_NAME','LINE_NUMBER','ERROR_TYPE','QUERY','ERROR');
			$template_value_array = array(electricity_bill_date_format_with_formatted(__LINE__,__FILE__,$datetime->format("Y-m-d H:i:s")),$datetime->format("Y-m-d H:i:s"),$file,$line,'QUERY FAILED',$query,mysqli_error($con)); 
			global $mailTempalte;
			electricity_bill_send_mail(__LINE__,__FILE__,$mailTempalte['query_failed_error_content'],$template_data_array,$template_value_array,QUERY_FAILED_RECEIVER,$mailTempalte['query_failed_error_subject']);
		 }
		 return $result;
}
function electricity_bill_num_rows($line,$file,$result){electricity_bill_debug($line,$file,__FUNCTION__);global $con; $rows = mysqli_num_rows($result); return $rows;}
function electricity_bill_fetch_assoc($line,$file,$result){electricity_bill_debug($line,$file,__FUNCTION__);global $con;return mysqli_fetch_assoc($result);}
function electricity_bill_fetch_array($line,$file,$value){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value));return mysqli_fetch_array($value);}
function electricity_bill_affected_rows($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);global $con;$rows = mysqli_affected_rows($con); return $rows;}
function electricity_bill_last_inserted($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);global $con;return mysqli_insert_id($con);}

#Database Related Functions

#Post Related Functions
function electricity_bill_print_post_data($line,$file,$content,$page)
{
	    electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($content,$page));
		$somecontent = "[".date('Y-m-d H:i:s')."]  \n".print_r($content,true)."\r\n";
		
		$filename = dirname(__FILE__) . '/app_post_data/'.$page.date('Y-m-d').'.txt';
		if (!$handle = fopen($filename, 'a')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}
	
		if (fwrite($handle, $somecontent) === FALSE) {
			echo "Cannot write to file ($filename)";
			exit;
		}
	
}
function electricity_bill_post_isset($line,$file,$post_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($post_name)); return isset($_POST[$post_name]);}
function electricity_bill_get_post_escape($line,$file,$post_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($post_name));global $con;return mysqli_real_escape_string($con,trim($_POST[$post_name]," "));	}
function electricity_bill_real_escape($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));global $con;return mysqli_real_escape_string($con,trim($string," "));	}
function electricity_bill_get_post($line,$file,$post_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($post_name));return $_POST[$post_name];	}
#Post Related Functions

#Send Related Functions
function electricity_bill_print_send_data($line,$file,$content,$page)
{
	    electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($content,$page));
		$somecontent = "[".date('Y-m-d H:i:s')."]  \n".print_r($content,true)."\r\n" ;
		$filename = dirname(__FILE__) . '/app_send_data/'.$page.date('Y-m-d').'.txt';
		if (!$handle = fopen($filename, 'a')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}
	
		if (fwrite($handle, $somecontent) === FALSE) {
			echo "Cannot write to file ($filename)";
			exit;
		}
	
}
#Send Related Functions

#Get Related Functions
function electricity_bill_get_get($line,$file,$get_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($get_name)); global $con; return mysqli_real_escape_string($con,trim($_GET[$get_name]));	}
function electricity_bill_get_isset($line,$file,$get_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($get_name));	return isset($_GET[$get_name]);}
#Get Related Functions

#Validation Related Functions
function electricity_bill_validation($line,$file,$string,$blank,$max,$min,$nospace,$nospecialch,$alphaonly,$numberonly,$field_name){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string,$blank,$max,$min,$nospace,$nospecialch,$alphaonly,$numberonly,$field_name));
	if($blank){
		if($string == ""){
			return BLANK_FIELDS.$field_name;
		}
	}
	if(strlen($string) <$min){
			return "Please enter at least ".$min." characters in ".$field_name;
	}
	if(strlen($string) >$max){
			return "Please enter no more than ".$max." characters in ".$field_name;
	}
	if($nospecialch){
		if(electricity_bill_hsaspecialcharacter($string)){
			return SPECIAL_CHARS. $field_name;
		}
	}
	if($nospace){
		if(preg_match('/\s/',$string)){
			return "No space please and don't leave it empty in ".$field_name;	
		}
	}
	if($alphaonly){
		if (!ctype_alpha($string)) {
			return "Please enter only letters in ".$field_name;	
		}
	}
	if($numberonly){
		if (!is_numeric($string)) {
			return "Please enter only numeric value in ".$field_name;	
		}
	}
	return "";
	}
function electricity_bill_no_single_quotes($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));if (preg_match("/[']/", $string)){return true;}else{return false;}}
function electricity_bill_no_double_quotes($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));if (preg_match('/["]/', $string)){return true;}else{return false;}}
function electricity_bill_validate_mobile_number($line,$file,$phone)
{
	if($phone === '0000000000')
	{
		electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($phone));
		return false;
	}
	if( !preg_match("/^[7-9][0-9]{9}$/i", $phone))
	{
		electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($phone));
		return false;
	}
	else
	{
		electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($phone));
	 	return true;
	}
}
function electricity_bill_filter_var($line,$file,$email){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($email)); return filter_var($email, FILTER_VALIDATE_EMAIL);  };
function electricity_bill_is_alphabet_consist($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string)); return preg_match("/[a-z]/i", $string);  };
#Validation Related Functions

#Encode-Decode Related Functions
function electricity_bill_hash($line,$file,$content){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($content));$output = sha1($content);	if(!$output) {electricity_bill_display(__LINE__,__FILE__,"error in hashing sha1 input "."\r\n"); } else { return $output;}}
function electricity_bill64_encode($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));return base64_encode($string);}
function electricity_bill64_decode($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));return base64_decode($string);}
function encrypt_password($line,$file,$password) {
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($password));
	$length = strlen($password);
	$encyPassword = "";
	for($i=0;$i<$length;$i++) {
		$utf8Character = substr($password,$i,1);
		list(, $ord) = unpack('N', mb_convert_encoding($utf8Character, 'UCS-4BE', 'UTF-8'));
		$temp = (int)$ord + 74;
		$encyPassword .= pad(__LINE__,__FILE__,$temp,4);
	}
	return $encyPassword;
}
#Encode-Decode Related Functions

#General Functions
function electricity_bill_file_open($line,$file,$filename,$mode){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($filename,$mode)); return fopen($filename, $mode);}
function electricity_bill_file_write($line,$file,$resource,$text){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($resource,$text)); return fwrite($resource, $text);}
function electricity_bill_array_merge(){
	$args = func_get_args();
	$temp_args = array();
	electricity_bill_debug($args[0],$args[1],__FUNCTION__);	
	for($index=2;$index<sizeof($args)-1;$index++){
		$temp_args = array_merge($temp_args,$args[$index]);			
	}
	return $temp_args;
}
function electricity_bill_display($line,$file,$content){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($content));echo $content;}
function electricity_bill_unset($line,$file,& $arguement){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($arguement));unset($arguement); }
function electricity_bill_unset_assoc($line,$file,& $arguement_array,$arguement_list){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($arguement_array,$arguement_list));
	for($index=0;$index<sizeof($arguement_list);$index++){
		unset($arguement_array[$arguement_list[$index]]); 
	}
}
function electricity_bill_get_request($line,$file,$request_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($request_name));return $_REQUEST[$request_name];	}
function electricity_bill_request_isset($line,$file,$request_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($request_name));return isset($_REQUEST[$request_name]);}
function electricity_bill_array_key_exists($line,$file,$search_str, $array){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($search_str,$array));return array_key_exists($search_str, $array);}
function electricity_bill_string_replace($line,$file,$find,$replace,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($find,$replace,$string));return str_replace($find,$replace,$string);}
function electricity_bill_lower($line,$file,$str){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($str)); return strtolower($str);}
function electricity_bill_ucwords($line,$file,$value){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value));return  ucwords($value); }
function electricity_bill_explode($line,$file,$string_to_explode, $string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string_to_explode, $string));return explode($string_to_explode, $string);}
function electricity_bill_upper($line,$file,$value){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value));return strtoupper($value);}
function electricity_bill_get_user_browser($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);return $_SERVER['HTTP_USER_AGENT'];}
function electricity_bill_in_array($line,$file,$value, $arr){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value, $arr));return in_array($value, $arr);}

function electricity_bill_find_string_length($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));return strlen($string);}

function electricity_bill_find_position($line,$file,$string, $findme){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string, $findme));return strpos($string, $findme);}
function electricity_bill_http_build_query($line,$file,$data_array){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($data_array));return http_build_query($data_array);}
function electricity_bill_file_put_contents($line,$file,$filename,$content){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($filename,$content));
return file_put_contents($filename,$content);}
function electricity_bill_file_content_by_line($line,$file,$myFile){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($myFile));return file($myFile);}
function electricity_bill_trim($line,$file,$value){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value));return trim($value);}
function electricity_bill_string_length($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));return strlen($string);}
function electricity_bill_substring_two_params($line,$file,$string,$value_1,$value_2){ electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string,$value_1,$value_2));return substr($string,$value_1,$value_2);}
function electricity_bill_substring_one_params($line,$file,$string,$value_1){ electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string,$value_1));return substr($string,$value_1);}
function electricity_bill_sleep($line,$file,$time){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($time));sleep($time);}
function electricity_bill_is_numeric($line,$file,$value){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value));return is_numeric($value);}
function electricity_bill_json_decode($line,$file,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($data));return json_decode($data,true);}
function electricity_bill_json_decode_1($line,$file,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($data));return json_decode($data);}
function electricity_bill_preg_split($line,$file,$split_by,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($split_by,$data));return preg_split($split_by,$data);}
function electricity_bill_preg_split_2($line,$file,$split_by,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($split_by,$data));return preg_split($split_by,$data,-1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);}
function electricity_bill_sizeof($line,$file,$data){/*electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($data));*/return sizeof($data);}
function electricity_preg_replace($line,$file,$pattern,$replacement,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($pattern,$replacement,$data));return preg_replace($pattern,$replacement,$data);}
function electricity_urlencode($line,$file,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($data));return urlencode($data);}
function electricity_strrchr($line,$file,$data,$find){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($data,$find));return strrchr($data,$find);}
function get_line_number($line,$file,$lines,$search){
	//electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($lines,$search));
	$line_number = false;
	while (list($key, $line) = each($lines) and !$line_number) {
	   $line_number = (strpos($line, $search) !== FALSE) ? $key : $line_number;
	}
	return $line_number;
}
function get_line_number_occurrence($line,$file,$lines,$search,$occurrence =1){
	//electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($lines,$search));
	$line_number = false;
	$find_occurrence=0;
	while (list($key, $line) = each($lines) and !$line_number) {
	   $line_number = (strpos($line, $search) !== FALSE) ? $key : $line_number;
	   if($line_number != false){
	   	$find_occurrence++;
	   }
	   if($find_occurrence != $occurrence){
	   	$line_number = false;
	   }
	}
	return $line_number;
}
function electricity_get_total_occurance_of_substring($line,$file,$to_find,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($to_find,$string));return substr_count($string, $to_find);}
function get_string_between($line,$file,$string, $start, $end){
	
	//electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string, $start, $end));
	//echo '</br> INSIDE FUNCTION';
	if(strpos($string,$start) !== false ){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		$temp_value = substr($string, $ini, $len);
		if(($temp_value !== false) && ($temp_value != '') ) {
			return trim($temp_value);
		}else{
			return false;	
		}
	}else{
		return false;	
	}
}
function electricity_bill_generate_bill_id($line,$file,$board,$ai_id){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($board,$ai_id));
	$bill_id = $board.'_'.date('y').'_'.date('m').'_'.electricity_bill_left_pad(__LINE__,__FILE__,$ai_id);
	return $bill_id;	
}
function get_line_number_by_regexp($line,$file,$pattern,$arr) {
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($pattern,$arr));
	$mixed  = preg_grep ($pattern, $arr);
	$line_number = false;
	foreach($mixed as $line_num => $value) {
		$line_number = $line_num;
	}
	return $line_number;
}	
function pad($line,$file,$num, $size) {
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($num, $size));
	$s = $num."";
	while (strlen($s) < $size) $s = "0".$s;
	return $s;
}
function mb_stripos_all($line,$file,$haystack, $needle) {
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($haystack, $needle));
 	$s = 0;
 	$i = 0;
 	while(is_integer($i)) {
 		$i = mb_stripos($haystack, $needle, $s);
 		if(is_integer($i)) {
      		$aStrPos[] = $i;
      		$s = $i + mb_strlen($needle);
    	}
  	}
 
  if(isset($aStrPos)) {
    return $aStrPos;
  } else {
    return false;
  }
}
function electricity_bill_unlink($line,$file,$file_name){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($file_name));unlink($file_name);}
function electricity_bill_left_pad($line,$file,$input){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($input));return str_pad($input, 11, "0", STR_PAD_LEFT);}
function electricity_bill_count($line,$file,$value){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value));return count($value);}
function electricity_bill_array_empty($line,$file,$array){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($array)); return empty($array);}
function electricity_bill_isset($line,$file,$var){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($var)); return isset($var);}
function electricity_bill_preg_match($line,$file,$search_char,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($search_char,$string));return preg_match($search_char,$string);}
function electricity_bill_disk_total_space($line,$file,$variable){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($variable));return disk_total_space($variable);}
function electricity_bill_disk_free_space($line,$file,$variable){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($variable));return disk_free_space($variable);}
function electricity_bill_floatval($line,$file,$variable){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($variable));return floatval($variable);}
function electricity_bill_intval($line,$file,$variable){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($variable));return intval($variable);}
function electricity_bill_number_format($line,$file,$num,$decimal_point){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($num,$decimal_point));
	
	$explrestunits = "" ;
	$minus = '';
	$after_point = '00';
	if(floatval($num) < 0){
		$minus = substr($num,0,1);
		$num = substr($num,1,strlen($num));
	}
	
	if(strpos($num,'.') !== false){
		$temp_val = number_format($num,$decimal_point).'';
		$temp_val_array_point = explode('.',$temp_val);
		$after_point = $temp_val_array_point[1];
		$temp_val_array = explode('.',$num);
		$num = 	$temp_val_array[0];
		
	} 
    if(strlen($num)>3) {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $minus.$explrestunits.$lastthree.'.'.$after_point;
    } else {
        $thecash = $minus.$num.'.'.$after_point;
    }
    return $thecash;
	//return number_format($num,$decimal_point);
	}
	
function electricity_bill_number_format_php($line,$file,$num,$decimal_point){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($num,$decimal_point));return number_format($num,$decimal_point);}
	
function electricity_bill_number_preg_match_all($line,$file,$explode,$data){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($explode,$data));
	preg_match_all($explode, $data,$matches, PREG_OFFSET_CAPTURE);
	return $matches;	
}
function electricity_bill_json_encode($line,$file,$array){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($array));return json_encode($array,JSON_PRETTY_PRINT);}
function electricity_bill_json_encode_1($line,$file,$array){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($array));return json_encode($array);}
function electricity_bill_hsaspecialcharacter($string){if (preg_match('/[\'^£!$%&*()}{@#~?><>,|=_+¬-]/', $string)){return true;}else{return false;}}
function electricity_bill_get_file_ext($line,$file,$filename){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($filename));return strtolower(pathinfo($filename, PATHINFO_EXTENSION));}
function electricity_bill_file_get_contents($line,$file,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($data)); return file_get_contents($data);}
function electricity_bill_array_push($line,$file,& $array,$data){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($array,$data)); array_push($array, $data);}
function electricity_bill_move_uploaded_file($line,$file,$filename,$save_dir){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($filename,$save_dir));return move_uploaded_file($filename,$save_dir);}
function electricity_bill_array_to_string($line,$file,$lower_limit,$upper_limit,$array){
	 electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($lower_limit,$upper_limit,$array));
	$retuen_value = "";
	for($index = $lower_limit;$index<=$upper_limit;$index++){
		$retuen_value .= " ".$array[$index];
	}
	return trim($retuen_value);
}	
function electricity_bill_round($line,$file,$value,$dec){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($value,$dec));return round($value,$dec) ;}
function electricity_bill_strtotime($line,$file,$time){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($time));return strtotime($time);}

function electricity_bill_filename($line,$file,$filename){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($filename));
	$file_name_temp = preg_replace('/[^a-zA-Z0-9_.]/', '', $filename);
	$file_name_temp = str_replace(' ','_',$file_name_temp);
	return strtolower($file_name_temp);
}
function electricity_bill_array_slice($line,$file,& $array,$index){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($array,$index));return array_slice($array,$index);}
function electricity_bill_implode($line,$file,$implode_by,& $array){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($implode_by,$array));return implode($implode_by,$array);}
function electricity_bill_stripslashes($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));return stripslashes($string);}
function electricity_bill_abs($line,$file,$string){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string));return abs($string);}

#General Functions

#Date-Time Related Functinos
function electricity_bill_datetime_date_year_month_date_time_IST($line,$file,$datetime){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($datetime));	
	$temp_timestamp = strtotime($datetime);
	//date_default_timezone_set("Asia/Calcutta");	
	return date('Y-m-d H:i:s',strtotime('+330 minutes',($temp_timestamp)));
}
function electricity_bill_date_format_with_formatted($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	if($date != '0000-00-00 00:00:00'){
		return date("j M, Y",strtotime($date));
	}else{
		return "0000-00-00 00:00:00";	
	}
}
function electricity_bill_date_format_with_formatted_2($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	if($date != '0000-00-00 00:00:00'){
		return date("j-M-Y",strtotime($date));
	}else{
		return "0000-00-00 00:00:00";	
	}
}
function electricity_bill_month_formatted_2($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	if($date != '0000-00-00 00:00:00'){
		return date("M",strtotime($date));
	}else{
		return "0000-00-00 00:00:00";	
	}
}
function electricity_bill_custom_date_time_format_2($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	return date("j-M-y (g:i A)", strtotime($date));
}
function electricity_bill_datapicker_date_format($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	return date("m/d/Y", strtotime($date));
}
function electricity_bill_database_date_format($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));
	return date("Y-m-d", strtotime($date));
}
function electricity_bill_custom_date_format($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	if($date == "0000-00-00" || $date == "0000-00-00 00:00:00"){
		return " ";	
	}else{
	return date("dS F, Y", strtotime($date));
	}
}
function electricity_bill_custom_date_time_format($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	return date("dS F, Y (g:i A)", strtotime($date));
}
function electricity_bill_time_format($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));	
	return date("g:i A ",strtotime($date));
}
function get_full_month($line,$file,$month){
	
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($month));
	switch(strtolower($month)){
			case "jan":
				return "JANUARY";
				break;
			case "feb":
				return "FEBRUARY";
				break;
			case "mar":
				return "MARCH";
				break;
			case "apr":
				return "APRIL";
				break;
			case "may":
				return "MAY";
				break;
			case "jun":
				return "JUNE";
				break;
			case "jul":
				return "JULY";
				break;
			case "aug":
				return "AUGUST";
				break;
			case "sep":
				return "SEPTEMBER";
				break;
			case "sept":
				return "SEPTEMBER";
				break;
			case "oct":
				return "OCTOBER";
				break;
			case "nov":
				return "NOVEMBER";
				break;
			case "dec":
				return "DECEMBER";
				break;
			default:
				return $month;
				break;
		}
}
function electricity_bill_current_year($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);return date("Y");}
function electricity_bill_current_month($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);return date("F");}
function electricity_bill_DGVCL_date($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);return date('F', strtotime('-1 month'));}
function electricity_bill_get_time_in_seconds($line,$file){electricity_bill_debug($line,$file,__FUNCTION__); return time(); }
function electricity_bill_date_to_database_format($line,$file,$date,$explodewith){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date,$explodewith));
	if('vU;' == $date) {
		$date = '00/00/0000';	
	}
	$temp_arr = explode($explodewith,trim($date));
	return $temp_arr[2].'-'.$temp_arr[1].'-'.$temp_arr[0];	
}
function get_month_number($line,$file,$month){
	
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($month));
switch(strtolower($month)){
		case "jan":
		case "january":
			return "01";
			break;
		case "feb":
		case "february":
			return "02";
			break;
		case "mar":
		case "march":
			return "03";
			break;
		case "apr":
		case "april":
			return "04";
			break;
		case "may":
			return "05";
			break;
		case "jun":
		case "june":
			return "06";
			break;
		case "jul":
		case "july":
			return "07";
			break;
		case "aug":
		case "august":
			return "08";
			break;
		case "sep":
		case "september":
			return "09";
			break;
		case "sept":
			return "09";
			break;
		case "oct":
		case "october":
			return "10";
			break;
		case "nov":
		case "november":
			return "11";
			break;
		case "dec":
		case "december":
			return "12";
			break;	
	}		
}
function get_full_year($line,$file,$year){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($year));
	$dt = DateTime::createFromFormat('y', $year);
	return $dt->format('Y');
}
function electricity_bill_date_to_database_format_2($line,$file,$date,$explodewith){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date,$explodewith));
	$temp_arr = explode($explodewith,trim($date));
	if(strlen($temp_arr[2]) == 2){
		return get_full_year(__LINE__,__FILE__,$temp_arr[2]).'-'.get_month_number(__LINE__,__FILE__,$temp_arr[1]).'-'.$temp_arr[0];	
	}else{
		return $temp_arr[2].'-'.get_month_number(__LINE__,__FILE__,$temp_arr[1]).'-'.$temp_arr[0];	
	}
}
function electricity_bill_date_to_database_format_3($line,$file,$date,$explodewith){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date,$explodewith));
	$temp_arr = explode($explodewith,trim($date));
	
	if(strlen($temp_arr[2]) == 4){
		return $temp_arr[2].'-'.get_month_number(__LINE__,__FILE__,$temp_arr[1]).'-'.$temp_arr[0];	
	}else{
		return get_full_year(__LINE__,__FILE__,$temp_arr[2]).'-'.get_month_number(__LINE__,__FILE__,$temp_arr[1]).'-'.$temp_arr[0];	
	}
}
function electricity_bill_date_to_database_format_4($line,$file,$date,$explodewith){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date,$explodewith));
	$temp_arr = explode($explodewith,trim($date));
	if(strlen($temp_arr[2]) == 2){
		return get_full_year(__LINE__,__FILE__,$temp_arr[2]).'-'.$temp_arr[1].'-'.$temp_arr[0];	
	}else{
		return $temp_arr[2].'-'.$temp_arr[1].'-'.$temp_arr[0];	
	}	
}
function electricity_bill_get_timestamp($line,$file,$user_time){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($user_time));
	$date = new DateTime($user_time);
	$date = $date->getTimestamp();
	return $date;
}
function electricity_bill_add_days_to_date($line,$file,$date,$days){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date,$days));
	$date=date_create($date);
	date_add($date,date_interval_create_from_date_string($days." days"));
	return date_format($date,"Y-m-d");
}
function electricity_bill_yesterday_date($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);return date('Y-m-d',strtotime("-1 days"));}

function electricity_bill_get_formatted_date_from_given_date($line,$file,$date){electricity_bill_debug($line,$file,__FUNCTION__,$date);return date('Y-m-d H:i:s',strtotime($date));}

function electricity_bill_today_date($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);return date('Y-m-d H:i:s');}
function electricity_bill_today_date_year_month_date($line,$file){electricity_bill_debug($line,$file,__FUNCTION__);return date('Y-m-d');}

function convert_ascii($line,$file,$string) { 
  electricity_bill_debug($line,$file,__FUNCTION__,$string);
  // Replace Single Curly Quotes
  $search[]  = chr(226).chr(128).chr(152);
  $replace[] = "'";
  $search[]  = chr(226).chr(128).chr(153);
  $replace[] = "'";
  // Replace Smart Double Curly Quotes
  $search[]  = chr(226).chr(128).chr(156);
  $replace[] = '"';
  $search[]  = chr(226).chr(128).chr(157);
  $replace[] = '"';
  // Replace En Dash
  $search[]  = chr(226).chr(128).chr(147);
  $replace[] = '--';
  // Replace Em Dash
  $search[]  = chr(226).chr(128).chr(148);
  $replace[] = '---';
  // Replace Bullet
  $search[]  = chr(226).chr(128).chr(162);
  $replace[] = '*';
  // Replace Middle Dot
  $search[]  = chr(194).chr(183);
  $replace[] = '*';
  // Replace Ellipsis with three consecutive dots
  $search[]  = chr(226).chr(128).chr(166);
  $replace[] = '...';
  // Apply Replacements
  $string = str_replace($search, $replace, $string);
  // Remove any non-ASCII Characters
  $string = preg_replace("/[^\x01-\x7F]/","", $string);
  return $string; 
}
function electricity_bill_check_valid_month ($line,$file,$month) { 
  	electricity_bill_debug($line,$file,__FUNCTION__,$month);
  	$x = DateTime::createFromFormat('M', $month);
   	if(!$x){
		return false;	
	}else{
		return true;
	}
}
#Date-Time Related Functinos

#Mail Related Functions
function electricity_bill_send_mail_attachment($line,$file,$template,$template_date_array,$template_value_array,$receiver,$subject,$filename,$path)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($template,$template_date_array,$template_value_array,$receiver,$subject,$filename,$path));
	$template_date_array = array_map("add_email_template_code", $template_date_array);
	$email_data = str_replace($template_date_array, $template_value_array, $template);
	$subject = str_replace($template_date_array, $template_value_array, $subject);
	$email_id = "newbills@rjio.billpro.online";
	$file = $filename;

    $message = $email_data."
    File link : https://rjio.billpro.online/".$file;
    $headers = 'From: info@rjio.billpro.online' . "\r\n" .
    'Reply-To: info@rjio.billpro.online' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
   	if(! mail($email_id, $subject, $message, $headers))
	{
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "FAIL";
	}
	else
	{
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "SUCCESS";
	}
/*
	$file_size = filesize($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);
	$content = chunk_split(base64_encode($content));
	$uid = md5(uniqid(time()));
	$from = "arup@codez.in";
	$email_id =	$receiver;

	$email_id = "rupak@codez.in";
	$message = $email_data;
	
    $headers = "Reply-To: ".$from."\r\n"; 
    $headers .= "Return-Path: ".$from."\r\n"; 
    $headers .= "From: ".$from."\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n";
	//$headers .= "Content-type: text/html; charset=iso-8859-1\r\nX-Priority: 3\r\nX-Mailer: PHP". phpversion() ."\r\n";
	$headers .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
	$headers .= "This is a multi-part message in MIME format.\r\n";
	$headers .= "--".$uid."\r\n";
	$headers .= "Content-type:text/plain; charset=iso-8859-1\r\n";
	$headers .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
	$headers .= $message."\r\n\r\n";
	$headers .= "--".$uid."\r\n";
	$headers .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
	$headers .= "Content-Transfer-Encoding: base64\r\n";
	$headers .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
	$headers .= $content."\r\n\r\n";
	$headers .= "--".$uid."--";
	
	
	if(!mail($email_id,$subject,$message,$headers, 'O DeliveryMode=b'))
	{
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "FAIL";
	}
	else
	{
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "SUCCESS";
	}*/
}
function electricity_bill_send_mail($line,$file,$template,$template_date_array,$template_value_array,$receiver,$subject)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($template,$template_date_array,$template_value_array,$receiver,$subject));
	$template_date_array = array_map("add_email_template_code", $template_date_array);
	$email_data = str_replace($template_date_array, $template_value_array, $template);
	$subject = str_replace($template_date_array, $template_value_array, $subject);
	$email_id =	$receiver;
	$_MAIL = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
			   "http://www.w3.org/TR/html4/loose.dtd">
			<html lang="en">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
				<meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
			
				<title>BillPro</title>
				<style type="text/css">
			
					/* Resets: see reset.css for details */
					.ReadMsgBody { width: 100%; background-color:#d8d7d5;}
					.ExternalClass {width: 100%; background-color:#d8d7d5;}
					.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
					body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
					body {margin:0; padding:0;}
					table {border-spacing:0;}
					table td {border-collapse:collapse;}
					.yshortcuts a {border-bottom: none !important;}
			
			
					/* Constrain email width for small screens */
					@media screen and (max-width: 600px) {
						table[class="container"] {
							width: 95% !important;
						}
					}
			
					/* Give content more room on mobile */
					@media screen and (max-width: 480px) {
						td[class="container-padding"] {
							padding-left: 12px !important;
							padding-right: 12px !important;
						}
					}
			
			
					/* Styles for forcing columns to rows */
					@media only screen and (max-width : 600px) {
			
						/* force container columns to (horizontal) blocks */
						td[class="force-col"] {
							display: block;
							padding-right: 0 !important;
						}
						table[class="col-3"] {
							/* unset table align="left/right" */
							float: none !important;
							width: 100% !important;
			
							/* change left/right padding and margins to top/bottom ones */
							margin-bottom: 12px;
							padding-bottom: 12px;
							border-bottom: 1px solid #eee;
						}
			
						/* remove bottom border for last column/row */
						table[id="last-col-3"] {
							border-bottom: none !important;
							margin-bottom: 0;
						}
			
						/* align images right and shrink them a bit */
						img[class="col-3-img"] {
							float: right;
							margin-left: 6px;
							max-width: 130px;
						}
					}
			
				</style>
			</head>
			<body style="margin:0; padding:10px 0;" bgcolor="#d8d7d5" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
			
			
			<!-- 100% wrapper (grey background) -->
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#d8d7d5" >
			  <tr>
				<td align="center" valign="top" bgcolor="#ebebeb" style="background-color:#d8d7d5;">
			
				  <!-- 600px container (white background) -->
				  <table border="0" width="100%" cellpadding="0" cellspacing="0" class="container" bgcolor="#ffffff" style="border:1px solid #d8d7d5;">
					<tr>
					  <td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 13px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;" align="left">
						<br>
			
						<!-- ### BEGIN CONTENT ### -->
			
						<div>
						<img src="https://rjio.billpro.online/assets/images/logo.png" style="padding-top:10px; padding-bottom:10px; width:100px" align="BillPro Logo">
						</div>
						<br>
			
						<!--/ end .columns-container-->
			
					</td>
				</tr>
				<tr>
					<td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 13px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;" align="left">
					   
			
			
			
						<div style="font-weight: bold; font-size: 18px; line-height: 24px; color: #000; border-top: 1px solid #ddd;"><br>
						Hi Team,</div>
						<br>'.$email_data.'
           
            <br>
            <br/>
Sincerely,<br>
                  The BillPro Team
            </div>

            <!-- ### END CONTENT ### -->
            <br><br>

          </td>
        </tr>
        
      </table>
      <!--/600px container -->

    </td>
  </tr>

</table>
<!--/100% wrapper-->
<img src="http://www.ifread.com/img/5991d867ec86010b66ca3a1f6f20b7df/ifread.gif" width="1" height="1" />
</body>
</html>
';
			
	$from = "BillPro Notifications <noreply@rjio.billpro.online>";
	$headers = "Reply-To: ".$from."\r\n";
    $headers .= "Return-Path: ".$from."\r\n"; 
    $headers .= "From: ".$from."\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\nX-Priority: 3\r\nX-Mailer: PHP". phpversion() ."\r\n";

	//mail("kousik@codez.in","My subject","hello world");
	
	if(!mail($email_id,$subject,$_MAIL,$headers, 'O DeliveryMode=b'))
	{
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "FAIL";
	}
	else
	{
		//echo("Mail Sent");
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "SUCCESS";
	}
}

function electricity_bill_send_mail_exception($line,$file,$template,$template_date_array,$template_value_array,$receiver,$subject)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($template,$template_date_array,$template_value_array,$receiver,$subject));
	
	$template_date_array = array_map("add_email_template_code", $template_date_array);
	$email_data = str_replace($template_date_array, $template_value_array, $template);
	$subject = str_replace($template_date_array, $template_value_array, $subject);
	$email_id =	$receiver;
	$_MAIL = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

    <title>BillPro</title>
    <style type="text/css">

        /* Resets: see reset.css for details */
        .ReadMsgBody { width: 100%; background-color:#d8d7d5;}
        .ExternalClass {width: 100%; background-color:#d8d7d5;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
        body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
        body {margin:0; padding:0;}
        table {border-spacing:0;}
        table td {border-collapse:collapse;}
        .yshortcuts a {border-bottom: none !important;}


        /* Constrain email width for small screens */
        @media screen and (max-width: 600px) {
            table[class="container"] {
                width: 95% !important;
            }
        }

        /* Give content more room on mobile */
        @media screen and (max-width: 480px) {
            td[class="container-padding"] {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }


        /* Styles for forcing columns to rows */
        @media only screen and (max-width : 600px) {

            /* force container columns to (horizontal) blocks */
            td[class="force-col"] {
                display: block;
                padding-right: 0 !important;
            }
            table[class="col-3"] {
                /* unset table align="left/right" */
                float: none !important;
                width: 100% !important;

                /* change left/right padding and margins to top/bottom ones */
                margin-bottom: 12px;
                padding-bottom: 12px;
                border-bottom: 1px solid #eee;
            }

            /* remove bottom border for last column/row */
            table[id="last-col-3"] {
                border-bottom: none !important;
                margin-bottom: 0;
            }

            /* align images right and shrink them a bit */
            img[class="col-3-img"] {
                float: right;
                margin-left: 6px;
                max-width: 130px;
            }
        }

    </style>
</head>
<body style="margin:0; padding:10px 0;" bgcolor="#d8d7d5" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<!-- 100% wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#d8d7d5" >
  <tr>
    <td align="center" valign="top" bgcolor="#ebebeb" style="background-color:#d8d7d5;">

      <!-- 600px container (white background) -->
      <table border="0" width="100%" cellpadding="0" cellspacing="0" class="container" bgcolor="#ffffff" style="border:1px solid #d8d7d5;">
        <tr>
          <td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 13px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;" align="left">
            <br>

            <!-- ### BEGIN CONTENT ### -->

            <div>
            <img src="https://www.codez.in/billpro/white_billpro_neosigma.png" style="padding-top:10px; padding-bottom:10px;" align="BillPro Logo">
            </div>
            <br>

            <!--/ end .columns-container-->

        </td>
    </tr>
    <tr>
        <td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff; padding-left: 30px; padding-right: 30px; font-size: 13px; line-height: 20px; font-family: Helvetica, sans-serif; color: #333;" align="left">
           

            <div style="font-weight: bold; font-size: 18px; line-height: 24px; color: #000; border-top: 1px solid #ddd;"><br>
            Hi Team,</div>
            <br>'.$email_data.'            
  <table cellspacing="0" cellpadding="0" border="1" style="background:#038740;border:solid 1px #5ea352;border-radius:3px 3px 3px 3px; clear:both;" align="center">
<tbody>
	<tr>
    	<td style="border:none;padding:5px 45px;">
					<b><span style="font-size:14px;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;">
                    	<a target="_blank" href="https://rjio.billpro.online" style="text-decoration:none;">
                        	<span style="color:white;text-decoration:none">Login to BILLPRO</span></a><u></u><u></u></span></b>
		</td>
    </tr>
</tbody>
</table>
           
            <br>
            <br/>
Sincerely,<br>
                  The BillPro Team
            </div>

            <!-- ### END CONTENT ### -->
            <br><br>

          </td>
        </tr>
        
      </table>
      <!--/600px container -->

    </td>
  </tr>

</table>
<!--/100% wrapper-->
<img src="http://www.ifread.com/img/5991d867ec86010b66ca3a1f6f20b7df/ifread.gif" width="1" height="1" />
</body>
</html>

';
	
	$from = "BillPro System <noreply@rjio.billpro.online>";
	$headers = "Reply-To: ".$from."\r\n";
    $headers .= "Return-Path: ".$from."\r\n"; 
    $headers .= "From: ".$from."\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\nX-Priority: 3\r\nX-Mailer: PHP". phpversion() ."\r\n";

	//mail("kousik@codez.in","My subject","hello world");
	
	if(!mail($email_id,$subject,$_MAIL,$headers, 'O DeliveryMode=b'))
	{
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "FAIL";
	}
	else
	{
		//echo("Mail Sent");
		electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__);
		return "SUCCESS";
	}
}


function add_email_template_code($n)
{
	electricity_bill_debug(__LINE__,__FILE__,__FUNCTION__,electricity_bill_json_converter($n));
    return("%%".$n."%%");
}
#Mail Related Functions

#Notification Related Functions
function electricity_bill_single_notification_add($line,$file,$message,$board_id,$user_id)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($message,$board_id,$user_id));	
	$err_flag = 0;
	$insert_into_notification_query = "INSERT INTO tbl_notification (fld_notification_type,fld_notification_message,fld_board_id,fld_by_user,fld_count) VALUES ('general','".$message."','".$board_id."','".$user_id."','1')";
	$insert_into_notification_query_result = electricity_bill_query(__LINE__,__FILE__,$insert_into_notification_query);
	if(electricity_bill_affected_rows(__LINE__,__FILE__)>0){
		
	}else{
		$err_flag = 1;
	}
	return $err_flag;
}
function electricity_bill_combined_notification_add($line,$file,$enum_value,$board_id){
		electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($enum_value,$board_id));	
		$insert_or_update_query = "";
		$count = "";
		$ai_id = "";
		$error_flag = 0;
		$check_for_already_inserted_notificatino_query = "SELECT tbl_notification.fld_ai_id,tbl_notification.fld_count FROM tbl_notification WHERE tbl_notification.fld_board_id = '".$board_id."' AND DATE(tbl_notification.fld_timestamp) = DATE(NOW()) AND tbl_notification.fld_notification_type = '".$enum_value."';";
		if($check_for_already_inserted_notificatino_query_result = electricity_bill_query(__LINE__,__FILE__,$check_for_already_inserted_notificatino_query)){
			if(electricity_bill_num_rows(__LINE__,__FILE__,$check_for_already_inserted_notificatino_query_result) > 0){
					$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$check_for_already_inserted_notificatino_query_result);
					$count = $row['fld_count'] + 1;
					$ai_id = $row['fld_ai_id'];
					
					$insert_or_update_query = "UPDATE tbl_notification SET tbl_notification.fld_count = '".$count."',tbl_notification.fld_timestamp = NOW() WHERE tbl_notification.fld_ai_id = '".$ai_id."'";
			}else{
				$insert_or_update_query = "INSERT INTO tbl_notification(fld_notification_type,fld_count,fld_board_id) VALUES('".$enum_value."','1','".$board_id."');";	
			}
			if($insert_or_update_query != ''){
				$insert_or_update_query_result = electricity_bill_query(__LINE__,__FILE__,$insert_or_update_query);
				if(electricity_bill_affected_rows(__LINE__,__FILE__)>0){
					
				}else{
					$error_flag = 1;
				}
			}else{
				$error_flag = 1;
			}
		}else{
			$error_flag = 1;
		}
		return $error_flag;
}
#Notification Related Functions
#Log Change Related Functions
function electricity_bill_change_status_log($line,$file,$bill_id, $status, $user_id)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($bill_id, $status, $user_id));
	$status_array = array(''=>'Select a Status', 'generated'=>'Generated', 'approved_for_payment'=>'Approved for Payment', 'processing_for_payment'=>'Processing Payment', 'paid'=>'Paid', 'on_hold'=>'On Hold');
	
	$board_id_list_array = array();
	$board_id_list_query = 'SELECT tbl_customer_details.fld_ai_id, fld_board AS fld_board_id , fld_electricity_board, fld_consumer_id, fld_bill_id AS fld_internal_bill_no FROM tbl_customer_details LEFT JOIN tbl_electricity_board ON tbl_customer_details.fld_board = tbl_electricity_board.fld_ai_id WHERE tbl_customer_details.fld_ai_id IN ('.$bill_id.');';
	if($result = electricity_bill_query(__LINE__,__FILE__,$board_id_list_query))
	{
		if(electricity_bill_num_rows(__LINE__,__FILE__,$result))
		{
			while($row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$result))
			{
				$board_id_list_array[$row['fld_ai_id'].'_']['fld_board_id'] = $row['fld_board_id'];
				$board_id_list_array[$row['fld_ai_id'].'_']['fld_electricity_board'] = $row['fld_electricity_board'];
				$board_id_list_array[$row['fld_ai_id'].'_']['fld_consumer_id'] = $row['fld_consumer_id'];
				$board_id_list_array[$row['fld_ai_id'].'_']['fld_internal_bill_no'] = $row['fld_internal_bill_no'];
			}
		}
	}
	
	$success = TRUE;
	$bill_id_array = explode(',', $bill_id);
	foreach($bill_id_array as $key => $value)
	{
		$change_status_log_query = 'INSERT INTO tbl_bill_status_change_log(fld_customer_id, fld_status, fld_changed_by) VALUES ('.$value.', "'.$status.'", '.$user_id.');';
		if(!electricity_bill_query(__LINE__,__FILE__,$change_status_log_query))
		{
			$success = FALSE;
		}
		else
		{
			$message = str_replace("%%status%%", $status_array[$status], STATUS_CHANGE_NOTIFICATION_MESSAGE);
			$message = str_replace("%%consumer_id%%", $board_id_list_array[$value.'_']['fld_consumer_id'], $message);
			$message = str_replace("%%board%%", $board_id_list_array[$value.'_']['fld_electricity_board'], $message);
			$message = str_replace("%%internal_bill_no%%", $board_id_list_array[$value.'_']['fld_internal_bill_no'], $message);
			if(electricity_bill_single_notification_add(__LINE__,__FILE__,$message,$board_id_list_array[$value.'_']['fld_board_id'],$user_id) == 1)
			{
				$success = FALSE;
			}
		}
	}
	return $success;
}
#Log Change Related Functions
#Bill Check Related Functions
function electricity_bill_last_bill_check($line,$file, $consumer_id,$board){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($consumer_id,$board));
	$select_consumer_exist_query = "SELECT fld_ai_internalsite_id FROM tbl_sites WHERE fld_consumer_id = '".$consumer_id."' AND fld_discom_id = '".$board."'"	;
	if($select_consumer_exist_query_result = electricity_bill_query(__LINE__,__FILE__,$select_consumer_exist_query)){
		if(electricity_bill_num_rows(__LINE__,__FILE__,$select_consumer_exist_query_result) > 0){
			$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$select_consumer_exist_query_result);
			$ai_id = $row['fld_ai_internalsite_id'];
			$update_user_last_checked_time_query = "UPDATE tbl_sites SET fld_last_bill_checked = NOW() ,fld_cron_count = 0 WHERE  fld_ai_internalsite_id = ".$ai_id.";";
			$update_user_last_checked_time_result = electricity_bill_query(__LINE__,__FILE__,$update_user_last_checked_time_query);
			if(electricity_bill_affected_rows(__LINE__,__FILE__)>0){
				
			}else{
				
			}
		}
	}
}


function fetch_page($line,$file,$pass_discom_id,$url, $referer, $header, $post, $current_attempt = 0, $with_header = 1,$allow_302 = 0,$is_redirecturl=0,$allow_303 = 0,$allow_301 = 0) {
	
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($url, $referer, $header, $post, $current_attempt, $with_header,$allow_302));
	//echo "Calling: ".$url. $post."////Attempt: ".$current_attempt;
	
	$timeout = 380;
	//$timeout = 500;
	if($pass_discom_id == 76){
		$timeout = 500;
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	
	if($pass_discom_id == 21){
		curl_setopt($ch, CURLOPT_POST, 1);
	}
	if(!is_null($post)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
	}
/*	$discoms_enable_proxy = array(7,188);//,29
	if(electricity_bill_in_array(__LINE__,__FILE__,$pass_discom_id,$discoms_enable_proxy)) {
		//echo 'setting proxy : ' . $url;
		//$proxy_var = '139.59.66.158:8086';
		//$proxy_var = '159.89.167.246:8086';
		$proxy_var = '159.65.149.250:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	$odisha_proxy = array(58,59,60,178);
	if(electricity_bill_in_array(__LINE__,__FILE__,$pass_discom_id,$odisha_proxy)){
		//$proxy_var = '159.65.149.250:8086';	
		$proxy_var = '159.89.167.246:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	$gujrat_proxy = array(51,52,6,33,36);
	if(electricity_bill_in_array(__LINE__,__FILE__,$pass_discom_id,$gujrat_proxy)){
		$proxy_var = '139.59.66.158:8086';	
		$proxy_var = '159.89.167.246:8086';
		
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	$gped_proxy = array(76,139);
	if(electricity_bill_in_array(__LINE__,__FILE__,$pass_discom_id,$gped_proxy)){
		//$proxy_var = '139.59.66.158:8086';	
		$proxy_var = '159.89.167.246:8086';
		
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 12  || $pass_discom_id == 30 || $pass_discom_id == 156){
		$proxy_var = '172.105.50.48:8086';
		//$proxy_var = '159.89.167.246:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 184 || $pass_discom_id == 145 || $pass_discom_id == 83 || $pass_discom_id == 76 || $pass_discom_id == 63 || $pass_discom_id == 152 || $pass_discom_id == 154){
		//$proxy_var = '139.59.66.158:8086';
		//$proxy_var = '172.105.50.48:8086';
		//$proxy_var = '159.65.149.250:8086';
		$proxy_var = '159.89.167.246:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 20){
		//$proxy_var = '159.89.167.246:8086';
		//$proxy_var = '139.59.66.158:8086';
		$proxy_var = '172.105.50.48:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 22 || $pass_discom_id == 204){
		$proxy_var = '159.65.149.250:8086';
		$proxy_var = '159.89.167.246:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if( $pass_discom_id == 24 || $pass_discom_id == 26 || $pass_discom_id == 27 || $pass_discom_id == 28 || $pass_discom_id == 29 || $pass_discom_id == 47 || $pass_discom_id == 37){

		$proxy_var = '172.105.50.48:8086';
		$proxy_var = '159.89.167.246:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 41 || $pass_discom_id == 18){

		$proxy_var = '172.105.50.48:8086';
		$proxy_var = '159.89.167.246:8086';
		//$proxy_var = '139.59.66.158:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 174 || $pass_discom_id == 183 ){
		//$proxy_var = '172.105.50.48:8086';
		//$proxy_var = '159.89.167.246:8086';
		$proxy_var = '139.59.66.158:8086';
		//$proxy_var = '159.65.149.250:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 10 || $pass_discom_id == 175 || $pass_discom_id == 162){
		$proxy_var = '159.65.149.250:8086';
		//$proxy_var = '139.59.66.158:8086';
		//$proxy_var = '159.89.167.246:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 190){
		$proxy_var = '139.59.66.158:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	$discoms_enable_proxy_250 = array(14,74,81,19,62,21);
	if(electricity_bill_in_array(__LINE__,__FILE__,$pass_discom_id,$discoms_enable_proxy_250)) {

		$proxy_var = '159.65.149.250:8086';
		$proxy_var = '172.105.50.48:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 82){
		//$proxy_var = '139.59.66.158:8086';
		//$proxy_var = '172.105.50.48:8086';
		//$proxy_var = '159.65.149.250:8086';
		$proxy_var = '159.89.167.246:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
*/
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if($pass_discom_id == 143) { curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); }
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	if ($with_header == 1) {
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
	}
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0');
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	if (!is_null($header)) {
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	if($is_redirecturl == 1){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);	
	}

	
	$data = curl_exec($ch);	
	//echo '<pre>';print_r($data);echo '</pre>';
	
	if (false === $data) { 
	//print_r(curl_getinfo($ch));
		//Check if Curl Responds Properly
		sleep(10);
		if($pass_discom_id == 50){
			echo 'in 2';
		}
		if (2 == $current_attempt) {
			//echo "False: ".$url. "/////Curl Failure////Attempt: ".$current_attempt." ".curl_error($ch);;				
			return false;
		}
		return fetch_page(__LINE__,__FILE__,$pass_discom_id,$url, $referer, $header, $post, $current_attempt + 1, $with_header,$allow_302,$is_redirecturl,$allow_303,$allow_301);
	}

	$response = curl_getinfo( $ch );
	//echo '<pre>';print_r($response);echo '</pre>';
	if (1 == $allow_302) {
		if ($response['http_code'] == 302) { 
			return $data;
		}		
	}
	if (1 == $allow_301) {
		if ($response['http_code'] == 301) { 
			return $data;
		}		
	}
	if ($response['http_code'] != 200) { 
	if ((303 == $response['http_code']) && (1 == $allow_303) ) { 
			return $response;		
	}
	//Check if Curl wsa able to fetch the page properly
		sleep(10);
		if (2 == $current_attempt) {
		//	echo "False: ".$url. "/////Code: ".$response['http_code']."////Attempt: ".$current_attempt;
			return false;
		}
		return fetch_page(__LINE__,__FILE__,$pass_discom_id,$url, $referer, $header, $post, $current_attempt + 1, $with_header,$allow_302,$is_redirecturl,$allow_303,$allow_301);
	}
	return $data;
}
#Bill Check Related Functions
#Fetch Page Related Function
function fetch_page_1($line,$file,$pass_discom_id,$url, $referer, $header, $post, $current_attempt = 0, $with_header = 1,$allow_302 = 0,$is_redirecturl=0,$allow_303 = 0,$allow_301 = 0) {
	
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($url, $referer, $header, $post, $current_attempt, $with_header,$allow_302));
	//echo "Calling: ".$url. $post."////Attempt: ".$current_attempt;
	$timeout = 60;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_POST, 1);
	if(!is_null($post)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		
	}
	//curl_setopt($ch, CURLOPT_NOBODY, 1);
	$discoms_enable_proxy = array(20,7,38,49,76,50,80,33,51,52,58,59,60);//,29
	if(electricity_bill_in_array(__LINE__,__FILE__,$pass_discom_id,$discoms_enable_proxy)) {
		//echo 'setting proxy : ' . $url;
		$proxy_var = '139.59.66.158:8086';
		
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 12  || $pass_discom_id == 30){
		$proxy_var = '172.105.50.48:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 19 || $pass_discom_id == 22 ){
		//$proxy_var = '139.59.66.158:8086';
		$proxy_var = '172.105.50.48:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	
	if($pass_discom_id == 24 || $pass_discom_id == 63 || $pass_discom_id == 26 || $pass_discom_id == 27 || $pass_discom_id == 28 || $pass_discom_id == 29 || $pass_discom_id == 38 ){
		$proxy_var = '172.105.50.48:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	if($pass_discom_id == 41){
		$proxy_var = '172.105.50.48:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	$discoms_enable_proxy_250 = array(10,14,74,81);
	if(electricity_bill_in_array(__LINE__,__FILE__,$pass_discom_id,$discoms_enable_proxy_250)) {
		$proxy_var = '159.65.149.250:8086';
		curl_setopt($ch, CURLOPT_PROXY, $proxy_var);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if($pass_discom_id == 143) { curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); }
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	if ($with_header == 1) {
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

	}
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0');
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	if (!is_null($header)) {
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);


	}

	if($is_redirecturl == 1){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);	
	}

/* 	if($pass_discom_id == 6){
		curl_setopt($ch, CURLOPT_PROXY,"107.175.218.166");         
		curl_setopt($ch, CURLOPT_PROXYPORT,"8086");
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, ':');
	} */
	$data = curl_exec($ch);	
	
	var_dump($data);
	
	/*if (false === $data) { 
		//Check if Curl Responds Properly
		sleep(10);
		if (2 == $current_attempt) {
			//echo "False: ".$url. "/////Curl Failure////Attempt: ".$current_attempt." ".curl_error($ch);;				
			return false;
		}
		return fetch_page(__LINE__,__FILE__,$pass_discom_id,$url, $referer, $header, $post, $current_attempt + 1, $with_header,$allow_302,$is_redirecturl,$allow_303,$allow_301);
	}*/
	
	$response = curl_getinfo( $ch );
		var_dump($response);
	if (1 == $allow_302) {
		if ($response['http_code'] == 302) { 
			return $data;
		}		
	}
	/*
	if (1 == $allow_301) {
		if ($response['http_code'] == 301) { 
			return $data;
		}		
	}
	if ($response['http_code'] != 200) { 
	if ((303 == $response['http_code']) && (1 == $allow_303) ) { 
			return $response;		
	}
	//Check if Curl wsa able to fetch the page properly
		sleep(10);
		if (2 == $current_attempt) {
		//	echo "False: ".$url. "/////Code: ".$response['http_code']."////Attempt: ".$current_attempt;
			return false;
		}
		return fetch_page(__LINE__,__FILE__,$pass_discom_id,$url, $referer, $header, $post, $current_attempt + 1, $with_header,$allow_302,$is_redirecturl,$allow_303,$allow_301);
	}*/
	return $data;
}
#Fetch Page Related Function
#CESC Consumer Related Functions
function get_consumer_parameters_CESC($line,$file,$string,$fixed_data){
	
	//electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string,$fixed_data));
	$data_assoc = array();			
	for($index =0;$index<sizeof($fixed_data);$index++){
			$data = '';
			if(strpos($string,'<input type="hidden" name="'.$fixed_data[$index].'" value="') !== false ){
				$data = get_string_between(__LINE__,__FILE__,$string,'<input type="hidden" name="'.$fixed_data[$index].'" value="','"');
			}
			else {
				return false;
			}
			$data_assoc[$fixed_data[$index]] = $data;
	}
	return 	$data_assoc;		
}
#CESC Consumer Related Functions
#WBSEDCL Consumer Related Functions
function get_parameters_WBSEDCL($line,$file,$string,$fixed_data){
	
	//electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string,$fixed_data));
	$data_assoc = array();			
	for($index =0;$index<sizeof($fixed_data);$index++){
			$data = '';
			if(strpos($string,'<input type="hidden" name="'.$fixed_data[$index].'" value="') !== false ){
				$data = get_string_between(__LINE__,__FILE__,$string,'<input type="hidden" name="'.$fixed_data[$index].'" value="','"');
			}
			else {
				return false;
			}
			$data_assoc[$fixed_data[$index]] = $data;
	}
	return 	$data_assoc;		
}
#WBSEDCL Consumer Related Functions
#NDMC Consumer Related Functions
function get_parameters_NDMC($line,$file,$string,$fixed_data){
	
	//electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string,$fixed_data));
	$data_assoc = array();			
	for($index =0;$index<sizeof($fixed_data);$index++){
			$data = '';
			if(strpos($string,'<input type="hidden" id="'.$fixed_data[$index].'" name="'.$fixed_data[$index].'" value="') !== false ){
				$data = get_string_between(__LINE__,__FILE__,$string,'<input type="hidden" id="'.$fixed_data[$index].'" name="'.$fixed_data[$index].'" value="','"');
			}else{
				return false;
			}
			$data_assoc[$fixed_data[$index]] = $data;
	}
	return 	$data_assoc;		
}
#NDMC Consumer Related Functions
#AVVNL Consumer Related Functions
function get_consumer_parameters_AVVNL($line,$file,$string,$fixed_data){
	   // electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string,$fixed_data));
		$data_assoc = array();			
		for($index =0;$index<sizeof($fixed_data);$index++){
				$data = '';
				if(strpos($string,'<input type="hidden" name="'.$fixed_data[$index].'" id="'.$fixed_data[$index].'" value="') !== false ){
					$data = get_string_between(__LINE__,__FILE__,$string,'<input type="hidden" name="'.$fixed_data[$index].'" id="'.$fixed_data[$index].'" value="','"');
					if($data == false){
						$data = '';	
					}
				}
				else {
					return false;
				}
				$data_assoc[$fixed_data[$index]] = $data;
		}
		return 	$data_assoc;		
	}
#AVVNL Consumer Related Functions
#Exception Related Function
function get_exception_by_customer($line,$file,$site_ai_id){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($site_ai_id));
	$exception_text = '';
	$meter_details_substring = '';
	$board_short_name = '';
	$board_full_name ='';
	$circle_name ='';
	$consumer_id ='';
	$consumer_name = '';
	$bill_link = '';
	$site_id = '';
	$insert_substring = '';
	$exception_generated_flag = false;
	$customer_assoc = array();
	$template_data_array = array();
	$template_value_array = array();
	$current_date = electricity_bill_today_date(__LINE__,__FILE__);
	$subject = electricity_bill_date_format_with_formatted(__LINE__,__FILE__,$current_date);
	$check_for_valid_consumer_query = "SELECT fld_ai_internalsite_id,fld_discom_id,fld_is_automated,fld_consumer_id,tbl_sites.fld_organizationsite_id FROM tbl_sites WHERE fld_ai_internalsite_id = '".$site_ai_id."';";
	$check_for_valid_consumer_query_result = electricity_bill_query(__LINE__,__FILE__,$check_for_valid_consumer_query);
	if(electricity_bill_num_rows(__LINE__,__FILE__,$check_for_valid_consumer_query_result)>0)
	{
		$row_data = electricity_bill_fetch_assoc(__LINE__,__FILE__,$check_for_valid_consumer_query_result);
		$customer_ai_id = $row_data['fld_ai_internalsite_id'];
		$customer_is_automated = $row_data['fld_is_automated'];
		$customer_id = $row_data['fld_consumer_id'];
		$board_id = $row_data['fld_discom_id'];
		$site_id = $row_data['fld_organizationsite_id'];
		if($customer_is_automated == '1'){
			$get_bills_of_current_and_previous_month_query = "SELECT tbl_discoms.fld_ai_id AS `electricity_board_id`, tbl_sites.fld_organizationsite_id, tbl_bills.fld_file_name, tbl_circles.fld_ai_id AS `circle_id`, tbl_sites.fld_zone_id, tbl_bills.fld_generated_date, tbl_bills.fld_due_date, tbl_bills.fld_ai_id, tbl_bills.fld_energy_charges, tbl_bills.fld_tarrif_code, tbl_bills.fld_penalty, tbl_bills.fld_arrears, tbl_bills.fld_amount, tbl_bills.fld_bill_month, tbl_bills.fld_bill_year,tbl_bills.fld_payableamount_current_month,fld_rebate,fld_current_month_dps,fld_previous_month_dps FROM tbl_bills LEFT JOIN tbl_discoms ON tbl_discoms.fld_ai_id = tbl_bills.fld_discom_id LEFT JOIN tbl_circles ON tbl_circles.fld_ai_id = tbl_discoms.fld_circle_id LEFT JOIN tbl_sites ON tbl_sites.fld_ai_internalsite_id = tbl_bills.fld_internalsite_id WHERE tbl_sites.fld_ai_internalsite_id = '".$site_ai_id."' ORDER BY tbl_bills.fld_datetime DESC LIMIT 0,2; ";
			$get_bills_of_current_and_previous_month_query_result = electricity_bill_query(__LINE__,__FILE__,$get_bills_of_current_and_previous_month_query);
			if(electricity_bill_num_rows(__LINE__,__FILE__,$get_bills_of_current_and_previous_month_query_result)>0){			
				$inner_index = 0;	
				$result_count = electricity_bill_num_rows(__LINE__,__FILE__,$get_bills_of_current_and_previous_month_query_result);
				while($row_data = electricity_bill_fetch_assoc(__LINE__,__FILE__,$get_bills_of_current_and_previous_month_query_result)){
					if($inner_index == 0){
						$insert_substring .= "'".$site_ai_id."'";
						
						$circle_id = $row_data['circle_id'];
						if($insert_substring != ''){
							$insert_substring .=",";
						}
						$insert_substring .= "'".$circle_id."'";
						$board_id = $row_data['electricity_board_id'];
						if($insert_substring != ''){
							$insert_substring .=",";
						}
						$insert_substring .= "'".$board_id."'";
						
						$zone_id = $row_data['fld_zone_id'];
						if($insert_substring != ''){
							$insert_substring .=",";
						}
						$insert_substring .= "'".$zone_id."'";
						
						$bill_id = $row_data['fld_ai_id'];
						if($insert_substring != ''){
							$insert_substring .=",";
						}
						$insert_substring .= "'".$bill_id."'";
						
						if($result_count == 1){
							$previous_bill_link  = 'NULL';
							if($insert_substring != ''){
								$insert_substring .=",";
							}
							$insert_substring .= $previous_bill_link;							
						}															
					}		
					
					if($inner_index == 1){
						$previous_bill_id  = $row_data['fld_ai_id'];
						if($insert_substring != ''){
							$insert_substring .=",";
						}
						$insert_substring .= "'".$previous_bill_id."'";
						
					}		
																
					if($meter_details_substring != ''){
						$meter_details_substring .=' OR ';	
					}
					$meter_details_substring .= 'tbl_bill_meter_details.fld_bill_id="'.$row_data['fld_ai_id'].'"';
					$customer_assoc[$inner_index] = array();
					$customer_assoc[$inner_index]['bill_ai_id'] = $row_data['fld_ai_id'];
					$customer_assoc[$inner_index]['amount'] = $row_data['fld_amount'];
					$customer_assoc[$inner_index]['bill_date'] = electricity_bill_date_format_with_formatted(__LINE__,__FILE__,$row_data['fld_generated_date']);					
					$customer_assoc[$inner_index]['due_date'] = electricity_bill_date_format_with_formatted(__LINE__,__FILE__,$row_data['fld_due_date']);
					$customer_assoc[$inner_index]['energy_charges'] = $row_data['fld_energy_charges'];
					$customer_assoc[$inner_index]['tarrif_code'] = $row_data['fld_tarrif_code'];
					$customer_assoc[$inner_index]['penalty'] = $row_data['fld_penalty'];
					$customer_assoc[$inner_index]['arrears'] = $row_data['fld_arrears'];
					$customer_assoc[$inner_index]['bill_month'] = $row_data['fld_bill_month'];
					$customer_assoc[$inner_index]['bill_year'] = $row_data['fld_bill_year'];
					$customer_assoc[$inner_index]['payableamount_current_month'] = $row_data['fld_payableamount_current_month'];
					$customer_assoc[$inner_index]['rebate'] = $row_data['fld_rebate'];
					$customer_assoc[$inner_index]['current_month_dps'] = $row_data['fld_current_month_dps'];
					$customer_assoc[$inner_index]['previous_month_dps'] = $row_data['fld_previous_month_dps'];
					$customer_assoc[$inner_index++]['meter'] = array();
				}	
				if($meter_details_substring != ''){
					$inner_index = 0;
					$meter_details_assoc = array();
					$get_meter_details_query = "SELECT tbl_bill_meter_details.fld_billed_unit,tbl_bill_meter_details.fld_meter_no, tbl_bill_meter_details.fld_bill_id, tbl_bill_meter_details.fld_previous_reading, tbl_bill_meter_details.fld_meter_current_reading, tbl_bill_meter_details.fld_meter_load, tbl_bills.fld_bill_month, tbl_bills.fld_energy_charges,tbl_bill_meter_details.fld_past_reading_date,tbl_bill_meter_details.fld_present_reading_date FROM tbl_bill_meter_details LEFT JOIN tbl_bills ON tbl_bills.fld_ai_id = tbl_bill_meter_details.fld_bill_id WHERE (".$meter_details_substring.") ORDER BY tbl_bill_meter_details.fld_bill_id DESC;";
					$get_meter_details_query_result = electricity_bill_query(__LINE__,__FILE__,$get_meter_details_query);
					if(electricity_bill_num_rows(__LINE__,__FILE__,$get_meter_details_query_result)>0){
						while($row_data = electricity_bill_fetch_assoc(__LINE__,__FILE__,$get_meter_details_query_result)){
							$meter_details_assoc = array();
							$meter_details_assoc['meter_no'] = $row_data['fld_meter_no'];
							$meter_details_assoc['previous_reading'] = $row_data['fld_previous_reading'];
							$meter_details_assoc['current_reading'] = $row_data['fld_meter_current_reading'];
							$meter_details_assoc['energy_charges'] = $row_data['fld_energy_charges'];	
							$meter_details_assoc['meter_load'] = $row_data['fld_meter_load'];
							$meter_details_assoc['billed_unit'] = $row_data['fld_billed_unit'];	
							$meter_details_assoc['past_reading_date'] = $row_data['fld_past_reading_date'];
							$meter_details_assoc['present_reading_date'] = $row_data['fld_present_reading_date'];					
							if($customer_assoc[$inner_index]['bill_ai_id'] != $row_data['fld_bill_id']){
								$inner_index = $inner_index+1;										
							}
							$customer_assoc[$inner_index]['meter'][]=$meter_details_assoc;
						}
						$exception_generated_substring = '';
						$bill_amount_bill_date_substring = '';
						$exception_array = array();
						$send_mail_flag = false;
						$send_mail_text = "";
						for($index=0;$index <= $inner_index;$index++){
							if($index == 0){
								
								//============================================================================AMOUNT=============================================================================
								if($customer_assoc[$index]['amount'] > 500000){
									$exception_generated_flag = true;
									$exception_generated_substring = "High Bill Amount: Rs. ".electricity_bill_number_format(__LINE__,__FILE__,$customer_assoc[$index]['amount'],2);	
									$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"large-amount");								
								}
								if($customer_assoc[$index]['amount'] < 0){
									$exception_generated_flag = true;
									$exception_generated_substring = "Negative Bill Amount: Rs. ".electricity_bill_number_format(__LINE__,__FILE__,$customer_assoc[$index]['amount'],2);	
									$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"negative-amount");								
								}									
								//============================================================================PENALTY=============================================================================

								if(floatval($customer_assoc[$index]['penalty']) > 1000){
									$exception_generated_flag = true;
									$exception_generated_substring ="Non-zero Penalty: Rs. ".electricity_bill_number_format(__LINE__,__FILE__,$customer_assoc[$index]['penalty'],2);									
									$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"non-zero-penalty");
								}
								//============================================================================ARREARS=============================================================================
								if(floatval($customer_assoc[$index]['arrears']) > 5000){
									$exception_generated_flag = true;
									$exception_generated_substring ="Non-zero Arrears: Rs. ".electricity_bill_number_format(__LINE__,__FILE__,$customer_assoc[$index]['arrears'],2);									
									$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"non-zero-arrears");
								}
								//============================================================================Energy Charges=============================================================================
								if($inner_index == 1){
									if($customer_assoc[$index+1]['energy_charges'] > 0){
										if(((($customer_assoc[$index]['energy_charges'] - $customer_assoc[$index+1]['energy_charges'])/$customer_assoc[$index+1]['energy_charges'])*100) > 15){
											$exception_generated_flag = true;
											$exception_generated_substring ="Energy Charges Variance wrt previous month bill: ".electricity_bill_number_format_php(__LINE__,__FILE__,((($customer_assoc[$index]['energy_charges'] - $customer_assoc[$index+1]['energy_charges'])/$customer_assoc[$index+1]['energy_charges'])*100),2)."%";											
											$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"energy-charges-variance");
										}
									}else{
										if(((($customer_assoc[$index]['energy_charges'] - $customer_assoc[$index+1]['energy_charges']))*100) > 15){
											$exception_generated_flag = true;
											$exception_generated_substring ="Energy Charges Variance wrt previous month bill: ".electricity_bill_number_format_php(__LINE__,__FILE__,((($customer_assoc[$index]['energy_charges'] - $customer_assoc[$index+1]['energy_charges']))*100),2)."%";											
											$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"energy-charges-variance");
										}	
									}
								
//============================================================================TARRIF=============================================================================
									if($customer_assoc[$index]['tarrif_code'] != $customer_assoc[$index+1]['tarrif_code']){
										$exception_generated_flag = true;
										$exception_generated_substring ="Tariff Code Mismatch.Present: ".$customer_assoc[$index]['tarrif_code'].", Previous: ".$customer_assoc[$index+1]['tarrif_code'];										
										$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"tarrif-mismatch");
									}																																													
									
//============================================================================Meter reading&Load=============================================================================
									if(sizeof($customer_assoc[$index]['meter']) == sizeof($customer_assoc[$index+1]['meter'])){
										for($inner_loop_index=0;$inner_loop_index<sizeof($customer_assoc[$index]['meter']);$inner_loop_index++){
											$malfunction_billdata_flag = false;
											if($customer_assoc[$index]['meter'][$inner_loop_index]['past_reading_date'] == '0000-00-00' || $customer_assoc[$index]['meter'][$inner_loop_index]['present_reading_date'] == '0000-00-00'){
												$exception_generated_flag = true;
												$malfunction_billdata_flag = true;
												if($customer_assoc[$index]['meter'][$inner_loop_index]['past_reading_date'] == '0000-00-00'){
													$exception_generated_substring ="Malfunction Bill Data. Previous Meter Reading Date : N/A";											
												}else{
													$exception_generated_substring ="Malfunction Bill Data. Current Meter Reading Date : N/A";
												}
												$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"malfunction-bill-data");
											}	
											
											if($customer_assoc[$index]['meter'][$inner_loop_index]['previous_reading'] != $customer_assoc[$index+1]['meter'][$inner_loop_index]['current_reading']){
												$exception_generated_flag = true;
												$exception_generated_substring ="Mismatch in Meter Readings, Current Bill Opening: ".$customer_assoc[$index]['meter'][$inner_loop_index]['previous_reading'].", Previous Bill Closing: ".$customer_assoc[$index+1]['meter'][$inner_loop_index]['current_reading'];												
												$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"meter-reading-mismatch");
											}
											
											if($customer_assoc[$index]['meter'][$inner_loop_index]['meter_load'] != $customer_assoc[$index+1]['meter'][$inner_loop_index]['meter_load']){
												$exception_generated_flag = true;
												$exception_generated_substring ="Mismatch in Meter Load, Previous Meter Load: ".$customer_assoc[$index+1]['meter'][$inner_loop_index]['meter_load'].", Current Meter Load: ".$customer_assoc[$index]['meter'][$inner_loop_index]['meter_load'];												
												$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"meter-load-mismatch");
											}
											
											if($customer_assoc[$index]['meter'][$inner_loop_index]['meter_no'] != $customer_assoc[$index+1]['meter'][$inner_loop_index]['meter_no']){
												$exception_generated_flag = true;
												$exception_generated_substring ="Mismatch in Meter Number, Previous Meter Number: ".$customer_assoc[$index+1]['meter'][$inner_loop_index]['meter_no'].", Current Meter Number: ".$customer_assoc[$index]['meter'][$inner_loop_index]['meter_no'];												
												$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"meter-mismatch");
											}
											if($malfunction_billdata_flag == false){
												if($customer_assoc[$index]['meter'][$inner_loop_index]['past_reading_date'] != $customer_assoc[$index+1]['meter'][$inner_loop_index]['present_reading_date']){
													$exception_generated_flag = true;
													$exception_generated_substring ="Missing Bill, Previous Bill Current Meter Reading Date: ".$customer_assoc[$index+1]['meter'][$inner_loop_index]['present_reading_date'].", Current Bill Past Reading Date: ".$customer_assoc[$index]['meter'][$inner_loop_index]['present_reading_date'];												
													$exception_array[] = array("text"=>$exception_generated_substring,"type"=>"missing-bill");
												}
											}
										}
									}else{	
										$exception_strin_segment .= "mismatch in total no of meters";	
									}
								}
								
								//============================================================================END=============================================================================	
								
								if($customer_assoc[$index]['energy_charges'] == "0.00"){
									$send_mail_flag = true;
									$send_mail_text .= '<li>Energy Charge: '.$customer_assoc[$index]['energy_charges'].'</li>';	
								}
								if($customer_assoc[$index]['payableamount_current_month'] == "0.00"){
									$send_mail_flag = true;
									$send_mail_text .= '<li>Payable Amount for Current Month: '.$customer_assoc[$index]['payableamount_current_month'].'</li>';	
								}
								for($inner_loop_index=0;$inner_loop_index<sizeof($customer_assoc[$index]['meter']);$inner_loop_index++){
									if($customer_assoc[$index]['meter'][$inner_loop_index]['current_reading'] == 0){
										$send_mail_flag = true;
										$send_mail_text .= '<li>Current Meter Reading: '.$customer_assoc[$index]['meter'][$inner_loop_index]['current_reading'].'</li>';
									}
									if($customer_assoc[$index]['meter'][$inner_loop_index]['billed_unit'] == 0.00){
										$send_mail_flag = true;
										$send_mail_text .= '<li>Billed Unit: '.$customer_assoc[$index]['meter'][$inner_loop_index]['billed_unit'].'</li>';
									}	
								}
								if($customer_assoc[$index]['rebate'] <= 0){
									$send_mail_flag = true;
									$send_mail_text .= '<li>Rebate: '.$customer_assoc[$index]['rebate'].'</li>';	
								}
								if($customer_assoc[$index]['current_month_dps'] <= 0){
									$send_mail_flag = true;
									$send_mail_text .= '<li>Current Month DPS: '.$customer_assoc[$index]['current_month_dps'].'</li>';	
								}
								if($customer_assoc[$index]['previous_month_dps'] <= 0){
									$send_mail_flag = true;
									$send_mail_text .= '<li>DPS: '.$customer_assoc[$index]['previous_month_dps'].'</li>';	
								}
							}																										
						}
						if($send_mail_flag == true){
							$template_data_array = array('CONSUMER_ID','SITE_ID','ERROR_DATA');
							$template_value_array = array($customer_id,$site_id,$send_mail_text);
							global $mailTempalte;
							electricity_bill_send_mail(__LINE__,__FILE__,$mailTempalte['newbill_zero_content'],$template_data_array,$template_value_array,QUERY_FAILED_RECEIVER,$mailTempalte['newbill_zero_subject']);
						}
						if($exception_generated_flag === true){
							$insert_query_string = "";
							foreach($exception_array as $value){
								if($insert_query_string != ""){
										$insert_query_string .= ",";
								}
								$insert_query_string .= "(".$insert_substring.",'".$value['type']."','".$value['text']."')";
							}
							electricity_bill_commit_off(__LINE__,__FILE__);
							$insert_query = "INSERT INTO tbl_exception(fld_internalsite_id,fld_circle_id,fld_discom_id,fld_zone_id,fld_present_bill_internalid,fld_previous_bill_internalid,fld_exception_type,fld_exception_text) VALUES ".$insert_query_string.";";
							$insert_query_result =  electricity_bill_query(__LINE__,__FILE__,$insert_query);
							if(electricity_bill_affected_rows(__LINE__,__FILE__) > 0){
								electricity_bill_commit(__LINE__,__FILE__);
							}
							electricity_bill_commit_on(__LINE__,__FILE__);
						}
						
					}else{
						return false;	
					}
				}else{
					return false;	
				}					
			}else{
				return false;	
			}
		}else{
			return false;	
		}
	}else{
		return false;	
	}
}
#Exception Related Function

#Exception For Previous Month Data
function data_previous_bill($line,$file,$consumer_id,$board_id,$due_date,$bill_month,$bill_year,$file_name){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($consumer_id,$board_id,$due_date,$file_name));
	$receiver = "arup@codez.in,kousik@codez.in";
	$error_message = "";
	$saved_bill_ai_id = "";
	$exception_text_substring = '';
	$return_flag = 0;
	$return_array = array();
	$select_board_deatils_query = "SELECT fld_electricity_board,fld_circle,fld_short_form FROM tbl_electricity_board LEFT JOIN tbl_circles ON tbl_circles.fld_ai_id = tbl_electricity_board.fld_circle_id WHERE tbl_electricity_board.fld_ai_id = '".$board_id."'";
	$select_board_deatils_query_result = electricity_bill_query(__LINE__,__FILE__,$select_board_deatils_query);
	if(electricity_bill_num_rows(__LINE__,__FILE__,$select_board_deatils_query_result) > 0){
		$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$select_board_deatils_query_result);
		$circle = $row['fld_circle'];
		$board_name = $row['fld_electricity_board'];
		$board_short_form = $row['fld_short_form'];
	}
	
	$check_already_exist_or_not_query = "SELECT fld_ai_id FROM tbl_customer_details WHERE fld_consumer_id = '".$consumer_id."' AND fld_bill_month = '".$bill_month."' AND fld_bill_year = '".$bill_year."' AND fld_board = '".$board_id."'";
	$check_already_exist_or_not_query_result = electricity_bill_query(__LINE__,__FILE__,$check_already_exist_or_not_query);
	if(electricity_bill_num_rows(__LINE__,__FILE__,$check_already_exist_or_not_query_result) > 0){	
		$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$check_already_exist_or_not_query_result);
		$saved_bill_ai_id = $row['fld_ai_id'];
		//$error_message = "Data already stored into database";
	}
	$select_top_consumer_ai_id_query = "SELECT  fld_ai_id,fld_due_date,fld_file_name,fld_name FROM tbl_customer_details WHERE fld_ai_id = (SELECT MAX(fld_ai_id) FROM tbl_customer_details WHERE fld_consumer_id = '".$consumer_id."' AND  fld_board = '".$board_id."');";
	$select_top_consumer_ai_id_query_result = electricity_bill_query(__LINE__,__FILE__,$select_top_consumer_ai_id_query);
	if(electricity_bill_num_rows(__LINE__,__FILE__,$select_top_consumer_ai_id_query_result) > 0){
			$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$select_top_consumer_ai_id_query_result);
			$flag = false;
			$top_ai_id = $row['fld_ai_id'];
			$top_due_date = $row['fld_due_date'];
			$file_name_saved = $row['fld_file_name'];
			$consumer_name = $row['fld_name'];
			if($saved_bill_ai_id != ""){
				if($saved_bill_ai_id == $top_ai_id){
					$error_message = "Data already stored into database";
					$return_flag = 1;
				}else if($saved_bill_ai_id < $top_ai_id){
					$error_message = "Getting bill with previously saved data";
					$flag = true;
					$exception_text_substring = '<ul style="padding-left:15px; padding-bottom:20px;">
													<li><strong>A New Bill from '.$board_short_form.' was found with Previously Saved Data</strong></li>
												</ul>';
				}
			}else{
				if(electricity_bill_strtotime(__LINE__,__FILE__,$due_date) < electricity_bill_strtotime(__LINE__,__FILE__,$top_due_date)){
					$error_message = "Getting bill with expired due date";
					$flag = true;
					$exception_text_substring = '<ul style="padding-left:15px; padding-bottom:20px;">
													<li><strong>A New Bill from '.$board_short_form.' was found with Expired Due Date</strong></li>
												</ul>';
				}
			}
			if($flag == true){
				$return_flag = 2;
				$table_data_1 = '<tr>
									<td>'.electricity_bill_date_format_with_formatted(__LINE__,__FILE__,$due_date).'</td>
									<td>&nbsp;</td>
									<td><a href="'.ROOT_PATH.'/download/'.$file_name.'" style="color:#038740;text-decoration:underline">Click to View</a></strong></td>
								</tr>';	
								
				$table_data_2 = '<tr>
									<td>'.electricity_bill_date_format_with_formatted(__LINE__,__FILE__,$top_due_date).'</td>
									<td>&nbsp;</td>
									<td><a href="'.ROOT_PATH.'/download/'.$file_name_saved.'" style="color:#038740;text-decoration:underline">Click to View</a></strong></td>
								</tr>';	
				$template_data_array = array('EXCEPTION_HEADING','BOARD_SHORT_NAME','CIRCLE_NAME','BOARD_FULL_NAME','CONSUMER_ID','CONSUMER_NAME','TABLE_CONTENT_1','TABLE_CONTENT_2');
				$template_value_array = array($exception_text_substring,$board_short_form,$circle,$board_name,$consumer_id,$consumer_name,$table_data_1,$table_data_2);	
				global $mailTempalte;	
				electricity_bill_send_mail(__LINE__,__FILE__,$mailTempalte['newbill_exception_2'], $template_data_array,$template_value_array,$receiver,$mailTempalte['newbill_exception_2_subject']);
			}
	}
	$return_array['flag'] = $return_flag;
	$return_array['error_message'] = $error_message;
	return $return_array;
}
function save_file_dir($line,$file,$from_flag,$dir_name='download/'){
	electricity_bill_debug($line,$file,__FUNCTION__);
	$temp_folder_name = date("Y-m-d");
	if($from_flag == "0"){
		$file_destination = "";
	}else{
		$file_destination = "../";
	}
	if(!file_exists($file_destination.$dir_name.$temp_folder_name)) {
    	 mkdir($file_destination.$dir_name.$temp_folder_name, 0777, true);
    }
	return $file_destination.$dir_name.$temp_folder_name;
}
function electricity_bill_date_diff($line,$file,$date_1,$date_2){
//echo "<br/>".$date_1.",".$date_2."------".$line."------".$file;
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date_1,$date_2));
	$date1=date_create($date_1);
	$date2=date_create($date_2);
	$diff=date_diff($date1,$date2);
	return $diff->format("%a");
}
function electricity_bill_date_diff_2($line,$file,$date_1,$date_2){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date_1,$date_2));
	$date1=date_create($date_1);
	$date2=date_create($date_2);
	$diff=date_diff($date1,$date2,false);
	return $diff->format("%r%a");
}

function electricity_bill_file_exist($line,$file,$filename){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($filename));
	if(file_exists($filename)) {
		return true;
	}else{
		return false;	
	}
}
function electricity_bill_mppdf($line,$file,$discom_id,$filename){
require_once '/home/rjiobillpro/public_html/vendor/autoload.php';
	$mpdf = new \Mpdf\Mpdf(['format' =>[297, 420]]);
	$temp_file_name = electricity_bill_string_replace(__LINE__,__FILE__,'.html','.pdf',$filename);
	// Set some flags for mPDF
	$mpdf->autoScriptToLang = true;
	$mpdf->autoLangToFont = true;
	$temp_arr = electricity_bill_explode(__LINE__,__FILE__,'/',$filename);
	// Read and Parse HTML Content
	$mpdf->WriteHTML(file_get_contents($filename));
	// Display PDF to Browser
	$mpdf->Output($temp_file_name,"F");
	return $temp_file_name;
}
function electricity_bill_html_to_pdf($line,$file,$discom_id,$filename){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($discom_id,$filename));
	include_once 'dompdf/autoload.inc.php';
	$temp_arr = electricity_bill_explode(__LINE__,__FILE__,'/',$filename);
	$dompdf = new Dompdf\Dompdf();
	$paper_size = "A4";
	$get_html = file_get_contents($filename);
	if( $discom_id == '8'){
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<script type="text/javascript" src="./URJA MITRA APPLICATION _ JHARKHAND BIJLI VITRAN NIGAM LTD._files/ajax.js.download"></script>','',$get_html);	
		
		$between_string = get_string_between(__LINE__,__FILE__,$get_html,'<script','/script>');
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<script'.$between_string.'/script>','',$get_html);				
		
		$get_logo_class_occurance = electricity_get_total_occurance_of_substring(__LINE__,__FILE__,'class="logo"',$get_html);
		for($index=0;$index<$get_logo_class_occurance;$index++){
			$between_string = get_string_between(__LINE__,__FILE__,$get_html,'<div class="logo"','/div>');
			$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<div class="logo"'.$between_string.'/div>','',$get_html);	
		}
		$between_string = get_string_between(__LINE__,__FILE__,$get_html,'<style','/style>');
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<style'.$between_string.'/style>','',$get_html);	
		
		$between_string = get_string_between(__LINE__,__FILE__,$get_html,'<script language="javascript" type="text/','/script>');
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<script language="javascript" type="text/'.$between_string.'/script>','',$get_html);
			
	}else if($discom_id == '6'){
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'http://cpmuz.esselutilities.com:86/images/Essel.png','images/Essel.png',$get_html);
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'http://cpmuz.esselutilities.com:86/images/sulogo.png','images/sulogo.png',$get_html);
		//$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'</head>','images/sulogo.png',$get_html);
		
	}else if($discom_id == '9'){
		$get_logo_class_occurance = electricity_get_total_occurance_of_substring(__LINE__,__FILE__,'rowspan="7"',$get_html);
		for($index=0;$index<$get_logo_class_occurance;$index++){
			$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'rowspan="7"','rowspan="6"',$get_html);	
		}
		
		$get_logo_class_occurance = electricity_get_total_occurance_of_substring(__LINE__,__FILE__,'<table border="1" cellpadding="0" cellspacing="0" width="100%" style="height: 45px">',$get_html);
		for($index=0;$index<$get_logo_class_occurance;$index++){
			$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<table border="1" cellpadding="0" cellspacing="0" width="100%" style="height: 45px">','<table border="1" cellpadding="2" cellspacing="2" width="100%" style="height: 45px">',$get_html);	
		}
	}else if($discom_id == '12'){
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'onKeyDown=\'return DisableControlKey(event)\' onMouseDown=\'return DisableControlKey(event)\'     onKeyPress="return disableCtrlKeyCombination(event);"onKeyDown="return disableCtrlKeyCombination(event); "onload=\'Onload_All(this.frm)\' bgColor=#ffffff style="background-image:url(/wss/images/main_bg.jpg); margin-top:0; padding-top:20px;margin-left:0; text-align:center"','',$get_html);			
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'विद्युत बिल एवं आपूर्ति शिकायत हेतु टोल फ्री 1912 डायल करें।','',$get_html);			
		$between_string = get_string_between(__LINE__,__FILE__,$get_html,'<table width="940" align="center" cellpadding="0" cellspacing="0" style="position:relative;left:-10px;"','/table>');
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<table width="940" align="center" cellpadding="0" cellspacing="0" style="position:relative;left:-10px;"'.$between_string.'/table>','',$get_html);	
		$paper_size = "A3";
	}else if($discom_id == '10' || $discom_id == '14' || $discom_id == '46' || $discom_id == '48' ){
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'onKeyDown=\'return DisableControlKey(event)\' onMouseDown=\'return DisableControlKey(event)\'     onKeyPress="return disableCtrlKeyCombination(event);"onKeyDown="return disableCtrlKeyCombination(event); "onload=\'Onload_All(this.frm)\' bgColor=#ffffff style="background-image:url(/wss/images/main_bg.jpg); margin-top:0; padding-top:20px;margin-left:0; text-align:center"','',$get_html);			
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'विद्युत बिल एवं आपूर्ति शिकायत हेतु टोल फ्री 1912 डायल करें।','',$get_html);			
		$between_string = get_string_between(__LINE__,__FILE__,$get_html,'<table width="940" align="center" cellpadding="0" cellspacing="0" style="position:relative;left:-10px;"','/table>');
		$get_html = electricity_bill_string_replace(__LINE__,__FILE__,'<table width="940" align="center" cellpadding="0" cellspacing="0" style="position:relative;left:-10px;"'.$between_string.'/table>','',$get_html);	
		$paper_size = "A2";
	}
	
	$dompdf->load_html($get_html);
	if($discom_id == '10' || $discom_id == '14'){
		$dompdf->setPaper($paper_size, 'potrait');
	}else{
		$dompdf->setPaper($paper_size, 'landscape');
	}
	$dompdf->set_option('defaultFont', 'Courier');
	$options = new  \Dompdf\Options();
	$options->setIsRemoteEnabled(true);
	$dompdf->setOptions($options);
	$context = stream_context_create([ 
		'ssl' => [ 
			'verify_peer' => FALSE, 
			'verify_peer_name' => FALSE,
			'allow_self_signed'=> TRUE 
		] 
	]);
	$temp_file_name = electricity_bill_string_replace(__LINE__,__FILE__,'.html','.pdf',$filename);
	$file_full_path ='html_to_pdf/'.$temp_arr[1].'/'.$temp_file_name; 
	$dompdf->setHttpContext($context);
	$dompdf->render();
	$output = $dompdf->output();
	file_put_contents($temp_file_name, $output);
	return $temp_file_name;
}
function electricity_bill_database_to_bill_check_format($line,$file,$date)
{
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($date));
	return date("d/m", strtotime($date));
}
function gzCompressFile($line,$file,$source, $level = 9){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($source, $level)); 
	$dest = $source . '.gz'; 
	$mode = 'wb' . $level; 
	$error = false; 
	if ($fp_out = gzopen($dest, $mode)) { 
		if ($fp_in = fopen($source,'rb')) { 
			while (!feof($fp_in)) 
				gzwrite($fp_out, fread($fp_in, 1024 * 512)); 
			fclose($fp_in); 
		} else {
			$error = true; 
		}
		gzclose($fp_out); 
	} else {
		$error = true; 
	}
	if ($error)
		return false; 
	else
	   return $dest; 
}
	function reposition_negative_sign($str) {
		if(strpos($str,'-') !== FALSE) {
			$str = electricity_bill_string_replace(__LINE__,__FILE__,'-','',$str);
			$str = -1 * $str;
		}
		return $str;
	}
function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}
function electricity_bill_random_strings($length_of_string) 
	{ 
		// String of all alphanumeric character 
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
	
		// Shufle the $str_result and returns substring 
		// of specified length 
		return substr(str_shuffle($str_result),  
						0, $length_of_string); 
	} 

	if (!function_exists('getallheaders')) {
		function getallheaders() {
		$headers = [];
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', strtolower(str_replace('_', '-', substr($name, 5))))] = $value;
			}
		}
		return $headers;
		}
	}
	
function electricity_bill_previous_bill_info($line,$file,$fields, $current_bill_added_date, $site_id, $discom_id){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($fields, $current_bill_added_date, $site_id, $discom_id));
	$table_field = join(", ", $fields);
	$previous_date_bill_query  = "SELECT $table_field FROM ebill_overview WHERE DATE(fld_datetime) < DATE('".$current_bill_added_date."') AND fld_discom_id = '".$discom_id."' AND fld_organizationsite_id = '$site_id' ORDER BY fld_datetime DESC LIMIT 0,1";
	$previous_date_bill_query_result = electricity_bill_query(__LINE__,__FILE__,$previous_date_bill_query);	
	if(electricity_bill_num_rows(__LINE__,__FILE__,$previous_date_bill_query_result) > 0){
		$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$previous_date_bill_query_result); 
		return $row;
	}
	return false; 
}

function electricity_bill_is_data_exist($line,$file,$field, $data, $current_bill_added_date, $site_id, $discom_id){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($field, $data, $current_bill_added_date, $site_id, $discom_id));
	$is_data_exist_query  = "SELECT COUNT(fld_ai_id) AS total_count FROM ebill_overview WHERE DATE(fld_datetime) < DATE('".$current_bill_added_date."') AND fld_discom_id = '".$discom_id."' AND fld_organizationsite_id = '$site_id' AND $field = '$data'";
	$is_data_exist_query_result = electricity_bill_query(__LINE__,__FILE__,$is_data_exist_query);	
	if(electricity_bill_num_rows(__LINE__,__FILE__,$is_data_exist_query_result) > 0){
		$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$is_data_exist_query_result); 
		return $row['total_count'];
	}
	return 0; 
}

function electricity_bill_is_bill_no_exist($line,$file,$field, $data, $current_bill_added_date, $site_id, $circle_id){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($field, $data, $current_bill_added_date, $site_id, $circle_id));
	$is_data_exist_query  = "SELECT COUNT(fld_ai_id) AS total_count FROM ebill_overview WHERE fld_circle_id = '".$circle_id."' AND $field = '$data'";
	$is_data_exist_query_result = electricity_bill_query(__LINE__,__FILE__,$is_data_exist_query);	
	if(electricity_bill_num_rows(__LINE__,__FILE__,$is_data_exist_query_result) > 0){
		$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$is_data_exist_query_result); 
		return $row['total_count'];
	}
	return 0; 
}

function electricity_bill_is_bill_no_exist_global($line,$file,$field, $data, $current_bill_added_date, $site_id, $circle_id){
	electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($field, $data, $current_bill_added_date, $site_id, $circle_id));
	$is_data_exist_query  = "SELECT COUNT(fld_ai_id) AS total_count FROM ebill_overview WHERE $field = '$data'";
	$is_data_exist_query_result = electricity_bill_query(__LINE__,__FILE__,$is_data_exist_query);	
	if(electricity_bill_num_rows(__LINE__,__FILE__,$is_data_exist_query_result) > 0){
		$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$is_data_exist_query_result); 
		return $row['total_count'];
	}
	return 0; 
}

function electricity_bill_is_bill_no_exist_api($line,$file,$bill_no, $bill_date){
	$financial_data = get_financial_date_range($bill_date);

	$is_data_exist_query  = "SELECT COUNT(fld_ai_id) AS total_count FROM ebill_overview WHERE fld_invoice_no BETWEEN '" . $financial_data['start_financial'] . "' AND '" . $financial_data['end_financial'] . "' AND fld_discombill_no = '$bill_no'";
	$is_data_exist_query_result = electricity_bill_query(__LINE__,__FILE__,$is_data_exist_query);	
	if(electricity_bill_num_rows(__LINE__,__FILE__,$is_data_exist_query_result) > 0){
		$row = electricity_bill_fetch_assoc(__LINE__,__FILE__,$is_data_exist_query_result); 
		return $row['total_count'];
	}
	return 0; 
}

function electricity_bill_generate_new_bill_no_API($line,$file,$site_id,$consumer_id,$bill_no, $fld_process_date, $bill_no_exist){
	if ("" == $bill_no || null == $bill_no || strlen($bill_no)<=2) {
		if($fld_process_date != "" && $fld_process_date != "0000-00-00") {
			$consumer_first_4_digit = substr($consumer_id, 0, 4);
			$consumer_last_4_digit = substr($consumer_id, -4);
			$ddmmyy = date("dmy", strtotime($fld_process_date));
			$bill_no = $consumer_first_4_digit . "-" . $consumer_last_4_digit . "/" . $ddmmyy;
			$bill_no = str_replace(' ', '-', $bill_no); // Replaces all spaces with hyphens.
			$bill_no = preg_replace('/[^A-Za-z0-9\-\/\_]/', '', $bill_no); // Removes special chars.
		}
	}else{
		if (electricity_bill_string_length(__LINE__,__FILE__,$bill_no) > 16 && !$bill_no_exist) {
			$total_last_char = 16; 
			$start_index = strlen($bill_no) - $total_last_char; 
			$bill_no = substr($bill_no, $start_index); 
		}else{
			$total_last_char_sap = 4; 
			$start_index_sap = strlen($site_id) - $total_last_char_sap; 
			$last_4_site_id = substr($site_id, $start_index_sap); 

			$site_arr = electricity_bill_explode(__LINE__,__FILE__,'-', $site_id);
			$sap_city = electricity_bill_trim(__LINE__,__FILE__,$site_arr[2]);

			$financial_day = get_financial_day($fld_process_date); // fld_bills_added_to_billpro
			$bill_no = $bill_no . $sap_city . $last_4_site_id . $financial_day;	
		}
	}

	// If bill no more than 16 char then take last 16 char
	if (electricity_bill_string_length(__LINE__,__FILE__,$bill_no) > 16) {
		$total_last_char = 16; 
		$start_index = strlen($bill_no) - $total_last_char; 
		$bill_no = substr($bill_no, $start_index); 
	}

	// CHeck new generated bill number still exist or not
	/* $financial_data = get_financial_date_range($fld_process_date);
    $query = "SELECT `fld_invoice_no` FROM  `tbl_bills` WHERE (fld_datetime BETWEEN '" . $financial_data['start_financial'] . "' AND '" . $financial_data['end_financial'] . "') AND (`fld_invoice_no` = '".$bill_no."' || fld_discombill_no = '$bill_no')";
	$result = electricity_bill_query(__LINE__,__FILE__,$query);
	if(electricity_bill_num_rows(__LINE__,__FILE__,$result) > 0){
		$total_last_char_sap = 4; 
		$start_index_sap = strlen($site_id) - $total_last_char_sap; 
		$last_4_site_id = substr($site_id, $start_index_sap); 

		$site_arr = electricity_bill_explode(__LINE__,__FILE__,'-', $site_id);
		$sap_city = electricity_bill_trim(__LINE__,__FILE__,$site_arr[2]);

		$financial_day = get_financial_day($fld_process_date); // fld_bills_added_to_billpro
		$bill_no = $bill_no . $sap_city . $last_4_site_id . $financial_day;	

		// If bill no more than 16 char then take last 16 char
		if (electricity_bill_string_length(__LINE__,__FILE__,$bill_no) > 16) {
			$total_last_char = 16; 
			$start_index = strlen($bill_no) - $total_last_char; 
			$bill_no = substr($bill_no, $start_index); 
		}
	} */


	$query = "INSERT INTO `tbl_bill_invoice` (`fld_bill_invoice_no`) VALUE ('".$bill_no."');";
	$result = electricity_bill_query(__LINE__,__FILE__,$query);
	if( !(electricity_bill_affected_rows(__LINE__,__FILE__) == 1) ){
		$total_last_char_sap = 4; 
		$start_index_sap = strlen($site_id) - $total_last_char_sap; 
		$last_4_site_id = substr($site_id, $start_index_sap); 

		$site_arr = electricity_bill_explode(__LINE__,__FILE__,'-', $site_id);
		$sap_city = electricity_bill_trim(__LINE__,__FILE__,$site_arr[2]);

		$financial_day = get_financial_day($fld_process_date); // fld_bills_added_to_billpro
		$bill_no = $bill_no . $sap_city . $last_4_site_id . $financial_day;	

		// If bill no more than 16 char then take last 16 char
		if (electricity_bill_string_length(__LINE__,__FILE__,$bill_no) > 16) {
			$total_last_char = 16; 
			$start_index = strlen($bill_no) - $total_last_char; 
			$bill_no = substr($bill_no, $start_index); 
		}
	}


	return $bill_no;
}

function get_financial_day($bill_date) {
	$dateinput = strtotime($bill_date);
	$dayOfCalenderYear = date('z', $dateinput);
	//print_r($dayOfCalenderYear);
	if ( date('m',$dateinput) >= 4 ) {
		$year = date('Y',$dateinput) + 1;
		$dayOfFiscalYear = ($dayOfCalenderYear - 90) +1;
	}
	else {
		$year = date('Y',$dateinput);
		$dayOfFiscalYear = ($dayOfCalenderYear - 90 + 365) + 1;
		if(date('L', $year) == '1'){
			if( ((date('m',$dateinput) == 2) && (date('d',$dateinput) == 29)) || (date('m',$dateinput) == 3)){
				$dayOfFiscalYear = ($dayOfCalenderYear - 90 + 365) + 1 + 1;
				}
		}
		
	}
	return $dayOfFiscalYear;
}

function get_financial_date_range($bill_date) {
	$dateinput = strtotime($bill_date);
	if ( date('m',$dateinput) < 4 ) {
		$start_year = date('Y',$dateinput) - 1;
		$end_year = date('Y',$dateinput);
	} else {
		$start_year = date('Y',$dateinput);
		$end_year = date('Y',$dateinput) + 1;
		
	}

	return array(
		'start_financial' => $start_year . '-04-01',
		'end_financial' => $end_year . '-03-31'
	);
}

function rjio_api_mail($to, $subject, $body) {

		$message = "
		<html>
		<head>
		<title>Rjio API Exception Email</title>
		<style>
		#customers {
		font-family: Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		#customers td, #customers th {
		border: 1px solid #ddd;
		padding: 8px;
		}

		#customers th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #4CAF50;
		color: white;
		}
		</style>
		</head>
		<body>
		$body
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$from = "Rjio BillPro (API) Exception <noreply@rjio.billpro.online>";
		$headers = "Reply-To: ".$from."\r\n";
		$headers .= "Return-Path: ".$from."\r\n"; 
		$headers .= "From: ".$from."\r\n"; 
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\nX-Priority: 3\r\nX-Mailer: PHP". phpversion() ."\r\n";

		mail($to,$subject,$message,$headers);
	}	
function electricity_bill_similar_text($line,$file,$string1,$string2,$perc){electricity_bill_debug($line,$file,__FUNCTION__,electricity_bill_json_converter($string1,$string2,$perc));similar_text($string1,$string2,$perc); return $perc;}

function electricity_bill_remove_special($main_txt) {
  $test_txt = $main_txt;
  $matching_cond = '/[^a-zA-Z0-9 ]/i';
  return preg_replace($matching_cond, '', $test_txt);
}

function electricity_bill_remove_special_linefeed_chars($main_txt) {
  return str_ireplace(array("\r","\n",'\r','\n', '\r\n', "\r\n", "\t", '\t'),'', $main_txt);
}

function electricity_bill_remove_billno_special($main_txt) {
  $test_txt = $main_txt;
  $matching_cond = '/[^A-Za-z0-9\-\/\\_]/i';
  return preg_replace($matching_cond, '', $test_txt);
}

function comma_dot_replace($value){
	if(electricity_bill_find_position(__LINE__,__FILE__,substr($value,-3),",") !== FALSE){
		$temp_value = electricity_bill_string_replace(__LINE__,__FILE__,'.','',$value);
		$main_value = electricity_bill_string_replace(__LINE__,__FILE__,',','.',$temp_value);
	}else{
		$main_value = electricity_bill_string_replace(__LINE__,__FILE__,',','',$value);
	}
	return $main_value;
}

function round_float_number($val) {
    return (float)number_format((float)$val, 2, '.', '');
}

function iem_set_date($date1) {
	if (!empty($date1)) {
		if (!preg_match('/\d{4}-\d{2}-\d{2}/i', $date1) ) {
			$date1 = date('Y-m-d', strtotime($date1));
		}
	}
	return $date1;
}

?>
