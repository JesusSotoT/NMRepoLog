<?php

    //ini_set('display_errors', '1');

	class PhpSrPago
	{

		const URL_API = "https://api.srpago.com";
		const URL_API_SANDBOX = "https://sandbox-api.srpago.com";
		const APP_NAME = ""; //TODO: Set project name in production context
		const APP_NAME_SANDBOX = "uplanersand"; //TODO: Set project name in development context
		const APP_ID = "be4e994c-344d-416a-aa4d-ac3414ad1500"; //TODO: Set project id in production context
		const APP_ID_SANDBOX = "94ef1fd4-2093-4ba6-8192-7e2b44bf5f78"; //TODO: Set project id in development context
		const APP_KEY = "Lw5?(8Nz!?r2"; //TODO: Set project key in production context
		const APP_KEY_SANDBOX = "MO66UwPOUefx"; //TODO: Set project key in development context

		const API_PAYMENT_METHOD = "/v1/payment/card"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_AUTH_METHOD = "/v1/auth/login/application"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_GET_CUSTOMER_LIST_METHOD = "/v1/customer"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_CREATE_CUSTOMER_METHOD = "/v1/customer"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_CREATE_CARD_TOKEN_METHOD = "/v1/token"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_ADD_CUSTOMER_CARD_METHOD = "/v1/customer/<<user>>/cards"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_DELETE_CUSTOMER_CARD_METHOD = "/v1/customer/<<user>>/cards/<<card>>"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_GET_CUSTOMER_CARDS_METHOD = "/v1/customer/<<user>>/cards"; //NOTE: Change this constant if the method changes at Sr. Pago's official documentation
        const API_PAYMENT_AUTHORIZED_AMOUNT = "50000.00"; //NOTE: Set the maximum authorized amount for credit cards

        private $api_token;
        private $data_transaction;
        private $metadata_transaction;
        private $currency;
        private $ip;
        private $latitude;
        private $longitude;
        private $sandbox;
        private $reference;
        private $paymentResponse;
        private $log;

        /**
            Class constructor

            @param latitude     Geographic latitude where the payment request is
            @param longitude    Geographic longitude where the payment request is
            @param currency     Currency to be applied for the payment, default is mexican pesos
            @param sandbox      Flag that indicates if the payment will be in sandbox or production, default is sandbox mode
        */
        function __construct($latitude, $longitude, $currency = "MXN", $sandbox = true) {
            $this->sandbox = $sandbox;
            $this->log = ":::Inicio Log:::";
            $this->token = $this->srPagoAuth();
            $this->data_transaction = array();
            $this->metadata_transaction = array();
            $this->currency = $currency;
            $this->ip = self::getIp();
            $this->latitude = $latitude;
            $this->longitude = $longitude;
            $this->setGeolocation();
            $this->reference;
        }

        function __destruct() {

        }

        /**
            Change between sandbox and production mode

            @param status   Set true for activate sandbox mode or false to activate production mode
        */
        public function setSandBox($status) {
            $this->sandbox = $status;
            $this->setLog("Sandbox: ". (($status) ? "true" : "false"));
        }

        /**
            Get the type of bank that accepts Sr. Pago

            @param bank     Name of the bank that represents credit card's payment
            @return         Type of credit card that is accepted by Sr. Pago
        */
        public static function creditCardType($bank) {
            switch ($bank) {
                case 'visa':

                case 'visa_electron':
                    $type = "VISA";
                    break;
                case 'mastercard':
                   $type = "MAST";
                    break;
                case 'amex':
                    $type = "AMEX";
                    break;
                default:
                    $type = $bank;
                    break;
            }
            return $type;
        }

        /**
            Set all software reference to payment

            @param description  Short description that indicates what will be charged
            @param reference    Payment reference between system and Sr. Pago, default is a 10 random characters
        */
        public function setInternalTransactionReference($description, $reference = null) {
            if(is_null($reference)) $reference = strtoupper(substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 10)), 0, 10));
            $this->reference = $reference;
            $this->data_transaction["payment"]["external"]["transaction"] = $reference;
            $this->data_transaction["payment"]["external"]["application_key"] = ($this->sandbox) ? self::APP_ID_SANDBOX : self::APP_ID;
            $this->data_transaction["payment"]["reference"]["number"] = $reference;
            $this->data_transaction["payment"]["reference"]["description"] = $description;
            $this->setLog("Internal Transaction Reference: \n". json_encode($this->data_transaction["payment"]));
        }

        /**
            Set tip information to payment

            @param amount   Amount set for tips
        */
        public function setTip($amount) {
            $this->data_transaction["payment"]["tip"]["amount"] = $amount;
            $this->data_transaction["payment"]["tip"]["currency"] = $this->currency;
            $this->setLog("Tip: \n". json_encode($this->data_transaction["payment"]["tip"]));
        }

        /**
            Set geolocation payment
        */
        private function setGeolocation() {
            $this->data_transaction["payment"]["origin"]["ip"] = $this->ip;
            $this->data_transaction["payment"]["origin"]["location"]["latitude"] = $this->latitude;
            $this->data_transaction["payment"]["origin"]["location"]["longitude"] = $this->longitude;
            $this->setLog("Geolocation: \n". json_encode($this->data_transaction["payment"]["origin"]));
        }

        /**
            Set credit card information

            @param holder_name  Array that contains all holder information, its structure must be:
                                array("first_name" => , "middle_name" => , "last_name" => )
            @param bank         Type of card, see creditCardType method
            @param card         Array that contains all credit card information, its structure must be:
                                array("number" => , "expiration_date" => array("year" => , "month" => ), "cvv" => )
        */
        public function setCardInformation($holder_name, $bank, $card) {
            $this->data_transaction["card"]["holder_name"] = $holder_name["first_name"] . " " . $holder_name["middle_name"] . " " . $holder_name["last_name"];
            $this->data_transaction["card"]["type"] = $bank;
            $this->data_transaction["card"]["number"] = $card["number"];
            $this->data_transaction["card"]["raw"] = $card["number"];
            $this->data_transaction["card"]["expiration"] = $card["expiration_date"]["year"] . $card["expiration_date"]["month"];
            $this->data_transaction["card"]["cvv"] = $card["cvv"];
            $this->data_transaction["card"]["ip"] = $this->ip;
            $this->data_transaction["ecommerce"]["holderName"] = $holder_name["first_name"] . " " . $holder_name["middle_name"] . " " . $holder_name["last_name"];
            $this->data_transaction["ecommerce"]["type"] = $bank;
            $this->data_transaction["ecommerce"]["number"] = $card["number"];
            $this->data_transaction["ecommerce"]["raw"] = $card["number"];
            $this->data_transaction["ecommerce"]["expiration"] = $card["expiration_date"]["year"] . $card["expiration_date"]["month"];
            $this->data_transaction["ecommerce"]["cvv"] = $card["cvv"];
            $this->data_transaction["ecommerce"]["ip"] = $this->ip;
            $this->setLog("Card Information: \n ##########");
        }

        /**
            Set credit card token

            @param token         Token assigned to the card by SrPago
        */
        public function setCardToken($token) {
            $this->data_transaction["recurrent"] = $token;
            $this->setLog("Card Token: \n". json_encode($this->data_transaction["recurrent"]));
        }

        /**
            Set number of months in which payment is gonna separated

            @param months   Number of periods, only accepts 3, 6 or 12 months
        */
        public function setPaymentPeriods($months) {
            if($months != 3 && $months != 6 && $months != 12) throw new Exception("Sr. Pago only accepts 3, 6 or 12 months", 1);
            $this->data_transaction["months"] = $months;
            $this->setLog("Payment Periods: \n". json_encode($this->data_transaction["months"]));
        }

        /**
            Set items purchased details
            
            @param items    Array that contains all items details, its structure must be:
                            array([0] => array("id" => , "description" => , "price" => , "quantity" => , "unit" => , "brand" => , "category" => , "tax" => ))
        */
        public function setItems($items) {
            $items_transaction = array();
            foreach ($items as $item) {
                $product = array();
                $item["itemNumber"] = $item["id"];
                $item["itemDescription"] = $item["description"];
                $item["itemPrice"] = $item["price"];
                $item["itemQuantity"] = $item["quantity"];
                $item["itemMeasurementUnit"] = $item["unit"];
                $item["itemBrandName"] = $item["brand"];
                $item["itemCategory"] = $item["category"];
                $item["itemTax"] = $item["tax"];
                $items[] = $product;
            }
            $this->metadata_transaction["items"]["item"] = $items;
            $this->setLog("Items: \n". json_encode($this->metadata_transaction["items"]));
        }

        /**
            Set payment taxes

            @param taxes    Taxes amount at the payment
        */
        public function setTaxes($taxes = 0.0) {
            $taxes = (float) $taxes;
            $this->metadata_transaction["salesTax"] = "$taxes";
            $this->setLog("Taxes: \n". json_encode($this->metadata_transaction["salesTax"]));
        }

        /**
            Set sale promotion

            @param item     Array that contains the promotion information, its structure must be:
                            array("code" => , "amount" => )
        */
        public function setPromotion($item) {
            $promotion = array();
            $promotion["promotionCode"] = $item["code"];
            $promotion["promotionAmount"] = $item["amount"];
            $this->metadata_transaction["promotions"]["promotion"] = array();
            $this->metadata_transaction["promotions"]["promotion"][] = $promotion;
            $this->setLog("Promotion: \n". json_encode($this->metadata_transaction["promotions"]));
        }

        /**
            Set billing information

            @param billing  Array that contains all billing information, its structure must be:
                            array("email" => , "first_name" => , "middle_name" => , "last_name" => , "street" => , "district" => , "city" => , "state" => , "postal_code" => , "country" => , "phone" => )
        */
        public function setBillingInformation($billing) {
            $this->metadata_transaction["billing"] = array();
            $this->metadata_transaction["billing"]["billingEmailAddress"] = $billing["email"];
            $this->metadata_transaction["billing"]["billingFirstName-D"] = $billing["first_name"];
            $this->metadata_transaction["billing"]["billingMiddleName-D"] = $billing["middle_name"];
            $this->metadata_transaction["billing"]["billingLastName-D"] = $billing["last_name"];
            $this->metadata_transaction["billing"]["billingAddress-D"] = $billing["street"];
            $this->metadata_transaction["billing"]["billingAddress2-D"] = $billing["district"];
            $this->metadata_transaction["billing"]["billingCity-D"] = $billing["city"];
            $this->metadata_transaction["billing"]["billingState-D"] = $billing["state"];
            $this->metadata_transaction["billing"]["billingPostalCode-D"] = $billing["postal_code"];
            $this->metadata_transaction["billing"]["billingCountry-D"] = $billing["country"];
            $this->metadata_transaction["billing"]["billingPhoneNumber-D"] = $billing["phone"];
            $this->metadata_transaction["billing"]["creditCardAuthorizedAmount-D"] = self::API_PAYMENT_AUTHORIZED_AMOUNT;
            $this->setLog("Billing Information: \n". json_encode($this->metadata_transaction["billing"]));
        }

        /**
            Set system member information

            @param member   Array that contains all member information, its strcuture must be:
                            array("id" => , "first_name" => , "middle_name" => , "last_name" => , "email" => , "phone" => )
            @param ship     Array that contains all ship information, its structure must be:
                            array("date" => , "street" => , "district" => , "city" => , "state" => , "country" => , "postal_code" => , "level" => , "status" => )
                            Note:   If you don't know the Ship -> Level, set "1"
                                    If you don't know the Ship -> Status, set "active"
        */
        public function setMemberInformation($member, $ship) {
            $this->metadata_transaction["member"] = array();
            $this->metadata_transaction["member"]["memberLoggedIn"] = "Si";
            $this->metadata_transaction["member"]["memberId"] = $member["id"];
            $this->metadata_transaction["member"]["membershipDate"] = $ship["date"];
            $this->metadata_transaction["member"]["memberFullName"] = $member["first_name"] . " " . $member["middle_name"] . " " . $member["last_name"];
            $this->metadata_transaction["member"]["memberFirstName"] = $member["first_name"];
            $this->metadata_transaction["member"]["memberMiddleName"] = $member["middle_name"];
            $this->metadata_transaction["member"]["memberLastName"] = $member["last_name"];
            $this->metadata_transaction["member"]["memberEmailAddress"] = $member["email"];
            $this->metadata_transaction["member"]["memberAddressLine1"] = $ship["street"];
            $this->metadata_transaction["member"]["memberAddressLine2"] = $ship["district"];
            $this->metadata_transaction["member"]["memberCity"] = $ship["city"];
            $this->metadata_transaction["member"]["memberState"] = $ship["state"];
            $this->metadata_transaction["member"]["memberCountry"] = $ship["country"];
            $this->metadata_transaction["member"]["memberPostalCode"] = $ship["postal_code"];
            $this->metadata_transaction["member"]["membershipLevel"] = $ship["level"];
            $this->metadata_transaction["member"]["membershipStatus"] = $ship["status"];
            $this->metadata_transaction["member"]["latitude"] = $this->latitude;
            $this->metadata_transaction["member"]["longitude"] = $this->longitude;
            $this->metadata_transaction["member"]["memberPhone"] = $member["phone"];
            $this->setLog("Member Information: \n". json_encode($this->metadata_transaction["member"]));
        }

        /**
            Make the payment, at this moment you should have called all previous methods

            @param amount   Payment Amount
        */
        public function payment($amount) {
            $this->setLog("---Inicio Pago---");
            $this->data_transaction["payment"]["total"]["amount"] = $amount;
            $this->data_transaction["payment"]["total"]["currency"] = $this->currency;
            $this->data_transaction["total"]["amount"] = $amount;
            $encryption = $this->encryption(json_encode($this->data_transaction));
            $parameters = json_encode(array("key" => $encryption["key"], "data" => $encryption["data"], "metadata" => $this->metadata_transaction));

            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . self::API_PAYMENT_METHOD;
            $headers = $this->createHeaders(self::API_PAYMENT_METHOD, "Bearer " . $this->token);
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters);
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $this->paymentResponse = $requestCurl;
            $this->setLog("---Termina Pago---");
            return $requestCurl;
        }

        /**
            Get the customers list
        */
        public function getCustomerList() {
            $this->setLog("---Inicio Lista de Clientes---");
            $parameters = json_encode(array());

            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . self::API_GET_CUSTOMER_LIST_METHOD;
            $headers = $this->createHeaders(self::API_GET_CUSTOMER_LIST_METHOD, "Bearer " . $this->token);
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters, "GET");
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $this->setLog("---Termina Lista de Clientes---");
            return $requestCurl["result"]["customers"];
        }

        /**
            Create a new customer

            @param name   Customer name
            @param email   Customer email
        */
        public function createCustomer($name, $email) {
            $this->setLog("---Inicio Crear Cliente---");
            $parameters = json_encode(array("name" => $name, "email" => $email));

            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . self::API_CREATE_CUSTOMER_METHOD;
            $headers = $this->createHeaders(self::API_CREATE_CUSTOMER_METHOD, "Bearer " . $this->token);
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters);
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $this->setLog("---Termina Crear Cliente---");
            return $requestCurl["result"]["id"];
        }

        /**
            Create card token

            @param cardholder_name   Customer name
            @param card     Array that contains all credit card information, its structure must be:
                            array("number" => , "expiration_date" => array("year" => , "month" => ), "cvv" => )

        */
        public function createCardToken($cardholder_name, $card) {
            $this->setLog("---Inicio Crear Token Tarjeta---");
            $card["cardholder_name"] = $cardholder_name;
            $card["expiration"] = $card["expiration_date"]["year"].$card["expiration_date"]["month"];
            $encryption = $this->encryption(json_encode($card));
            $parameters = json_encode(array("key" => $encryption["key"], "data" => $encryption["data"]));

            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . self::API_CREATE_CARD_TOKEN_METHOD;
            $headers = $this->createHeaders(self::API_CREATE_CARD_TOKEN_METHOD, "Bearer " . $this->token);
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters);
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $this->setLog("---Termina Crear Token Tarjeta---");
            return $requestCurl["result"]["token"];
        }

        /**
            Add new card to customer
            
            @param user    Customer SrPago id
            @param token   Customer card token
        */
        public function addCard($user, $token) {
            $this->setLog("---Inicio Agregar Tarjeta---");
            $method = str_replace("<<user>>", $user, self::API_ADD_CUSTOMER_CARD_METHOD);
            $parameters = json_encode(array("token" => $token));

            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . $method;
            $headers = $this->createHeaders($method, "Basic " . base64_encode(($this->sandbox) ? self::APP_ID_SANDBOX .":". self::APP_KEY_SANDBOX : self::APP_ID .":". self::APP_KEY));
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters);
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $this->setLog("---Termina Agregar Tarjeta---");
            return $requestCurl["result"]["token"];
        }

        /**
            Delete customer card
            
            @param user    Customer SrPago id
            @param token   Customer card token
        */
        public function removeCard($user, $token) {
            $this->setLog("---Inicio Eliminar Tarjeta---");
            $method = str_replace("<<card>>", $token, str_replace("<<user>>", $user, self::API_DELETE_CUSTOMER_CARD_METHOD));
            $parameters = json_encode(array());

            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . $method;
            $headers = $this->createHeaders($method, "Basic " . base64_encode(($this->sandbox) ? self::APP_ID_SANDBOX .":". self::APP_KEY_SANDBOX : self::APP_ID .":". self::APP_KEY), "DELETE");
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters, "DELETE");
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $this->setLog("---Termina Eliminar Tarjeta---");
            return $requestCurl["result"]["token"];
        }

        /**
            Create card token

            @param cardholder_name   Customer name
            @param card     Array that contains all credit card information, its structure must be:
                            array("number" => , "expiration_date" => array("year" => , "month" => ), "cvv" => )

        */
        public function getCustomerCards($user) {
            $this->setLog("---Inicio Obtener Tarjetas---");
            $method = str_replace("<<user>>", $user, self::API_GET_CUSTOMER_CARDS_METHOD);
            $parameters = json_encode(array());

            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . $method;
            $headers = $this->createHeaders($method, "Bearer " . $this->token, "GET");
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters, "GET");
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $tarjetas = array();
            foreach ($requestCurl["result"] as $tarjeta) {
                $tarjetas[] = array("tipo" => $tarjeta["type"], "numero" => $tarjeta["number"], "crd" => $tarjeta["token"]);
            }
            $this->setLog("---Termina Obtener Tarjetas---");
            return $tarjetas;
        }

        public function getReference() {
            return $this->reference;
        }

        public function getPaymentResponse() {
            return $this->paymentResponse;
        }

        /**
            Get client IP

            @return     Client IPv4
        */
        public static function getIp() {
            $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
            foreach ($ip_keys as $key) {
                if (array_key_exists($key, $_SERVER) === true) {
                    foreach (explode(',', $_SERVER[$key]) as $ip) {
                        $ip = trim($ip);
                        if (self::validateIp($ip)) {
                            return $ip;
                        }
                    }
                }
            }
            return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
        }

        private static function validateIp($ip)
        {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return false;
            }
            return true;
        }

        private function createHeaders($method, $auth, $type = "POST") {
            $headers = array(
                $type ." ". $method ." HTTP/1.0", 
                "Content-Type: application/json",
                "Authorization: ". $auth
            );
            return $headers;
        }

        private function srPagoAuth() {
            $this->setLog("---Inicio Login SrPago---");
            $parameters = json_encode(array("application_bundle" => ($this->sandbox) ? self::APP_NAME_SANDBOX : self::APP_NAME));
            $url = (($this->sandbox) ? self::URL_API_SANDBOX : self::URL_API) . self::API_AUTH_METHOD;
            $headers = $this->createHeaders(self::API_AUTH_METHOD, "Basic " . base64_encode(($this->sandbox) ? self::APP_ID_SANDBOX .":". self::APP_KEY_SANDBOX : self::APP_ID .":". self::APP_KEY));
            $this->setLog("Url: ". $url);
            $this->setLog("Parameters: ". $parameters);
            $this->setLog("Headers: ". json_encode($headers));
            $requestCurl = $this->requestCurl($url, $headers, $parameters);
            $this->setLog("Response: ". $requestCurl);
            $requestCurl = $this->validateJSON($requestCurl);
            $this->setLog("---Termina Login SrPago---");
            return $requestCurl["connection"]["token"];
        }

        private function requestCurl($url, $headers, $parameters, $type = "POST") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            if($type == "POST"){
                curl_setopt($ch, CURLOPT_POST, 1); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
            $result = curl_exec($ch);
            if ($result === false) $result = curl_error($ch);
            curl_close($ch);
            return $result;
        }

        private function validateJSON($result) {
            $json = $this->isJSON($result);
            if($json == null || array_key_exists("error", $json)){
                $error = $json["error"]["message"];
                $code = 50;
                switch ($json["error"]["code"]) {
                    case 'CardAlreadyRegisteredException':
                        $error = "La tarjeta ya ha sido registrada previamente";
                        $code = -10;
                        break;
                }
                throw new Exception($error . "||" . json_encode($json), $code);
            }
            if(!$json["success"]) throw new Exception("Sorry, the request to Sr. Pago's server is broken", 4);
            return $json;
        }

        private function isJSON($json) {
            $json = json_decode($json, true);
            if(json_last_error() === JSON_ERROR_NONE) return $json;
            return null;
        }

        private static function encryption($json) {
            $random = '4cb893ff1ed88631bd8ad542ed69b6f6';
            $key = null;
            $ruta = dirname(__DIR__) . "/srpago/RSA-PUBLIC_KEY.txt";
            openssl_public_encrypt($random, $key, file_get_contents($ruta));
            $key = base64_encode($key);
            $data = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $random, $json, MCRYPT_MODE_ECB);
            $data = base64_encode($data);
            return array('key' => $key, 'data' => $data);   
        }

        private function setLog($log){
            $this->log .= "\n". $log;
        }

        public function getLog(){
            return $this->log;
        }

	}

?>