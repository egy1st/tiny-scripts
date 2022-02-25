<?php
/**
 * 
 * SubCancelAPIClient
 * 
 * A class which facilitates the interaction with AlertPay's 
 * SubscriptionCancellation API. SubCancelAPIClient class allows user to create 
 * the data to be sent to the API in the correct format and 
 * retrieve the response. 
 * 
 * 
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY
 * OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE IMPLIED WARRANTIES OF FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * @author AlertPay
 * @copyright 2010
 */

class SubCancelAPIClient
{
    /**
     * The API's response variables
     */
    private $responseArray;

    /**
     * The server address of the Subscription Cancellation API
     */
    private $server = 'api.alertpay.com';

    /**
     * The exact URL of the Subscription Cancellation API
     */
    private $url = '/svc/api.svc/CancelSubscription';

    /**
     * Your AlertPay user name which is your email address
     */
    private $myUserName = '';

    /**
     * Your API password that is generated from your AlertPay account
     */
    private $apiPassword = '';

    /**
     * The data that will be sent to the SubscriptionCancellation API
     */
    public $dataToSend = '';


    /**
     * SubCancelAPIClient::__construct()
     * 
     * Constructs a SubCancelAPIClient object
     * 
     * @param string $userName Your AlertPay user name.
     * @param string $password Your API password.
     */
    public function __construct($userName, $password)
    {
        $this->myUserName = $userName;
        $this->apiPassword = $password;
        $this->dataToSend = '';
    }


    /**
     * SubCancelAPIClient::setServer()
     * 
     * Sets the $server variable
     * 
     * @param string $newServer New web address of the server.
     */
    public function setServer($newServer = '')
    {
        $this->server = $newServer;
    }


    /**
     * SubCancelAPIClient::getServer()
     * 
     * Returns the server variable
     * 
     * @return string A variable containing the server's web address.
     */
    public function getServer()
    {
        return $this->server;
    }


    /**
     * SubCancelAPIClient::setUrl()
     * 
     * Sets the $url variable
     * 
     * @param string $newUrl New url address.
     */
    public function setUrl($newUrl = '')
    {
        $this->url = $newUrl;
    }


    /**
     * SubCancelAPIClient::getUrl()
     * 
     * Returns the url variable
     * 
     * @return string A variable containing a URL address.
     */
    public function getUrl()
    {
        return $this->url;
    }

	
    /**
     * SubCancelAPIClient::buildPostVariables()
     * 
     * Builds a URL encoded post string which contains the variables to be 
     * sent to the API in the correct format. 
     * 
     * @param int $subRefNum The reference number of the subscription to be cancelled.
     * @param int $testMode Test mode status.
     * 
     * @return string The URL encoded post string
     */
    public function buildPostVariables($subRefNum, $note = '', $testMode = '1')
    {
        $this->dataToSend = sprintf("USER=%s&PASSWORD=%s&SUBSCRIPTIONREFERENCE=%s&NOTE=%s&TESTMODE=%s",
            urlencode($this->myUserName), urlencode($this->apiPassword), urlencode((string) $subRefNum), urlencode((string)$note), urlencode((string) $testMode));
        return $this->dataToSend;
    }

	
    /**
     * SubCancelAPIClient::send()
     * 
     * Sends the URL encoded post string to the SubscriptionCancellation API
     * using cURL and retrieves the response.
     * 
     * @return string The response from the SubscriptionCancellation API.
     */
    public function send()
    {
        $response = '';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://' . $this->getServer() . $this->getUrl());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->dataToSend);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }


    /**
     * SubCancelAPIClient::parseResponse()
     * 
     * Parses the encoded response from the SubscriptionCancellation API
     * into an associative array.
     * 
     * @param string $input The string to be parsed by the function.
     */
    public function parseResponse($input)
    {
        parse_str(urldecode($input), $this->responseArray);
    }


    /**
     * SubCancelAPIClient::getResponse()
     * 
     * Returns the responseArray 
     * 
     * @return string An array containing the response variables.
     */
    public function getResponse()
    {
        return $this->responseArray;
    }


    /**
     * SubCancelAPIClient::__destruct()
     * 
     * Destructor of the SubCancelAPIClient object
     */
    public function __destruct()
    {
        unset($this->responseArray);
    }
}
