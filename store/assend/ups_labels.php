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
  $operation = "FreightShipRequest";
  //$endpointurl = 'https://wwwcie.ups.com/webservices/Rate';
 $endpointurl = 'https://wwwcie.ups.com/ups.app/xml/ShipConfirm';
  $outputFileName = "../XOLTResult.xml";

  
  $request['Request']=array();
 /* 
    $mode = array
    (
         'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
         'trace' => 1
    );

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

	$resp = $client->__soapCall('ShipmentConfirmRequest',array($request));
*/
	
	$req='<?xml version="1.0" ?>
<AccessRequest xml:lang=\'en-US\'>
<AccessLicenseNumber>'.$access.'</AccessLicenseNumber>
<UserId>'.$userid.'</UserId>
<Password>'.$passwd.'</Password>
</AccessRequest>
<?xml version="1.0" ?>
<ShipmentConfirmRequest>
<Request>
<TransactionReference>
<CustomerContext>guidlikesubstance</CustomerContext>
<XpciVersion>1.0001</XpciVersion>
</TransactionReference>
<RequestAction>ShipConfirm</RequestAction>
<RequestOption>nonvalidate</RequestOption>
</Request>
<Shipment>
<Shipper>
<Name>Joe\'s Garage</Name>
<AttentionName>John Smith</AttentionName>
<PhoneNumber>9725551212</PhoneNumber>
<ShipperNumber>123X67</ShipperNumber>
<Address>
<AddressLine1>1000 Preston Rd</AddressLine1>
<City>Plano</City>
<StateProvinceCode>TX</StateProvinceCode>
<CountryCode>US</CountryCode>
<PostalCode>75093</PostalCode>
</Address>
</Shipper>
<ShipTo>
<CompanyName>Pep Boys</CompanyName>
<AttentionName>Manny</AttentionName>
<PhoneNumber>41051255512121234</PhoneNumber>
<Address>
<AddressLine1>201 York Rd</AddressLine1>
<City>Timonium</City>
<StateProvinceCode>MD</StateProvinceCode>
<CountryCode>US</CountryCode>
<PostalCode>21093</PostalCode>
<ResidentialAddress />
</Address>
</ShipTo>
<Service>
<Code>14</Code>
<Description>Next Day Air Early AM</Description>
</Service>
<PaymentInformation>
<Prepaid>
<BillShipper>
<CreditCard>
<Type>06</Type>
<Number>4111111111111111</Number>
<ExpirationDate>121999</ExpirationDate>
</CreditCard>
</BillShipper>
</Prepaid>
</PaymentInformation>
<Package>
<PackagingType>
<Code>02</Code>
</PackagingType>
<Dimensions>
<UnitOfMeasurement>
<Code>IN</Code>
</UnitOfMeasurement>
<Length>22</Length>
<Width>20</Width>
<Height>18</Height>
</Dimensions>
<PackageWeight>
<Weight>14.1</Weight>
</PackageWeight>
<ReferenceNumber>
<Code>02</Code>
<Value>1234567</Value>
</ReferenceNumber>
<PackageServiceOptions>
<InsuredValue>
<CurrencyCode>USD</CurrencyCode>
<MonetaryValue>149.99</MonetaryValue>
</InsuredValue>
<VerbalConfirmation>
<Name>Sidney Smith</Name>
<PhoneNumber>4105551234</PhoneNumber>
</VerbalConfirmation>
</PackageServiceOptions>
</Package>
<Package>
<PackagingType>
<Code>02</Code>
</PackagingType>
<PackageWeight>
<Weight>22.0</Weight>
</PackageWeight>
<ReferenceNumber>
<Code>PM</Code>
<Value>1234568</Value>
</ReferenceNumber>
<ReferenceNumber>
<Code>ST</Code>
<Value>Distributor</Value>
</ReferenceNumber>
<PackageServiceOptions>
<InsuredValue>
<MonetaryValue>299.99</MonetaryValue>
</InsuredValue>
</PackageServiceOptions>
</Package>
</Shipment>
<LabelSpecification>
<LabelPrintMethod>
<Code>GIF</Code>
</LabelPrintMethod>
<HTTPUserAgent>Mozilla/4.5</HTTPUserAgent>
<LabelImageFormat>
<Code>GIF</Code>
</LabelImageFormat>
</LabelSpecification>
</ShipmentConfirmRequest>';
		 $ch = curl_init($endpointurl);
		 curl_setopt($ch, CURLOPT_POST      ,1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS    ,$req);
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
		 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml'
			));
		 curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
		 $resp = curl_exec($ch);
		 curl_close($ch);
	echo $resp ;
	
  
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
