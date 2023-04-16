<?php

if(!class_exists('ACFE_Paypal')):

class ACFE_Paypal{
    
    // https://developer.paypal.com/docs/archive/express-checkout/ec-initiate-payment/#request
    // https://developer.paypal.com/docs/nvp-soap-api/set-express-checkout-soap/
    // https://developer.paypal.com/developer/accounts/
    // https://developer.paypal.com/docs/nvp-soap-api/errors/
    // https://developer.paypal.com/docs/nvp-soap-api/endpoints/
    // https://developer.paypal.com/docs/archive/express-checkout/ec-initiate-payment/#redirect-the-buyer-to-paypal
    public function request($url, $args){
        
        // params
        $params = array();
    
        foreach($args as $key => $value){
            $value = urlencode($value);
            $params[] = "$key=$value";
        }
        
        // compile
        $compiled_data = implode('&', $params);
        
        // send
        $result = $this->send_request($url, $compiled_data);
        $result = $this->convert_result($result);
        
        // return
        return $result;
        
    }
    
    public function confirm($url, $args){
        
        // vars
        $username = $args['username'];
        $password = $args['password'];
        $signature = $args['signature'];
        $token = $args['token'];
        $payer_id = $args['payer_id'];
        
        // checkout args
        $checkout_args = array(
            'METHOD'    => 'GetExpressCheckoutDetails',
            'TOKEN'     => $token,
            'USER'      => $username,
            'PWD'       => $password,
            'SIGNATURE' => $signature,
            'VERSION'   => 121,
        );
        
        // get checkout details
        $checkout = $this->request($url, $checkout_args);
        
        // confirm args
        $confirm_args = array(
            'METHOD'                            => 'DoExpressCheckoutPayment',
            'USER'                              => $username,
            'PWD'                               => $password,
            'SIGNATURE'                         => $signature,
            'TOKEN'                             => $token,
            'PAYERID'                           => $payer_id,
            'PAYMENTREQUEST_0_AMT'              => number_format($checkout['AMT'], 2, '.', ''),
            'PAYMENTREQUEST_0_CURRENCYCODE'     => $checkout['CURRENCYCODE'],
            'VERSION'                           => 121,
            'PAYMENTREQUEST_0_PAYMENTACTION'    => 'Sale',
        );
    
        // get confirm
        return $this->request($url, $confirm_args);
        
    }
    
    function send_request($url, $data){
        
        // count
        $num_params = substr_count($data, '&') + 1;
        
        // init
        $ch = curl_init();
        
        // params
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,    2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
        curl_setopt($ch, CURLOPT_URL,               $url);
        curl_setopt($ch, CURLOPT_POST,              $num_params);
        curl_setopt($ch, CURLOPT_POSTFIELDS,        $data);
        
        //execute post
        $response = curl_exec($ch);
        
        //close connection
        curl_close($ch);
        
        return $response;
        
    }
    
    function convert_result($result){
        
        $initial = 0;
        $result_array = array();
        
        while(strlen($result)){
            
            // postion of Key
            $keypos = strpos($result, '=');
            
            // position of value
            $valuepos = strpos($result, '&') ? strpos( $result, '&' ) : strlen($result);
            
            // getting the Key and Value values and storing in a Associative Array
            $keyval = substr($result, $initial, $keypos);
            $valval = substr($result, $keypos + 1, $valuepos - $keypos - 1);
            
            // decoding the respose
            $result_array[ urldecode( $keyval ) ] = urldecode($valval);
            $result = substr($result, $valuepos + 1, strlen($result));
            
        }
        
        return $result_array;
        
    }
    
}

endif;