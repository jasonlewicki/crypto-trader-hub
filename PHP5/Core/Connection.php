<?php

namespace CryptoTraderHub\Core;

class Connection {
	
    public static function request($url, $method, $data, $options = Array()){

		// Don't time out;
    	set_time_limit(0);
    	
		// Attempt to contact server (3 times max);
		$tries  =   0;
		do {			
			// Initialize CURL request
			$curl_handle = curl_init();		
			
			// Form request by request method
			switch ($request_method) {
				case 'GET' :
					curl_setopt($curl_handle, CURLOPT_URL,$url.'?'. http_build_query($request_data));
					break;
				case 'POST' :
				case 'PUT' :
				case 'DELETE' :
				case 'HEAD' :
				case 'OPTIONS' :
					curl_setopt($curl_handle, CURLOPT_URL,$url);
					curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $request_method);
                    $postfields = $request_data;
                    if ($request_method == 'PUT') {
                        $postfields = http_build_query($postfields);
                    }
					curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postfields);
					break;
			}	
			
			// cURL options
			foreach($options as $key => $value){
				curl_setopt($curl_handle, constant($key), $value);
			}
						
			// Send Request
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);    
		    
		    // Get Result
		    $result = json_decode(curl_exec($curl_handle), true);    
		    
		    // Get Result Status
		    $http_status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);		
				
			// Debug
			//error_log(json_encode($result),0);
			//error_log($this -> host.$request_uri."?".http_build_query($request_data),0);	
						
			// Check if there was an error  
		    if ($http_status >= 400){
		    	error_log("CONNECTION ERROR HTTP STATUS: ".$http_status, 0);
				error_log("CONNECTION ERROR URL: ".$this -> host.$request_uri, 0);
			}
			
    		$tries++;
			
		} while((curl_errno($curl_handle) == 28) && $tries < 3);

		// Close CURL Handle
		curl_close($curl_handle);		
		
		return $result;

    }
	
}