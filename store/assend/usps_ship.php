<?php
/**
 * USPS.com Click-n-Ship Auto-Fill Contrib
 * Qhome (qhomezone@gmail.com)
 *
 * @package
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: usps_autofill_button.php 3182 2007-04-23 15:40:58Z qhome $
 */

	global $db;
	require('includes/application_top.php');
	$oID = (int)tep_db_prepare_input($HTTP_GET_VARS['oID']);
	include(DIR_WS_CLASSES . 'order.php');


	$email_notification = "false";  // notify reciepient of shipping --true or false


	$order = new order($oID);
	$USPS_file_dir = DIR_WS_INCLUDES . 'usps_ship_files';    //directory where all support files are
	//Gets the return & billing address two digit state code
	$shipping_zone_query = tep_db_query("select z.zone_code from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where zone_name = '" . $order->delivery['state'] . "' AND c.countries_name = '" . $order->delivery['country'] . "' AND c.countries_id = z.zone_country_id");
	$shipping_zone = tep_db_fetch_array($shipping_zone_query);
	$shipping_zone_code = ($shipping_zone['zone_code'] == '' ? $order->delivery['state'] : $shipping_zone['zone_code']);  // if the query result was empty, then use the state name
	if ($order->billing['state'] == $order->delivery['state']) {  // if billing and shipping states are the same, then we can save a query
	  $billing_zone_code = $shipping_zone_code;
	  } else {
	  $billing_zone_query = tep_db_query("select z.zone_code from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where z.zone_name = '" . $order->billing['state'] . "' AND c.countries_name = '" . $order->billing['country'] . "' AND c.countries_id = z.zone_country_id");
	  $billing_zone = tep_db_fetch_array($billing_zone_query);
	  $billing_zone_code = ($billing_zone['zone_code'] == '' ? $order->billing['state'] : $billing_zone['zone_code']); // if the query result was empty, then use the state name
	  }

	// This checks the country to see if it should be using state check for USA or International
	//$order_check_country = $db->Execute("select delivery_country from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
	$UpperPHPCountry = strtoupper($order->delivery['country']);

	//$order_check = $db->Execute("select customers_telephone, customers_email_address,
	//                                    TRIM(TRAILING substring_index(delivery_name,' ', -1) from delivery_name) as delivery_firstname,
	 ///                                   substring_index(delivery_name,' ', -1) as delivery_lastname,
	   //                                 delivery_company, delivery_street_address, delivery_suburb,
	    ///                                delivery_city, delivery_postcode, delivery_state, delivery_country, zone_code,
	      //                              last_modified from " . TABLE_ORDERS . ', ' . TABLE_ZONES ."
	       //                             where orders_id = '" . (int)$oID . "' and zone_name = delivery_state");

	// Get order subtotal for use as insurance or declaration value (if those options are enabled)
	If (USPS_DELIVERY_DEFAULT_CONTENTS_VALUE == "subtotal" || USPS_DELIVERY_INSURANCE_VALUE == "subtotal") {
	  //$order_total_check = $db->Execute("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_subtotal'");
	}

	If (USPS_DELIVERY_DEFAULT_CONTENTS_VALUE == "total" || USPS_DELIVERY_INSURANCE_VALUE == "total") {
	 // $order_total_check2 = $db->Execute("select value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_total'");
	}

	// String of all the countries copied from the usps form. Followed by explode functions to save me the work of having to list them manually.
	$AllCountries=('<option value="1">UNITED STATES</option><option value="10440">Abu Dhabi (United Arab Emirates)</option><option value="10345">Admiralty Islands (Papua New Guinea)</option><option value="10000">Afghanistan</option><option value="10314">Aitutaki, Cook Islands (New Zealand)</option><option value="10441">Ajman (United Arab Emirates)</option><option value="10110">Aland Island (Finland)</option><option value="10001">Albania</option><option value="12000">Alberta (Canada)</option><option value="10140">Alderney (Channel Islands) (Great Britain and Northern Ireland)</option><option value="10002">Algeria</option><option value="10388">Alhucemas (Spain)</option><option value="10309">Alofi Island (New Caledonia)</option><option value="10193">Andaman Islands (India)</option><option value="10003">Andorra</option><option value="10004">Angola</option><option value="10005">Anguilla</option><option value="10068">Anjouan (Comoros)</option><option value="10097">Annobon Island (Equatorial Guinea)</option><option value="12140">Antigua (Antigua and Barbuda)</option><option value="10006">Antigua and Barbuda</option><option value="10009">Argentina</option><option value="10010">Armenia</option><option value="10011">Aruba</option><option value="10012">Ascension</option><option value="10152">Astypalaia (Greece)</option><option value="10505">Atafu (Western Samoa)</option><option value="12026">Atiu, Cook Islands (New Zealand)</option><option value="10013">Australia</option><option value="10026">Austria</option><option value="10315">Avarua (New Zealand)</option><option value="10027">Azerbaijan</option><option value="10359">Azores (Portugal)</option><option value="10028">Bahamas</option><option value="10029">Bahrain</option><option value="10389">Balearic Islands (Spain)</option><option value="10342">Baluchistan (Pakistan)</option><option value="10030">Bangladesh</option><option value="10496">Banks Island (Vanuatu)</option><option value="10031">Barbados</option><option value="10007">Barbuda (Antigua and Barbuda)</option><option value="10171">Barthelemy (Guadeloupe)</option><option value="10032">Belarus</option><option value="10033">Belgium</option><option value="10034">Belize</option><option value="10036">Benin</option><option value="10038">Bermuda</option><option value="10039">Bhutan</option><option value="10346">Bismark Archipelago (Papua New Guinea)</option><option value="10040">Bolivia</option><option value="10299">Bonaire (Netherlands Antilles)</option><option value="10118">Borabora (French Polynesia)</option><option value="10200">Borneo (Indonesia)</option><option value="10041">Bosnia-Herzegovina</option><option value="10042">Botswana</option><option value="10347">Bougainville (Papua New Guinea)</option><option value="10366">Bourbon (Reunion)</option><option value="10043">Brazil</option><option value="12001">British Columbia (Canada)</option><option value="10184">British Guiana (Guyana)</option><option value="10035">British Honduras (Belize)</option><option value="10044">British Virgin Islands</option><option value="10045">Brunei Darussalam</option><option value="10348">Buka (Papua New Guinea)</option><option value="10046">Bulgaria</option><option value="10047">Burkina Faso</option><option value="10048">Burma</option><option value="10050">Burundi</option><option value="10434">Caicos Islands (Turks and Caicos Islands)</option><option value="10051">Cambodia</option><option value="10053">Cameroon</option><option value="10054">Canada</option><option value="10390">Canary Islands (Spain)</option><option value="10225">Canton Island (Kiribati)</option><option value="10057">Cape Verde</option><option value="10058">Cayman Islands</option><option value="10059">Central African Republic</option><option value="10391">Ceuta (Spain)</option><option value="10397">Ceylon (Sri Lanka)</option><option value="10060">Chad</option><option value="10392">Chaferinas Islands (Spain)</option><option value="10153">Chalki (Greece)</option><option value="10141">Channel Islands (Jersey, Guernsey, Alderney and Sark) (Great Britain and Northern Ireland)</option><option value="10062">Chile</option><option value="10063">China</option><option value="10014">Christmas Island (Australia)</option><option value="10226">Christmas Island (Kiribati)</option><option value="10015">Cocos Island (Australia)</option><option value="10067">Colombia</option><option value="10069">Comoros</option><option value="10073">Congo, Democratic Republic of the</option><option value="10072">Congo, Republic of the</option><option value="10316">Cook Islands (New Zealand)</option><option value="10098">Corisco Island (Equatorial Guinea)</option><option value="10112">Corsica (France)</option><option value="10080">Costa Rica</option><option value="10081">Cote d Ivoire</option><option value="10154">Crete (Greece)</option><option value="10082">Croatia</option><option value="10083">Cuba</option><option value="10280">Cumino Island (Malta)</option><option value="10300">Curacao (Netherlands Antilles)</option><option value="10243">Cyjrenaica (Libya)</option><option value="10085">Cyprus</option><option value="10086">Czech Republic</option><option value="10037">Dahomey (Benin)</option><option value="10194">Damao (India)</option><option value="10317">Danger Islands (New Zealand)</option><option value="10087">Denmark</option><option value="10172">Desirade Island (Guadeloupe)</option><option value="10195">Diu (India)</option><option value="10088">Djibouti</option><option value="10155">Dodecanese Islands (Greece)</option><option value="10363">Doha (Qatar)</option><option value="10091">Dominica</option><option value="10092">Dominican Republic</option><option value="10442">Dubai (United Arab Emirates)</option><option value="10201">East Timor (Indonesia)</option><option value="10093">Ecuador</option><option value="10094">Egypt</option><option value="10209">Eire (Ireland)</option><option value="10095">El Salvador</option><option value="10436">Ellice Islands (Tuvalu)</option><option value="10099">Elobey Islands (Equatorial Guinea)</option><option value="10227">Enderbury Island (Kiribati)</option><option value="10142">England (Great Britain and Northern Ireland)</option><option value="10100">Equatorial Guinea</option><option value="10103">Eritrea</option><option value="10104">Estonia</option><option value="10105">Ethiopia</option><option value="10506">Fakaofo (Western Samoa)</option><option value="10106">Falkland Islands</option><option value="10228">Fanning Island (Kiribati)</option><option value="10108">Faroe Islands</option><option value="10457">Federated States of Micronesia</option><option value="10101">Fernando Po (Equatorial Guinea)</option><option value="10244">Fezzan (Libya)</option><option value="10109">Fiji</option><option value="10111">Finland</option><option value="10414">Formosa (Taiwan)</option><option value="10113">France</option><option value="10117">French Guiana</option><option value="10119">French Oceania (French Polynesia)</option><option value="10120">French Polynesia</option><option value="10089">French Somaliland (Djibouti)</option><option value="10090">French Territory of the Afars and Issas (Djibouti)</option><option value="10173">French West Indies (Guadeloupe)</option><option value="12002">French West Indies (Martinique)</option><option value="10426">Friendly Islands (Tonga)</option><option value="10443">Fujairah (United Arab Emirates)</option><option value="10503">Futuna (Wallis and Futuna Islands)</option><option value="10134">Gabon</option><option value="10135">Gambia</option><option value="10121">Gambier (French Polynesia)</option><option value="10136">Georgia, Republic of</option><option value="10137">Germany</option><option value="10138">Ghana</option><option value="10139">Gibraltar</option><option value="10229">Gilbert Islands (Kiribati)</option><option value="10196">Goa (India)</option><option value="10281">Gozo Island (Malta)</option><option value="10070">Grand Comoro (Comoros)</option><option value="10143">Great Britain and Northern Ireland</option><option value="10156">Greece</option><option value="10169">Greenland</option><option value="10170">Grenada</option><option value="10407">Grenadines (Saint Vincent and the Grenadines)</option><option value="10174">Guadeloupe</option><option value="10181">Guatemala</option><option value="10144">Guernsey (Channel Islands) (Great Britain and Northern Ireland)</option><option value="10182">Guinea</option><option value="10183">Guinea-Bissau</option><option value="10185">Guyana</option><option value="10064">Hainan Island (China)</option><option value="10186">Haiti</option><option value="10220">Hashemite Kingdom (Jordan)</option><option value="10318">Hervey, Cook Islands (New Zealand)</option><option value="10122">Hivaoa (French Polynesia)</option><option value="10297">Holland (Netherlands)</option><option value="10187">Honduras</option><option value="10189">Hong Kong</option><option value="10123">Huahine (French Polynesia)</option><option value="10310">Huan Island (New Caledonia)</option><option value="10191">Hungary</option><option value="10192">Iceland</option><option value="10197">India</option><option value="10202">Indonesia</option><option value="10206">Iran</option><option value="10208">Iraq</option><option value="10210">Ireland</option><option value="10203">Irian Barat (Indonesia)</option><option value="10145">Isle of Man (Great Britain and Northern Ireland)</option><option value="10311">Isle of Pines (New Caledonia)</option><option value="10084">Isle of Pines, West Indies (Cuba)</option><option value="10211">Israel</option><option value="12077">Issas (Djibouti)</option><option value="10212">Italy</option><option value="12312">Ivory Coast (Cote d Ivoire)</option><option value="10213">Jamaica</option><option value="10214">Japan</option><option value="10146">Jersey (Channel Islands) (Great Britain and Northern Ireland)</option><option value="10259">Johore (Malaysia)</option><option value="10221">Jordan</option><option value="10157">Kalymnos (Greece)</option><option value="10052">Kampuchea (Cambodia)</option><option value="10158">Karpathos (Greece)</option><option value="10159">Kassos (Greece)</option><option value="10160">Kastellorizon (Greece)</option><option value="10223">Kazakhstan</option><option value="10260">Kedah (Malaysia)</option><option value="10016">Keeling Islands (Australia)</option><option value="10261">Kelantan (Malaysia)</option><option value="10224">Kenya</option><option value="10230">Kiribati</option><option value="10232">Korea, Democratic Peoples Republic of (North Korea)</option><option value="10234">Korea, Republic of (South Korea)</option><option value="10161">Kos (Greece)</option><option value="12314">Kosovo, Republic of</option><option value="10464">Kosrae, Micronesia</option><option value="10190">Kowloon (Hong Kong)</option><option value="10236">Kuwait</option><option value="10237">Kyrgyzstan</option><option value="10055">Labrador (Canada)</option><option value="10262">Labuan (Malaysia)</option><option value="10238">Laos</option><option value="10239">Latvia</option><option value="10240">Lebanon</option><option value="10162">Leipsos (Greece)</option><option value="10163">Leros (Greece)</option><option value="10175">Les Saints Island (Guadeloupe)</option><option value="10241">Lesotho</option><option value="10242">Liberia</option><option value="10245">Libya</option><option value="10247">Liechtenstein</option><option value="10248">Lithuania</option><option value="10017">Lord Howe Island (Australia)</option><option value="10312">Loyalty Islands (New Caledonia)</option><option value="10249">Luxembourg</option><option value="10250">Macao</option><option value="10251">Macau (Macao)</option><option value="10252">Macedonia, Republic of</option><option value="10253">Madagascar</option><option value="10360">Madeira Islands (Portugal)</option><option value="10263">Malacca (Malaysia)</option><option value="10254">Malagasy Republic (Madagascar)</option><option value="10256">Malawi</option><option value="10264">Malaya (Malaysia)</option><option value="10265">Malaysia</option><option value="10278">Maldives</option><option value="10279">Mali</option><option value="10282">Malta</option><option value="10319">Manahiki (New Zealand)</option><option value="10065">Manchuria (China)</option><option value="12006">Manitoba (Canada)</option><option value="10176">Marie Galante (Guadeloupe)</option><option value="10124">Marquesas Islands (French Polynesia)</option><option value="10283">Martinique</option><option value="10284">Mauritania</option><option value="10285">Mauritius</option><option value="10115">Mayotte (France)</option><option value="10393">Melilla (Spain)</option><option value="10287">Mexico</option><option value="10404">Miquelon (Saint Pierre and Miquelon)</option><option value="10071">Moheli (Comoros)</option><option value="10288">Moldova</option><option value="10116">Monaco (France)</option><option value="10289">Mongolia</option><option value="12313">Montenegro</option><option value="10290">Montserrat</option><option value="10125">Moorea (French Polynesia)</option><option value="10291">Morocco</option><option value="10292">Mozambique</option><option value="10340">Muscat (Oman)</option><option value="10049">Myanmar (Burma)</option><option value="10293">Namibia</option><option value="10215">Nansil Islands (Japan)</option><option value="10295">Nauru</option><option value="10266">Negri Sembilan (Malaysia)</option><option value="10296">Nepal</option><option value="10298">Netherlands</option><option value="10301">Netherlands Antilles</option><option value="10302">Netherlands West Indies (Netherlands Antilles)</option><option value="10399">Nevis (Saint Christopher and Nevis)</option><option value="10349">New Britain (Papua New Guinea)</option><option value="12011">New Brunswick (Canada)</option><option value="10313">New Caledonia</option><option value="10350">New Hanover (Papua New Guinea)</option><option value="10497">New Hebrides (Vanuatu)</option><option value="10351">New Ireland (Papua New Guinea)</option><option value="10018">New South Wales (Australia)</option><option value="10324">New Zealand</option><option value="10056">Newfoundland (Canada)</option><option value="10335">Nicaragua</option><option value="10336">Niger</option><option value="10337">Nigeria</option><option value="10164">Nissiros (Greece)</option><option value="10325">Niue (New Zealand)</option><option value="10019">Norfolk Island (Australia)</option><option value="10267">North Borneo (Malaysia)</option><option value="10233">North Korea (Korea, Democratic People\'s Republic of)</option><option value="10147">Northern Ireland (Great Britain and Northern Ireland)</option><option value="12012">Northwest Territory (Canada)</option><option value="10338">Norway</option><option value="12013">Nova Scotia (Canada)</option><option value="10126">Nukahiva (French Polynesia)</option><option value="10507">Nukunonu (Western Samoa)</option><option value="10257">Nyasaland (Malawi)</option><option value="10231">Ocean Island (Kiribati)</option><option value="10217">Okinawa (Japan)</option><option value="10341">Oman</option><option value="12014">Ontario (Canada)</option><option value="10268">Pahang (Malaysia)</option><option value="10343">Pakistan</option><option value="10326">Palmerston, Avarua (New Zealand)</option><option value="10344">Panama</option><option value="10352">Papua New Guinea</option><option value="10353">Paraguay</option><option value="10327">Parry, Cook Islands (New Zealand)</option><option value="10165">Patmos (Greece)</option><option value="10420">Pemba (Tanzania)</option><option value="10269">Penang (Malaysia)</option><option value="10415">Penghu Islands (Taiwan)</option><option value="10394">Penon de Velez de la Gomera (Spain)</option><option value="10328">Penrhyn, Tongareva (New Zealand)</option><option value="10270">Perak (Malaysia)</option><option value="10271">Perlis (Malaysia)</option><option value="10207">Persia (Iran)</option><option value="10354">Peru</option><option value="10416">Pescadores Islands (Taiwan)</option><option value="10177">Petite Terre (Guadeloupe)</option><option value="10355">Philippines</option><option value="10356">Pitcairn Island</option><option value="10357">Poland</option><option value="10362">Portugal</option><option value="12015">Prince Edward Island (Canada)</option><option value="10272">Province Wellesley (Malaysia)</option><option value="10329">Pukapuka (New Zealand)</option><option value="10364">Qatar</option><option value="12016">Quebec (Canada)</option><option value="10020">Queensland (Australia)</option><option value="10417">Quemoy (Taiwan)</option><option value="10127">Raiatea (French Polynesia)</option><option value="10330">Rakaanga (New Zealand)</option><option value="10128">Rapa (French Polynesia)</option><option value="10331">Rarotonga, Cook Islands (New Zealand)</option><option value="10444">Ras al Kaimah (United Arab Emirates)</option><option value="10008">Redonda (Antigua and Barbuda)</option><option value="10367">Reunion</option><option value="10515">Rhodesia (Zimbabwe)</option><option value="10102">Rio Muni (Equatorial Guinea)</option><option value="10166">Rodos (Greece)</option><option value="10286">Rodrigues (Mauritius)</option><option value="10368">Romania</option><option value="10369">Russia</option><option value="10370">Rwanda</option><option value="10303">Saba (Netherlands Antilles)</option><option value="10273">Sabah (Malaysia)</option><option value="12019">Saint Barthelemy (Guadeloupe)</option><option value="10178">Saint Bartholomew (Guadeloupe)</option><option value="10400">Saint Christopher and Nevis</option><option value="10304">Saint Eustatius (Netherlands Antilles)</option><option value="10402">Saint Helena</option><option value="10401">Saint Kitts (Saint Christopher and Nevis)</option><option value="10403">Saint Lucia</option><option value="10305">Saint Maarten (Dutch) (Netherlands Antilles)</option><option value="10179">Saint Martin (French) (Guadeloupe)</option><option value="10405">Saint Pierre and Miquelon</option><option value="12017">Sainte Marie de Madagascar (Madagascar)</option><option value="10096">Salvador (El Salvador)</option><option value="10371">San Marino</option><option value="10381">Santa Cruz Islands (Solomon Island)</option><option value="10372">Sao Tome and Principe</option><option value="10274">Sarawak (Malaysia)</option><option value="12018">Saskatchewan (Canada)</option><option value="10373">Saudi Arabia</option><option value="10332">Savage Island, Niue (New Zealand)</option><option value="10508">Savaii Island (Western Samoa)</option><option value="10149">Scotland (Great Britain and Northern Ireland)</option><option value="10275">Selangor (Malaysia)</option><option value="10374">Senegal</option><option value="10376">Seychelles</option><option value="10445">Sharja (United Arab Emirates)</option><option value="10218">Shikoku (Japan)</option><option value="10423">Siam (Thailand)</option><option value="10377">Sierra Leone</option><option value="10198">Sikkim (India)</option><option value="10378">Singapore</option><option value="10379">Slovak Republic (Slovakia)</option><option value="10380">Slovenia</option><option value="10129">Society Islands (French Polynesia)</option><option value="10382">Solomon Islands</option><option value="10383">Somali Democratic Republic (Somalia)</option><option value="10384">Somalia</option><option value="10385">Somaliland (Somalia)</option><option value="10386">South Africa</option><option value="10021">South Australia (Australia)</option><option value="10107">South Georgia (Falkland Islands)</option><option value="10235">South Korea (Korea, Republic of)</option><option value="10294">South-West Africa (Namibia)</option><option value="10395">Spain</option><option value="10339">Spitzbergen (Norway)</option><option value="10398">Sri Lanka</option><option value="10408">Sudan</option><option value="10409">Suriname</option><option value="10333">Suwarrow Islands (New Zealand)</option><option value="10188">Swan Islands (Honduras)</option><option value="10410">Swaziland</option><option value="10411">Sweden</option><option value="10412">Switzerland</option><option value="10167">Symi (Greece)</option><option value="10413">Syrian Arab Republic (Syria)</option><option value="10130">Tahaa (French Polynesia)</option><option value="10131">Tahiti (French Polynesia)</option><option value="10418">Taiwan</option><option value="10419">Tajikistan</option><option value="10421">Tanzania</option><option value="10022">Tasmania (Australia)</option><option value="10061">Tchad (Chad)</option><option value="10424">Thailand</option><option value="10023">Thursday Island (Australia)</option><option value="10066">Tibet (China)</option><option value="10168">Tilos (Greece)</option><option value="10204">Timor (Indonesia)</option><option value="10428">Tobago (Trinidad and Tobago)</option><option value="10425">Togo</option><option value="10509">Tokelau (Union Group) (Western Samoa)</option><option value="10427">Tonga</option><option value="10334">Tongareva (New Zealand)</option><option value="10219">Tori Shima (Japan)</option><option value="10498">Torres Island (Vanuatu)</option><option value="10222">Trans-Jordan, Hashemite Kingdom (Jordan)</option><option value="10387">Transkei (South Africa)</option><option value="10276">Trengganu (Malaysia)</option><option value="10429">Trinidad and Tobago</option><option value="10246">Tripolitania (Libya)</option><option value="10430">Tristan da Cunha</option><option value="10446">Trucial States (United Arab Emirates)</option><option value="10132">Tuamotou (French Polynesia)</option><option value="10133">Tubuai (French Polynesia)</option><option value="10431">Tunisia</option><option value="10432">Turkey</option><option value="10433">Turkmenistan</option><option value="10435">Turks and Caicos Islands</option><option value="10437">Tuvalu</option><option value="10438">Uganda</option><option value="10439">Ukraine</option><option value="10365">Umm Said (Qatar)</option><option value="10447">Umm al Quaiwain (United Arab Emirates)</option><option value="10510">Union Group (Western Samoa)</option><option value="10448">United Arab Emirates</option><option value="10150">United Kingdom (Great Britain and Northern Ireland)</option><option value="10511">Upolu Island (Western Samoa)</option><option value="10449">Uruguay</option><option value="10495">Uzbekistan</option><option value="10499">Vanuatu</option><option value="10500">Vatican City</option><option value="10501">Venezuela</option><option value="10024">Victoria (Australia)</option><option value="10502">Vietnam</option><option value="12023">Virgin Islands (British)</option><option value="10151">Wales (Great Britain and Northern Ireland)</option><option value="10504">Wallis and Futuna Islands</option><option value="10277">Wellesley, Province (Malaysia)</option><option value="10205">West New Guinea (Indonesia)</option><option value="10025">Western Australia (Australia)</option><option value="10512">Western Samoa</option><option value="10513">Yemen</option><option value="12024">Yukon Territory (Canada)</option><option value="10396">Zafarani Islands (Spain)</option><option value="10514">Zambia</option><option value="10422">Zanzibar (Tanzania)</option><option value="10516">Zimbabwe</option>');

	$ParseTest = explode("</option><option ", $AllCountries);
	for ($c=0;$c<=count($ParseTest);$c++) {
	  $ParseTest2[$c] = explode("value=\"", $ParseTest[$c]);
	}
	for ($f=0;$f<=count($ParseTest);$f++) {
	  $ParseTest3[$f] = explode("\">", $ParseTest2[$f][1]);
	  $cn_abbrv[$f] = $ParseTest3[$f][0];
       $ParseTest4[$f] = explode(" )", $ParseTest3[$f][1]);
	  $CountryName[$f] = $ParseTest4[$f][0];
	}
	$ExactMatch = 0;

	// Check if country exists (Exact match)
	for ($i=0;$i<count($ParseTest);$i++) {
	  $UpperCountry = strtoupper($CountryName[$i]);
	  if ($UpperCountry == $UpperPHPCountry) {
	    $ExactMatch = 1;
	    $sCountry = $CountryName[$i];
	    $CountryNum = $i;
	    $sCountryNum = $cn_abbrv[$CountryNum];
	    break;
	  }
	}
	// Check strpos for any partial country matches.
	// This is for countries like "United Kingdom" but USPS has "United Kingdom (Great Britain)"
	if ($ExactMatch < 1) {
	  //echo "\nExact ascii country match not found, searching for partial match. Verify Country is correct\n";
	  if (!$UpperPHPCountry == Null) {
	    for ($i=0;$i<count($ParseTest);$i++) {
	      $UpperCountry = strtoupper($CountryName[$i]);
	      $FoundCountry = strpos($UpperCountry, $UpperPHPCountry);
	      if ($FoundCountry > -1) {
	        $sCountry = $CountryName[$i];
	        $CountryNum = $i;
	        $sCountryNum = $cn_abbrv[$CountryNum];
	        break;
	      }
	    }
	  }
	}

	// Weight Calculations
	if (USPS_WEIGHT_OVERRIDE != '') {
	  $shipping_weight = USPS_WEIGHT_OVERRIDE;
	  } else {
	  $weight_query = tep_db_query("select sum(op.products_quantity * p.products_weight) as weight from " . TABLE_PRODUCTS . " p, " . TABLE_ORDERS_PRODUCTS . " op where op.products_id = p.products_id AND op.orders_id = '" . (int)$oID . "'");
	  $total_weight = tep_db_fetch_array($weight_query);
	  $shipping_weight =  $total_weight['weight'] + SHIPPING_BOX_WEIGHT;  // adds the "Package Tare weight" configuration value to the package value
	 
	  }
	   $shipping_weight = ($shipping_weight < 0.0625 ? 0.0625 : $shipping_weight); // if shipping weight is less than one ounce then make it one ounce
	$shipping_weight = ceil($shipping_weight*16)/16;  // rounds up to the next ounce, 4.6 oz becomes 5 oz, 15.7 oz becomes 1 lb
	$shipping_pounds = floor ($shipping_weight);
	$shipping_ounces = (16 * ($shipping_weight  - floor($shipping_weight)));

	$contents_value = ceil(substr(strip_tags($order->totals[0]['text']),1));
	$send_value = (USPS_SEND_VALUE_OVER > $contents_value ? '' : $contents_value);

	
	$arr = explode(" ", $order->delivery['name'], 2);
	                                                               $myFirstName = $arr[0];
	                                                               $myLastName = ($arr[1])?$arr[1]:' - ';
	                                                               $myFirstName = ucfirst(strtolower($myFirstName));
	                                                               if(strlen($myFirstName) < 3 ){
	                                                                  $myFirstName = substr($order->delivery['name'], 0, strrpos( $order->delivery['name'], ' ') );
	                                                               }


$xml ='<?xml version="1.0" encoding="UTF-8" ?>
<ExpressMailLabelRequest  USERID="998JUPIT0756">

    <Option />

    <Revision>2</Revision>

    <EMCAAccount />

    <EMCAPassword />

    <ImageParameters />

    <FromFirstName>Jeremy</FromFirstName>

    <FromLastName>Green</FromLastName>

    <FromFirm/>

<FromAddress1>1500 N US Highway 1</FromAddress1>

<FromAddress2>-</FromAddress2>

<FromCity>Jupiter</FromCity>

<FromState>FL</FromState>

<FromZip5>33469</FromZip5>

<FromZip4/>

    <FromPhone>5614270240</FromPhone>

<ToFirstName>'.$myFirstName.'</ToFirstName>

<ToLastName>'.$myLastName.'</ToLastName>

<ToFirm>'.($order->delivery['company']==''?'-':$order->delivery['company']).'</ToFirm>

<ToAddress1>'.$order->delivery['street_address'].'</ToAddress1>

<ToAddress2>'.($order->delivery['suburb']==''?'-':$order->delivery['suburb']).'</ToAddress2>

<ToCity>'.$order->delivery['city'].'</ToCity>

<ToState>'.$shipping_zone_code.'</ToState>

<ToZip5>'.$order->delivery['postcode'].'</ToZip5>

<ToZip4 />

    <ToPhone>'.str_pad(preg_replace("/[^0-9]/", "", $order->customer['telephone']), 10, "0", STR_PAD_LEFT).'</ToPhone>

    <WeightInOunces>'.(16*$shipping_weight).'</WeightInOunces>

    <FlatRate/>

    <SundayHolidayDelivery/>

    <StandardizeAddress/>

    <WaiverOfSignature/>

    <NoHoliday/>

    <NoWeekend/>

    <SeparateReceiptPage/>

    <POZipCode>'.$order->delivery['postcode'].'</POZipCode>

    <FacilityType>DDU</FacilityType>

    <ImageType>PDF</ImageType>

    <LabelDate>'.date('m/d/Y',time()+24*3600).'</LabelDate>

    <CustomerRefNo/>

    <SenderName>Jupiter Kiteboarding</SenderName>

    <SenderEMail>customersupport@jupiterkiteboarding.com</SenderEMail>

    <RecipientName>'.$myFirstName.' '.$myLastName.'</RecipientName>

    <RecipientEMail>'.$order->customer['email_address'].'</RecipientEMail>

    <HoldForManifest/>

    <CommercialPrice>false</CommercialPrice>

    <InsuredAmount></InsuredAmount>

<Container></Container>

<Size>Regular</Size>

<Width></Width>

<Length></Length>

<Height></Height>

<Girth></Girth>

  </ExpressMailLabelRequest>';
//$url = 'http://testing.shippingapis.com/ShippingAPITest.dll?API=ExpressMailLabelRequest';
$url = 'https://secure.shippingapis.com/ShippingAPI.dll?API=ExpressMailLabel';
//$url = 'https://secure.shippingapis.com/ShippingAPI.dll?API=ExpressMailLabel&XML=';

/*echo '<pre>';
echo htmlspecialchars($xml);
echo '</pre>';*/

$post_data = array(
    "XML" => $xml,
);

$stream_options = array(
    'http' => array(
       'method'  => 'POST',
       'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
       'content' => http_build_query($post_data),
    ),
);

$context  = stream_context_create($stream_options);
$response = file_get_contents($url, null, $context);
//echo $response; 
//exit;
$xml = simplexml_load_string($response );
$xml_array = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="the.pdf"');
echo base64_decode($xml_array['EMLabel']);







?>