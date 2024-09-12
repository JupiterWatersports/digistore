<?php

 require('includes/application_top.php');

 require(DIR_WS_CLASSES . 'currencies.php');
 $currencies = new currencies();

 $oID = tep_db_prepare_input($_GET['oID']);
 $service_type = ($_GET['ups_method']);
 $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

 include(DIR_WS_CLASSES . 'order.php');
 $order = new order($oID);
 $date = date('d M, Y');

  //Configuration
  $access = "1C8FCA1570C9E1F8";
  $userid = "jupiterkb";
  $passwd = "Fusion101";
  $wsdl = "wsdl/Ship.wsdl";
  $operation = "ProcessShipment";
  //$endpointurl = 'https://wwwcie.ups.com/webservices/Rate';
 $endpointurl = 'https://wwwcie.ups.com/ups.app/xml/ShipAccept';
  $outputFileName = "../XOLTResult.xml";



      //create soap request
    $requestoption['RequestOption'] = 'nonvalidate';
    $request['Request'] = $requestoption;

    $shipment['Description'] = 'Ship WS test';
    $shipper['Name'] = 'ShipperName';
    $shipper['AttentionName'] = 'ShipperZs Attn Name';
    $shipper['TaxIdentificationNumber'] = '123456';
    $shipper['ShipperNumber'] = '';
    $address['AddressLine'] = '2311 York Rd';
    $address['City'] = 'Timonium';
    $address['StateProvinceCode'] = 'MD';
    $address['PostalCode'] = '21093';
    $address['CountryCode'] = 'US';
    $shipper['Address'] = $address;
    $phone['Number'] = '1115554758';
    $phone['Extension'] = '1';
    $shipper['Phone'] = $phone;
    $shipment['Shipper'] = $shipper;

    $shipto['Name'] = $order->delivery['company'];
    $shipto['AttentionName'] = $order->delivery['name'];
    $addressTo['AddressLine'] =  $order->delivery['street_address'].($order->delivery['suburb']?' '.$order->delivery['suburb']:'');
    $addressTo['City'] = $order->delivery['city'];
    $addressTo['StateProvinceCode'] = $order->delivery['state'];
    $addressTo['PostalCode'] = $order->delivery['postcode'];
    $addressTo['CountryCode'] = $order->delivery['country'];
    $phone2['Number'] = $order->delivery['telephone'];
    $shipto['Address'] = $addressTo;
    $shipto['Phone'] = $phone2;
    $shipment['ShipTo'] = $shipto;

    $shipfrom['Name'] = 'Jupiter kiteboarding';
    $shipfrom['AttentionName'] = '';
    $addressFrom['AddressLine'] = '1500 N US HWY 1';
    $addressFrom['City'] = 'Juipter';
    $addressFrom['StateProvinceCode'] = 'FL';
    $addressFrom['PostalCode'] = '33469';
    $addressFrom['CountryCode'] = 'US';
    $phone3['Number'] = '561-427-0240';
    $shipfrom['Address'] = $addressFrom;
    $shipfrom['Phone'] = $phone3;
    $shipment['ShipFrom'] = $shipfrom;

    $shipmentcharge['Type'] = '01';
    $creditcard['Type'] = '';
    $creditcard['Number'] = '';
    $creditcard['SecurityCode'] = '';
    $creditcard['ExpirationDate'] = '';
    $creditCardAddress['AddressLine'] = '';
    $creditCardAddress['City'] = '';
    $creditCardAddress['StateProvinceCode'] = '';
    $creditCardAddress['PostalCode'] = '';
    $creditCardAddress['CountryCode'] = '';
    $creditcard['Address'] = $creditCardAddress;
    $billshipper['CreditCard'] = $creditcard;
    $shipmentcharge['BillShipper'] = $billshipper;
    $paymentinformation['ShipmentCharge'] = $shipmentcharge;
    $shipment['PaymentInformation'] = $paymentinformation;

    $service['Code'] = $service_type;
	if ($service_type == 'GND')     $service['Description'] = 'UPS Ground';
	if ($service_type == '3DS')     $service['Description'] = 'UPS 3 Day Select';
	if ($service_type == '2DA')     $service['Description'] = 'UPS 2 Day Air';
	if ($service_type == '2DM')     $service['Description'] = 'UPS 2 Day Air A.M.';
	if ($service_type == '1DP')     $service['Description'] = 'UPS Next Day Air Saver';
	if ($service_type == '1DA')     $service['Description'] = 'UPS Next Day Air';
	if ($service_type == '1DM')     $service['Description'] = 'UPS Next Day Air Early A.M.';
    $shipment['Service'] = $service;

 for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    $package['Description'] = $order->products[$i]['qty'].'x'.$order->products[$i]['name']. "\n";
}
    $packaging['Code'] = '02';
    $packaging['Description'] = '$description_goods';
    $package['Packaging'] = $packaging;
    $unit['Code'] = 'IN';
    $unit['Description'] = 'Inches';
    $dimensions['UnitOfMeasurement'] = $unit;
    $dimensions['Length'] = '';
    $dimensions['Width'] = '';
    $dimensions['Height'] = '';
    $package['Dimensions'] = $dimensions;
    $unit2['Code'] = 'LBS';
    $unit2['Description'] = 'Pounds';
    $packageweight['UnitOfMeasurement'] = $unit2;
    $packageweight['Weight'] = $shipping_pounds2;
    $package['PackageWeight'] = $packageweight;
    $shipment['Package'] = $package;

    $labelimageformat['Code'] = 'GIF';
    $labelimageformat['Description'] = 'GIF';
    $labelspecification['LabelImageFormat'] = $labelimageformat;
    $labelspecification['HTTPUserAgent'] = 'Mozilla/4.5';
    $shipment['LabelSpecification'] = $labelspecification;
    $request['Shipment'] = $shipment;

    echo "Request.......\n";
	print_r($request);
    echo "\n\n<pre>";
   // return $request;


  function processShipment()
  {
      global $request;
      return $request;
  }

  function processShipConfirm()
  {

    //create soap request

  }

  function processShipAccept()
  {
    //create soap request
  }
  error_reporting(E_ALL);
  ini_set('display_errors','On');

 /* try
  {*/

    $mode = array
    (
         'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
         'trace' => 1
    );
	print_r($mode);

    // initialize soap client
  	$client = new SoapClient($wsdl , $mode);

  	//set endpoint url
  	$client->__setLocation($endpointurl);


    //create soap header
    $usernameToken['Username'] = $userid;
    $usernameToken['Password'] = $passwd;
    $serviceAccessLicense['AccessLicenseNumber'] = $access;
    $upss['UsernameToken'] = $usernameToken;
    $upss['ServiceAccessToken'] = $serviceAccessLicense;

    $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
    $client->__setSoapHeaders($header);

    if(strcmp($operation,"ProcessShipment") == 0 )
    {
        //get response
  	$resp = $client->__soapCall('ProcessShipment',array(processShipment()));

         //get status
        echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

        //save soap request and response to file
        $fw = fopen($outputFileName , 'w');
        fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);

    }
    else if (strcmp($operation , "ProcessShipConfirm") == 0)
    {
            //get response
  	$resp = $client->__soapCall('ProcessShipConfirm',array(processShipConfirm()));

         //get status
        echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

        //save soap request and response to file
        $fw = fopen($outputFileName , 'w');
        fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);

    }
    else
    {
        $resp = $client->__soapCall('ProcessShipeAccept',array(processShipAccept()));

        //get status
        echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

  	//save soap request and response to file
  	$fw = fopen($outputFileName ,'w');
  	fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);
    }

  /*}
  catch(Exception $ex)
  {
  	print_r ($ex);
  }*/
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
