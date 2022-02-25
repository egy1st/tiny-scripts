<?php
require_once("MySQL.php");
require_once("encrypt.php");
require_once("global.php");

/**
 * 
 * Sample IPN Handler for Subscription Payments
 * 
 * The purpose of this code is to help you to understand how to process the Instant Payment Notification 
 * variables for a payment received through AlertPay's buttons and integrate it in your PHP site. The following
 * code will ONLY handle SUBSCRIPTION payments. For handling IPNs for ITEMS, please refer to the appropriate
 * sample code file.
 *	
 * Put this code into the page which you have specified as Alert URL.
 * The variables being read from the $_POST object in the code below are pre-defined IPN variables and the
 * the conditional blocks provide you the logical placeholders to process the IPN variables. It is your responsibility
 * to write appropriate code as per your requirements.
 *	
 * If you have any questions about this script or any suggestions, please visit us at: dev.alertpay.com
 * 
 *
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY
 * OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE IMPLIED WARRANTIES OF FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * @author AlertPay
 * @copyright 2010
 */
 
	//The value is the Security Code generated from the IPN section of your AlertPay account. Please change it to yours.
	define("IPN_SECURITY_CODE", "");
	define("MY_MERCHANT_EMAIL", "");

	//Setting information about the transaction from the IPN post variables
	
     $db= new MySQL($dbServer, $dbUser, $dbPass, $dbDatabaseName);
     $f = fopen("payment.txt", "w");

    $mySecurityCode = urldecode($_POST['ap_securitycode']);
	$receivedMerchantEmailAddress = urldecode($_POST['ap_merchant']);	
	//==================================================================
	 	
    $transactionReferenceNumber = urldecode($_POST['ap_referencenumber']);	
    $transactionStatus = urldecode($_POST['ap_status']);
	$testModeStatus = urldecode($_POST['ap_test']);
	$purchaseType = urldecode($_POST['ap_purchasetype']);
	$currency = urldecode($_POST['ap_currency']);	
	$totalAmountReceived = urldecode($_POST['ap_totalamount']);
    $feeAmount = urldecode($_POST['ap_feeamount']);
    $netAmount = urldecode($_POST['ap_netamount']);	
	$transactionDate = urldecode($_POST['ap_transactiondate']);
	$transactionType = urldecode($_POST['ap_transactiontype']);
	
    //$date3 = "9/13/2010 12:36:06 AM";
    //$date12 = DATE("Y-m-d"STRTODATE("$date3")) . DATE("H:i:s", STRTOTIME("$date3"));
    //echo $date12;
    
    $stmt1 = "INSERT INTO alertpay (ap_referencenumber, ap_status, ap_test, ap_purchasetype, ap_currency, ap_totalamount, ap_feeamount, ap_netamount, ap_transactiondate, ap_transactiontype";
    $val1 = "VALUES ( '$transactionReferenceNumber', '$transactionStatus', $testModeStatus, '$purchaseType', '$currency', $totalAmountReceived, $feeAmount, $netAmount, '$transactionDate', '$transactionType'";

    	
    //Setting the subscription's information from the IPN post variables
	$subscriptionReferenceNumber = urldecode($_POST['ap_subscriptionreferencenumber']);
	$subscriptionSetupCost = urldecode($_POST['ap_setupamount']);
	$subscriptionTimeUnit = urldecode($_POST['ap_timeunit']);
	$subscriptionPeriodLength = urldecode($_POST['ap_periodlength']);
	$subscriptionPeriodCount = urldecode($_POST['ap_periodcount']);
	
   
    //$subscriptionTrialAmount = urldecode($_POST['ap_trialamount']);
    $subscriptionTrialAmount = 0 ;
    //$subscriptionTrialTimeUnit = urldecode($_POST['ap_trialtimeunit']);
    $subscriptionTrialTimeUnit = "" ;
    //$subscriptionTrialPeriodLength = urldecode($_POST['ap_trialperiodlength']);
    $subscriptionTrialPeriodLength = 0;
    
		
	$subscriptionNextRunDate = urldecode($_POST['ap_nextrundate']);
	$subscriptionPaymentNumber = urldecode($_POST['ap_subscriptionpaymentnumber']);
	$subscriptionCancelledBy = urldecode($_POST['ap_cancelledby']);
	$subscriptionCancelNotes = urldecode($_POST['ap_cancelnotes']);
	

    $stmt2 = ", ap_subscriptionreferencenumber, ap_setupamount, ap_timeunit, ap_periodlength, ap_periodcount, ap_trialamount, ap_trialtimeunit, ap_trialperiodlength, ap_nextrundate, ap_subscriptionpaymentnumber, ap_cancelledby, ap_cancelnotes";
    $val2 = ", '$subscriptionReferenceNumber', $subscriptionSetupCost, '$subscriptionTimeUnit', $subscriptionPeriodLength, $subscriptionPeriodCount, $subscriptionTrialAmount, '$subscriptionTrialTimeUnit', $subscriptionTrialPeriodLength, '$subscriptionNextRunDate', '$subscriptionPaymentNumber', '$subscriptionCancelledBy', '$subscriptionCancelNotes'";


    
	//Setting the customer's information from the IPN post variables
	$customerFirstName = urldecode($_POST['ap_custfirstname']);
	$customerLastName = urldecode($_POST['ap_custlastname']);
	$customerAddress = urldecode($_POST['ap_custaddress']);
	$customerCity = urldecode($_POST['ap_custcity']);
	$customerState = urldecode($_POST['ap_custstate']);
	$customerCountry = urldecode($_POST['ap_custcountry']);
	$customerZipCode = urldecode($_POST['ap_custzip']);
	$customerEmailAddress = urldecode($_POST['ap_custemailaddress']);
	

    $stmt3 = ", ap_custfirstname, ap_custlastname, ap_custaddress, ap_custcity, ap_custstate, ap_custcountry, ap_custzip, ap_custemailaddress";
    $val3 = ", '$customerFirstName', '$customerLastName', '$customerAddress', '$customerCity', '$customerState', '$customerCountry', '$customerZipCode', '$customerEmailAddress'";



	//Setting information about the purchased service from the IPN post variables
	$itemName = urldecode($_POST['ap_itemname']);
	$itemCode = urldecode($_POST['ap_itemcode']);
	$itemDescription = urldecode($_POST['ap_description']);
	$itemQuantity = urldecode($_POST['ap_quantity']);
	$itemAmount = urldecode($_POST['ap_amount']);
	
	
    $stmt4 = ", ap_itemname, ap_itemcode, ap_description, ap_quantity, ap_amount";
    $val4 = ", '$itemName', '$itemCode', '$itemDescription', $itemQuantity, $itemAmount";


    //Setting extra information about the purchased item from the IPN post variables
	$additionalCharges = urldecode($_POST['ap_additionalcharges']);
	$shippingCharges = urldecode($_POST['ap_shippingcharges']);
	$taxAmount = urldecode($_POST['ap_taxamount']);
	$discountAmount = urldecode($_POST['ap_discountamount']);
	 
	
    $stmt5 = ", ap_additionalcharges, ap_shippingcharges, ap_taxamount, ap_discountamount";
    $val5 = ", $additionalCharges, $shippingCharges, $taxAmount, $discountAmount";


    //Setting your customs fields received from the IPN post variables
	$myCustomField_1 = urldecode($_POST['apc_1']);
	$myCustomField_2 = urldecode($_POST['apc_2']);
	$myCustomField_3 = urldecode($_POST['apc_3']);
	$myCustomField_4 = urldecode($_POST['apc_4']);
	$myCustomField_5 = urldecode($_POST['apc_5']);
	$myCustomField_6 = urldecode($_POST['apc_6']);


	$stmt6 = ", apc_1, apc_2, apc_3, apc_4, apc_5, apc_6)";
    $val6 = ", '$myCustomField_1', '$myCustomField_2', '$myCustomField_3', '$myCustomField_4', '$myCustomField_5', '$myCustomField_6')";

    $stmt = $stmt1.$stmt2.$stmt3.$stmt4.$stmt5.$stmt6 ;
    $val = $val1.$val2.$val3.$val4.$val5.$val6 ;

    fwrite($f, $stmt.$val);

    if ($receivedMerchantEmailAddress != MY_MERCHANT_EMAIL) {
        // The data was not meant for the business profile under this email address.
		// Take appropriate action.
	}
	else {	
	    // Check if the security code matches
		if ($mySecurityCode != IPN_SECURITY_CODE) {
             // The data is NOT sent by AlertPay.
			// Take appropriate action.
		      
        }
		else {
            // Check if it is an initial payment for a subscription
	
             if ($purchaseType == "subscription" && $transactionStatus == "Success") {
                // Check if there was a trial period
			    
				 if (isset ($subscriptionTrialAmount) && isset ($subscriptionTrialTimeUnit) && isset ($subscriptionTrialPeriodLength)) {
                    if ($subscriptionTrialAmount == 0) {

                        // It is a FREE trial and no transaction reference number is returned.
						// Check if TEST MODE is on/off and apply the proper logic.
						// A subscription reference number will be returned.
						// Process the order here by cross referencing the received data with your database.
						// After verification, update your database accordingly.
	


    				}
					elseif ($subscriptionTrialAmount > 0) {

                    
                       	// Is is a PAID trial and transaction reference number will be returned.
						// Check if TEST MODE is on/off. and apply the proper logic. 
						// If Test Mode is ON then no transaction reference number will be returned.
						// A subscription reference number will be returned.
						// Process the order here by cross referencing the received data with your database. 														
						// Check that the total amount paid was the expected amount.
						// Check that the amount paid was for the correct service.
						// Check that the currency is correct.
						// ie: if ($totalAmountReceived == 50) ... etc ...
						// After verification, update your database accordingly.						
					}
					else {
                        // The trial amount is invalid.
						// Take appropriate action.
	
    			      	}
    			      	
    			      	    
                       //if(!$db->Exists("SELECT * FROM references WHERE ap_referencenumber = '" . $transactionReferenceNumber . "'"))
                     {
                        //$db->Execute($stmt.$val);
                     }

				}
								
   			
                // There is no trial and the payment is the first installment of the subscription.
				// Check if TEST MODE is on/off and apply the proper logic. 
				// If Test Mode is ON then no transaction reference number will be returned.
				// A subscription reference number will be returned.
				// Process the order here by cross referencing the received data with your database. 														
				// Check that the total amount paid was the expected amount.
				// Check that the amount paid was for the correct service.
				// Check that the currency is correct.
				// ie: if ($totalAmountReceived == 50) ... etc ...
				// After verification, update your database accordingly.														
				
					
                   //=============================================
			}
			
            //elseif ($purchaseType == "subscription" && $transactionStatus == "Subscription-Payment-Success" && $subscriptionPaymentNumber > 1) {
            // this condition was altered by me from $subscriptionPaymentNumber > 1 to $subscriptionPaymentNumber == 1
                elseif ($purchaseType == "subscription" && $transactionStatus == "Subscription-Payment-Success" && $subscriptionPaymentNumber == 1) {

				// The payment is for a recurring subscription.
				// Check if TEST MODE is on/off and apply the proper logic. 
				// If Test Mode is ON then no transaction reference number will be returned.
				// A subscription reference number will be returned.
				// Process the order here by cross referencing the received data with your database. 														
				// Check that the total amount paid was the expected amount.
				// Check that the amount paid was for the correct service.
				// Check that the currency is correct.
				// ie: if ($totalAmountReceived == 50) ... etc ...
				// After verification, update your database accordingly.	
				$db->Execute($stmt.$val);
				 if($db->Exists("SELECT * FROM subscribers WHERE email = '" . $myCustomField_1 . "'" ))
                	{
	                  $cur_date = date("Y/m/d") ;
                      $cur_time = date("H:i:s") ;
                      $end_time = $cur_time ;
                    if ( $itemCode == "WB-1w-S" )
                        {
                          $end_date = date("Y-m-d", strtotime("+7 days"));
                          $stmt = "UPDATE subscribers set credits = 2000, start_date = '$cur_date', start_time = '$cur_time' , end_date = '$end_date', end_time = '$end_time' , type = 'Subscription' , period = 7 WHERE email = '". $myCustomField_1 . "'";
                          
                         }
                        
	                 elseif ( $itemCode == "WB-1m-S" )
                             {
                               $end_date = date("Y-m-d", strtotime("+31 days"));
                          $stmt = "UPDATE subscribers set credits = 8000 , start_date = '$cur_date', start_time = '$cur_time' , end_date = '$end_date', end_time = '$end_time' , type = 'Subscription' , period = 31 WHERE email = '". $myCustomField_1 . "'";
                             }
	                 elseif ( $itemCode == "WB-3m-S" )
                             {
                               $end_date = date("Y-m-d", strtotime("+92 days"));
                               $stmt = "UPDATE subscribers set credits = 25000 , start_date = '$cur_date', start_time = '$cur_time' , end_date = '$end_date', end_time = '$end_time' , type = 'Subscription' , period = 92 WHERE email = '". $myCustomField_1 . "'";
                             }
	                 elseif ( $itemCode == "WB-6m-S" )
                             {
                               $end_date = date("Y-m-d", strtotime("+183 days"));
                               $stmt = "UPDATE subscribers set credits = 50000 , start_date = '$cur_date', start_time = '$cur_time' , end_date = '$end_date', end_time = '$end_time' , type = 'Subscription' , period = 183 WHERE email = '". $myCustomField_1 . "'";
                             }
	                 elseif ( $itemCode == "WB-1y-S" )
                             {
                               $end_date = date("Y-m-d", strtotime("+366 days"));
                               $stmt = "UPDATE subscribers set credits = 100000 , start_date = '$cur_date', start_time = '$cur_time' , end_date = '$end_date', end_time = '$end_time' , type = 'Subscription' , period = 366 WHERE email = '". $myCustomField_1 . "'";
                             }
                      else
                           {
                             // nothing to do
                           }
	                 	                 	
	                 $db->Execute($stmt);
	                 
	                }

			}
			else {
                switch ($transactionStatus){
					
                    case "Subscription-Expired":
                        // Take appropriate when the subscription has reached its terms.
                        break;
					case "Subscription-Payment-Failed":
                        // Take appropriate actions when a payment attempt has failed.
						break;
					case "Subscription-Payment-Rescheduled":
                        // Take appropriate actions when a payment is rescheduled.
						break;
					case "Subscription-Canceled":
                        // Take appropriate actions regarding a cancellation.
						break;
					default:
                        // Take a default action in the case that none of the above were handled.
						break;
				}			

			}

		}

	}


                   
fclose($f);	
	
?>
