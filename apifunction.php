<?php
@session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$BOOKING_URL = "https://cdn-api.co-vin.in/api/v2/appointment/schedule";
$BENEFICIARIES_URL = "https://cdn-api.co-vin.in/api/v2/appointment/beneficiaries";
$CALENDAR_URL_DISTRICT = "https://cdn-api.co-vin.in/api/v2/appointment/sessions/calendarByDistrict?district_id={0}&date={1}";
$CALENDAR_URL_PINCODE = "https://cdn-api.co-vin.in/api/v2/appointment/sessions/calendarByPin?pincode={0}&date={1}";
$CAPTCHA_URL = "https://cdn-api.co-vin.in/api/v2/auth/getRecaptcha";
$OTP_PUBLIC_URL = 'https://cdn-api.co-vin.in/api/v2/auth/public/generateOTP';
$OTP_PRO_URL = 'https://cdn-api.co-vin.in/api/v2/auth/generateMobileOTP';
$VALIDATE_OTP = 'https://cdn-api.co-vin.in/api/v2/auth/validateMobileOtp';

if($_GET['function'] == 'generateMobileOTP'){

    $_SESSION['mobile'] = $_POST['mobile'];
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/auth/generateMobileOTP',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  // 
	  CURLOPT_POSTFIELDS =>'{"mobile": "'.$_POST['mobile'].'","secret": "U2FsdGVkX1+z/4Nr9nta+2DrVJSv7KS6VoQUSQ1ZXYDx/CJUkWxFYG6P3iM/VW+6jLQ9RDQVzp/RcZ8kbT41xw=="}',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json',
	    'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
	  ),
	));

	$response = curl_exec($curl);
	curl_close($curl);
    $_SESSION['txnId'] = json_decode($response)->txnId;
	echo ($response);


}

if($_GET['function'] == 'validateMobileOtp'){
// sha256(str(OTP).encode('utf-8')).hexdigest()


	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/auth/validateMobileOtp',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{"otp": "'.hash('sha256', $_POST['otp']).'", "txnId": "'.$_POST['txnId'].'"}',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json',
	    'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	$_SESSION['token'] = json_decode($response)->token;

	$data = json_decode($response);
	try {
		if($data->errorCode != '' || $data->error != ''){
			echo 'wrong otp!';
			die();
		}
	} catch (\Exception $e) {
		
	}


	echo $response;

}

if($_GET['function'] == 'checkPreviousData'){
	if(file_exists("json/".$_POST['mobile']."-data.json")){
		//load previous data in session
		$data = json_decode(file("json/".$_POST['mobile']."-data.json")[0]);
        $_SESSION['beneficiary_dtls'] = $data->beneficiary_dtls;
        $_SESSION['location_dtls'] = $data->location_dtls;
        $_SESSION['search_option'] = $data->search_option;
        $_SESSION['minimum_slots'] = $data->minimum_slots;
        $_SESSION['refresh_freq'] = $data->refresh_freq;
        $_SESSION['auto_book'] = $data->auto_book;
        $_SESSION['start_date'] = $data->start_date;
        $_SESSION['vaccine_type'] = $data->vaccine_type;
        $_SESSION['fee_type'] = $data->fee_type;

	    echo file("json/".$_POST['mobile']."-data.json")[0];
	}else{
	    echo 'not-found';
	}
}

if($_GET['function'] == 'saveData'){
	if(isset($_POST['mobile']) && $_POST['mobile'] != null){
		$myfile = fopen("json/".$_POST['mobile']."-data.json", "w") or die("Unable to open file!");
        fwrite($myfile, $_POST['collected_details']);
        fclose($myfile);

        $data = json_decode($_POST['collected_details']);
        $_SESSION['beneficiary_dtls'] = $data->beneficiary_dtls;
        $_SESSION['location_dtls'] = $data->location_dtls;
        $_SESSION['search_option'] = $data->search_option;
        $_SESSION['minimum_slots'] = $data->minimum_slots;
        $_SESSION['refresh_freq'] = $data->refresh_freq;
        $_SESSION['auto_book'] = $data->auto_book;
        $_SESSION['start_date'] = $data->start_date;
        $_SESSION['vaccine_type'] = $data->vaccine_type;
        $_SESSION['fee_type'] = $data->fee_type;

		echo 'saved';
	}else{
		echo 'error';
	}
}


if($_GET['function'] == 'getBeneficiaries'){

	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/appointment/beneficiaries',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json',
	    'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
	    'Authorization: Bearer '.$_POST['token']
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	echo $response;
}

if($_GET['function'] == 'getStateList'){
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/admin/location/states',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json',
	    'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	echo $response;
}

if($_GET['function'] == 'getDistrictList'){
	if(isset($_POST['state_id']) && $_POST['state_id'] != null){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/admin/location/districts/'.$_POST['state_id'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json',
		    'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
}

if($_GET['function'] == 'getCaptcha'){
	if(isset($_POST['token']) && $_POST['token'] != null){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/auth/getRecaptcha',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_HTTPHEADER => array(
		    'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
		    'Authorization: Bearer '.$_POST['token']
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		echo $response;
	}
}

if($_GET['function'] == 'scheduleAppointment'){
	if(isset($_POST['details']) && $_POST['details'] != '' && isset($_POST['token']) && $_POST['token'] != '' ){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/appointment/schedule',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_HEADER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $_POST['details'],
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json',
		    'User-Agent : Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
		    'Authorization: Bearer '.$_POST['token']
		  ),
		));
		
		$response = curl_exec($curl);
		
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
		curl_close($curl);
        if(strpos($body, 'errorCode') !== false || strpos($body, 'error') !== false){
            $data  = json_decode($body);
            $data->status_code = $httpcode;
            echo json_encode($data);
        }else{
            $data = 'invald-input';
            echo $data;
        }
        
	}else{
		echo 'invald-input';
	}
}

?>