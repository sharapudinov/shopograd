<?
// USAGE: Call www.site.ru/_tools/import/opentao.php?secret=taobao from your browser
// FORCE IMPORT OF SOME PROFILE: www.site.ru/_tools/import/opentao.php?secret=taobao&profile=123

// SETTINGS
$arSettings = array(
	"WEBSERVICE" => "http://otapi.net/OtapiWebService2.asmx/BatchSearchItemsFrame?instanceKey=710057b8-b668-41f9-a009-f60b82462a63&language=ru&sessionId=&blockList=",
	"IMPORT_INTERVAL" => 0, // 1/2 day between full import ended/started
	"TIME_LIMIT" => 30, // 30 seconds per action
	"TIMEOUT" => 2000, // 2 seconds pause before next run on JS
	"SLEEP" => 50000, // 50000 microseconds sleep time between heavy operations
	"SECRET" => 'taobao', // secret word to prevent unallowed runs
	"PROFILES_IBLOCK_ID" => 15, // stores import profiles with main settings
	"MAIN_IBLOCK_ID" => 1,
	"OFFERS_IBLOCK_ID" => 2,
	"BRANDS_IBLOCK_ID" => 11,
	"BRANDS_IMPORT_PRODUCT_WORDS_PROP" => "IMPORT_PRODUCT_WORDS",
	"LAST_RUN_FILE_NAME" => "opentao_last_run.txt", // last time all import operations were finished
	"LAST_PROFILE_FILE_NAME" => "opentao_last_profile.txt", // stores last ID of proceeded import profile
	"OFFSET_FILE_NAME" => "opentao_offset.txt", // stores the number of proceeded elements
	"PROFILE_PROPERTIES"=> array(
		"LOG" => "LOG", // stores log
		"DEST_SECTION" => "DEST_SECTION",
		"SETTINGS" => "^OT_SETTINGS_", // search settings (pattern of property codes)
		"FEATURES" => "^OT_FEATURES_", // additional search settings (pattern of property codes)
		"TIME" => "^TIME_", // properties to be set as a time()+number (pattern of property codes)
		"SET" => "^SET_", // properties to be set as defined value (pattern of property codes)
		"LAST_RUN" => "LAST_RUN",
		"PROFIT" => "PROFIT",
		"MAX_QUANTITY" => "MAX_QUANTITY",
		"ONE_TIME_QUANTITY" => "ONE_TIME_QUANTITY",
		"IMPORTANT" => "IMPORTANT",
		"SKIP_FIRST_PICTURE" => "SKIP_FIRST_PICTURE",
		"CLEAR_NAME"=>"CLEAR_NAME",
		"CLEAR_NAME_BRACKETS"=>"CLEAR_NAME_BRACKETS",
		"PRICE_REDUCE"=>"PRICE_REDUCE",
		"FIXED_PRODUCT_NAME" => "FIXED_PRODUCT_NAME",
		"SEARCH_WORDS" => "SEARCH_WORDS",
		"AUTO_BRAND" => "AUTO_BRAND"
	),
	"PROFILE_DEST_SECTION_PROP_ID"=> 74,
	"PRODUCT_PROPERTIES"=> array(
		"CODE" => "CODE",
		"ORIGINAL_LINK" => "ORIGINAL_LINK",
		"AUTO_CALCULATED_MIN_PRICE"=>"AUTO_CALCULATED_MIN_PRICE",
		"AUTO_CALCULATED_MAX_PRICE"=>"AUTO_CALCULATED_MAX_PRICE",
		"SEARCH_WORDS"=>"SEARCH_WORDS",
		"AUTO_CALCULATED_SEARCH_WORDS"=>"AUTO_CALCULATED_SEARCH_WORDS",
		"FORCE_IMPORT_ACTIVITY"=>"FORCE_IMPORT_ACTIVITY",
		"IMPORT_PROFILE_ID"=>"IMPORT_PROFILE_ID",
		"IMPORT_TMP_PRICE"=>"IMPORT_TMP_PRICE",
		"IMPORT_TMP_PRICE_CURRENCY"=>"IMPORT_TMP_PRICE_CURRENCY",
		"IMPORT_FULL_DESCRIPTION" => "IMPORT_FULL_DESCRIPTION",
		"BRAND" => "BRAND"
	),
	"SECTION_PROPERTIES"=> array(
		"CURRENCY" => "UF_CURRENCY" // currency to convert prices to
	)
);

if ($_REQUEST['secret'] != $arSettings['SECRET']) {
	exit;	
}

$last_run = file_get_contents($arSettings["LAST_RUN_FILE_NAME"]);
if(!$_REQUEST['profile'] & ($last_run>(time()-$arSettings['IMPORT_INTERVAL']))) {
	?>
    <p>It's to early to begin import...</p>
    <?
	exit;	
}

define("LANG", "ru"); 
define("NO_KEEP_STATISTIC", true); 
define("NOT_CHECK_PERMISSIONS", true); 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

$START_TIME = time();

CModule::IncludeModule('iblock');
$obIblockElement = new CIBlockElement;
CModule::IncludeModule('catalog');
CModule::IncludeModule('currency');

// HELPERS
$LOG = "";
function log_data($message,$profile,$get,$put,$erase) {
	// profile = profile ID (if not provided - we only add data to log)
	// get (bool) = get current log from profile ($profile must be provided)
	// put (bool) = put log to profile ($profile must be provided)
	// erase (bool) = put log to profile ($profile must be provided)
	global $arSettings;
	global $LOG;
	if($profile) {
		if($get) {
			$arProfileLog = CIBlockElement::GetProperty($arSettings["PROFILES_IBLOCK_ID"], $profile, array("SORT",'ASC'), array("CODE"=>$arSettings['PROFILE_PROPERTIES']['LOG']))->Fetch();
			$LOG = $arProfileLog['VALUE']['TEXT'];
		}
		if($erase) {
			$LOG = "";
			CIBlockElement::SetPropertyValueCode($profile, $arSettings['PROFILE_PROPERTIES']['LOG'], array("VALUE"=>array("TEXT"=>"","TYPE"=>"HTML")));
		}
	}
	if($message) {
		$message = date("Y.m.d H:i:s") . ": " . $message;
		?>
        <p><?=$message;?></p>
        <?
		$LOG = $LOG . "\n\n" . $message;	
	}
	if($profile and $put) {
		CIBlockElement::SetPropertyValueCode($profile, $arSettings['PROFILE_PROPERTIES']['LOG'], array("VALUE"=>array("TEXT"=>$LOG,"TYPE"=>"HTML")));
		$LOG = "";
	}
}

// MAIN LOGICS

$last_profile = intval(file_get_contents($arSettings["LAST_PROFILE_FILE_NAME"]));
if(!$last_profile) {
	$last_profile = 0;
}
if($_REQUEST['profile']) {
	$last_profile = $_REQUEST['profile'];
}
$force_global_finish = false; // used when we force some profile to be imported - when operation is finished we mustn't refresh the page
$OFFSET = intval(file_get_contents($arSettings["OFFSET_FILE_NAME"])); // offset in import request query
if(!$OFFSET) {
	$OFFSET = 0;	
}
if(($OFFSET == 0) and !$_REQUEST['profile']) {
	// means we must select next profile
	$arCurrentProfile = CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings["PROFILES_IBLOCK_ID"], '>ID'=>$last_profile, 'ACTIVE'=>"Y", "ACTIVE_DATE"=>"Y", "INCLUDE_SUBSECTIONS"=>"Y"), false, array('nTopCount'=>1), array('ID','IBLOCK_ID','PROPERTY_*'))->GetNext();
	if($arCurrentProfile['ID']) {
		log_data("Let's begin with profile #" . $arCurrentProfile['ID'] . "...",$arCurrentProfile['ID'],false,false,true);
		CIBlockElement::SetPropertyValueCode($arCurrentProfile['ID'], $arSettings['PROFILE_PROPERTIES']['LAST_RUN'], date("d.m.Y H:i:s"));
		// deactivate all products, loaded by this profile in the past
		$obElements = CIBlockElement::GetList(array('ID'=>'DESC'), array('IBLOCK_ID'=>$arSettings["MAIN_IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", 'ACTIVE'=>"Y", "ACTIVE_DATE"=>"Y", "SECTION_ID"=>intval($arCurrentProfile["PROPERTY_".$arSettings["PROFILE_DEST_SECTION_PROP_ID"]]), "PROPERTY_".$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID']=>$arCurrentProfile['ID'], "PROPERTY_".$arSettings['PRODUCT_PROPERTIES']['FORCE_IMPORT_ACTIVITY']=>""), false, false, array('ID','IBLOCK_ID'));
		$counter = 0;
		while($arElement = $obElements->GetNext()) {
			$counter++;
			$obIblockElement->Update($arElement['ID'], array("ACTIVE" => "N"));
		}
		log_data($counter . " items deactivated",$arCurrentProfile['ID']);
	}
} else {
	// means we didn't finish with current profile
	$arCurrentProfile = CIBlockElement::GetList(array(), array('IBLOCK_ID'=>$arSettings["PROFILES_IBLOCK_ID"], 'ID'=>$last_profile), false,array('nTopCount'=>1), array('ID','IBLOCK_ID','PROPERTY_*'))->GetNext();
	log_data("Continue processing profile #" . $arCurrentProfile['ID'] . "...",$arCurrentProfile['ID'],true,false,false);
}

if($arCurrentProfile['ID']) {
	
	file_put_contents($arSettings["LAST_PROFILE_FILE_NAME"],"".$arCurrentProfile['ID']);
	
	// make good profile settings array
	$arCurrentProfileWithCodes = array(
		'ID' => $arCurrentProfile['ID']
	);
	$obProfileProps = CIBlockProperty::GetList(array(), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arSettings["PROFILES_IBLOCK_ID"]));
	while ($arProfileProp = $obProfileProps->GetNext()) {
		if($arCurrentProfile['PROPERTY_'.$arProfileProp['ID']] and strlen($arProfileProp['CODE'])) {
			$arCurrentProfileWithCodes[$arProfileProp['CODE']] = $arCurrentProfile['PROPERTY_'.$arProfileProp['ID']];
			if($arProfileProp['PROPERTY_TYPE']=='N') {
				$arCurrentProfileWithCodes[$arProfileProp['CODE']] = floatval($arCurrentProfile['PROPERTY_'.$arProfileProp['ID']]);	
			}
			if(in_array($arProfileProp['PROPERTY_TYPE'],array('G','E'))) {
				$arCurrentProfileWithCodes[$arProfileProp['CODE']] = intval($arCurrentProfile['PROPERTY_'.$arProfileProp['ID']]);	
			}
			if($arProfileProp['PROPERTY_TYPE'] == "L") {
				// for lists - set value to xml_id of it
				$arEnum = CIBlockProperty::GetPropertyEnum($arProfileProp['CODE'], array(), array("IBLOCK_ID"=>$arSettings["PROFILES_IBLOCK_ID"], "ID"=>$arCurrentProfile['PROPERTY_'.$arProfileProp['ID']]))->GetNext();
				$arCurrentProfileWithCodes[$arProfileProp['CODE']] = $arEnum['XML_ID'];	
			}
		}
	}
	$arCurrentProfile = $arCurrentProfileWithCodes;
	
	// if we have to set brand automatically - we must prepare array containing all search words
	if($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['AUTO_BRAND']]) {
		$arBrands = array();
		$obBrands = CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings["BRANDS_IBLOCK_ID"], 'ACTIVE'=>"Y", "ACTIVE_DATE"=>"Y",'!PROPERTY_' . $arSettings["BRANDS_IMPORT_PRODUCT_WORDS_PROP"] => false), false, false, array('ID','IBLOCK_ID','PROPERTY_' . $arSettings["BRANDS_IMPORT_PRODUCT_WORDS_PROP"]));
		while($arBrand = $obBrands->GetNext()) {
			$arBrands[$arBrand['ID']] = array();
			foreach($arBrand['PROPERTY_'.$arSettings["BRANDS_IMPORT_PRODUCT_WORDS_PROP"]."_VALUE"] as $v) {
				$arBrands[$arBrand['ID']][] = $v; 
			}
		}
	}
	
	$search_str = "<SearchItemsParameters>";
	foreach($arCurrentProfile as $k=>$v) {
		if(strlen($v) and preg_match('/' . $arSettings["PROFILE_PROPERTIES"]['SETTINGS'] . '/',$k)) {
			$tag = preg_replace('/' . $arSettings["PROFILE_PROPERTIES"]['SETTINGS'] . '/','',$k);
			$search_str .= "<" . $tag . ">" . $v . "</" . $tag . ">";	
		}	
	}
	$search_str .= "<Features>";
	foreach($arCurrentProfile as $k=>$v) {
		if(strlen($v) and preg_match('/' . $arSettings["PROFILE_PROPERTIES"]['FEATURES'] . '/',$k)) {
			$feature = preg_replace('/' . $arSettings["PROFILE_PROPERTIES"]['FEATURES'] . '/','',$k);
			$search_str .= "<Feature Name='" . $feature . "'>" . $v . "</Feature>";	
		}	
	}
	$search_str .= "</Features>";
	$search_str .= "<IsClearItemTitles>false</IsClearItemTitles>";
	$search_str .= "</SearchItemsParameters>";
	
	log_data("Search params: " . $search_str . "; offset: " . $OFFSET,$arCurrentProfile['ID']);
	
	$curl = curl_init();
	$general_error = false;
	curl_setopt($curl, CURLOPT_URL, $arSettings["WEBSERVICE"] . "&framePosition=" . $OFFSET . "&frameSize=" . $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['ONE_TIME_QUANTITY']] . "&xmlParameters=" . $search_str);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	 
	$result = curl_exec($curl);
	if ($result === FALSE) {
		$general_error = true;
		log_data("cURL Error: " . curl_error($curl));
	}
	$xmlObject = simplexml_load_string($result);
	 
	curl_close($curl);
	 
	if ((string)$xmlObject->ErrorCode !== 'Ok') {
		$general_error = true;
		log_data("Error: " . $xmlObject->ErrorDescription);
	}
	
	if(!$general_error) {
		$arSectionFields = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>$arSettings['MAIN_IBLOCK_ID'], "ID" => $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['DEST_SECTION']]), false, array("ID", "IBLOCK_ID", "UF_*"))->GetNext();
		$rsEnum = CUserFieldEnum::GetList(array(), array("ID"=>$arSectionFields[$arSettings['SECTION_PROPERTIES']['CURRENCY']])); 
		$arEnum = $rsEnum->GetNext();
		$arSectionFields[$arSettings['SECTION_PROPERTIES']['CURRENCY']] = $arEnum["VALUE"];
		$total_items_loaded = $OFFSET;
		$items_loaded_this_time = 0; // really imported items
		$items_watched_this_time = 0; // all items (some of them may not be imported)
		foreach($xmlObject->Result->Items->Items->Content->Item as $arItem) {
			$items_watched_this_time++;
			if(($arItem->IsSellAllowed == 'true') and ($arItem->HasError == 'false') and ($arItem->ErrorCode == 'Ok')) {
				$total_items_loaded++;
				$items_loaded_this_time++;
				if($total_items_loaded<=$arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['MAX_QUANTITY']]) {
					$arCurrentProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID'=>$arSettings["MAIN_IBLOCK_ID"], 'SECTION_ID'=>$arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['DEST_SECTION']], 'XML_ID'=>$arItem->Id, "INCLUDE_SUBSECTIONS"=>"Y"), false,array('nTopCount'=>1), array('ID','IBLOCK_ID','PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_TMP_PRICE'],'PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID'],'PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_FULL_DESCRIPTION']))->GetNext();
					if(!$arCurrentProduct['ID'] or ((intval($arCurrentProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID'].'_VALUE']) != $arCurrentProfile['ID']) and $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['IMPORTANT']]) or ((intval($arCurrentProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_TMP_PRICE'].'_VALUE'])!=intval($arItem->Price->OriginalPrice)) and !$arCurrentProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_FULL_DESCRIPTION'].'_VALUE'] and (intval($arCurrentProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID'].'_VALUE']) == $arCurrentProfile['ID']))) {
						$arFieldsUpdate=array(
							"ACTIVE" => "Y",
							"XML_ID" => trim($arItem->Id),
							"IBLOCK_SECTION" => $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['DEST_SECTION']]
						);
						//if(!$arCurrentProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_FULL_DESCRIPTION']]) {
						if(!$arCurrentProduct['ID']) {
							if($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['FIXED_PRODUCT_NAME']]) {
								$arFieldsUpdate['NAME'] = $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['FIXED_PRODUCT_NAME']];
							} else {
								$arFieldsUpdate['NAME'] = trim($arItem->Title);
								if(!$arFieldsUpdate['NAME']) {
									$arFieldsUpdate['NAME'] = trim($arItem->OriginalTitle);
								}
								if(count($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['CLEAR_NAME']])) {
									foreach($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['CLEAR_NAME']] as $v) {
										$arFieldsUpdate['NAME'] = preg_replace('/' . $v . '/i','',$arFieldsUpdate['NAME']);
									}
								}
								if($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['CLEAR_NAME_BRACKETS']]) {
									$arFieldsUpdate['NAME'] = preg_replace('/\[.+\]/U','',$arFieldsUpdate['NAME']);	
								}
							}
							$picture = trim($arItem->MainPictureUrl);
							$arPictures = array();
							foreach($arItem->Pictures->ItemPicture as $arPicture) {
								$arPictures[] = trim($arPicture->Url);
							}
							
							if($arPictures[0]) {
								$picture = $arPictures[0];
							}
							if($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['SKIP_FIRST_PICTURE']] and $arPictures[1]) {
								$picture = $arPictures[1];	
							}
							$arFieldsUpdate["PREVIEW_PICTURE"] = CFile::MakeFileArray($picture);
						}
						$orig_price = intval(trim($arItem->Price->OriginalPrice));
						if(intval(trim($arItem->PromotionPrice->OriginalPrice))) {
							$orig_price = intval(trim($arItem->PromotionPrice->OriginalPrice));
						} else {
							// reduce price
							if((floatval($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['PRICE_REDUCE']])>0) and (floatval($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['PRICE_REDUCE']])<1)) {
								$orig_price = $orig_price*floatval($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['PRICE_REDUCE']]);
							}
						}
						// add profit
						$orig_price = $orig_price + $orig_price*intval($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['PROFIT']])/100;
						$tmp_price = CCurrencyRates::ConvertCurrency($orig_price, trim($arItem->Price->OriginalCurrencyCode), $arSectionFields[$arSettings['SECTION_PROPERTIES']['CURRENCY']]);
						$arPropsUpdate = array(
							"AUTO_CALCULATED_MIN_PRICE"=>$tmp_price,
							"AUTO_CALCULATED_MAX_PRICE"=>$tmp_price,
							"IMPORT_PROFILE_ID"=>$arCurrentProfile['ID'],
							"IMPORT_TMP_PRICE"=>$orig_price,
							"IMPORT_TMP_PRICE_CURRENCY"=>trim($arItem->Price->OriginalCurrencyCode)
						);
						
						$product_id = false;
						if(!$arCurrentProduct['ID']) {
							$arFieldsUpdate['IBLOCK_ID'] = $arSettings["MAIN_IBLOCK_ID"];
							$arFieldsUpdate['CODE'] = time() . "_" . trim($arItem->Id);
							$product_id = $obIblockElement->Add($arFieldsUpdate);
							log_data('New product ' . $arFieldsUpdate['NAME'] . ' imported! ID=' . $product_id);
							$arPropsUpdate = array_merge($arPropsUpdate, array(
								$arSettings["PRODUCT_PROPERTIES"]["CODE"] => trim($arItem->Id),
								$arSettings["PRODUCT_PROPERTIES"]["ORIGINAL_LINK"] => trim($arItem->TaobaoItemUrl),
								$arSettings["PRODUCT_PROPERTIES"]["IMPORT_PROFILE_ID"]=>$arCurrentProfile['ID'],
								$arSettings["PRODUCT_PROPERTIES"]["SEARCH_WORDS"]=>$arCurrentProfile['ID'],
								$arSettings["PRODUCT_PROPERTIES"]["AUTO_CALCULATED_SEARCH_WORDS"]=>trim($arItem->Id) . ' ' . $arFieldsUpdate['NAME'] . ' ' . trim($arItem->TaobaoItemUrl) . ' ' . str_replace('-',' ',str_replace(array('"','\'',',','.'),'',str_replace(array('<','>','$'),'',trim($arItem->TaobaoItemUrl)))) . ' ' . $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['SEARCH_WORDS']]['TEXT']
							));
							// additional settings for new item
							foreach($arCurrentProfile as $k=>$v) {
								if(strlen($v)) {
									if(preg_match('/' . $arSettings["PROFILE_PROPERTIES"]['SET'] . '/',$k)) {
										$prop_code = preg_replace('/' . $arSettings["PROFILE_PROPERTIES"]['SET'] . '/','',$k);
										$arPropsUpdate[$prop_code] = $v;
									}
									if(preg_match('/' . $arSettings["PROFILE_PROPERTIES"]['TIME'] . '/',$k)) {
										$prop_code = preg_replace('/' . $arSettings["PROFILE_PROPERTIES"]['TIME'] . '/','',$k);
										$arPropsUpdate[$prop_code] = date("d.m.Y H:i:s",time()+($v*60*60*24));
									}
								}
							}
							if($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['AUTO_BRAND']]) {
								// try to set brand
								foreach($arBrands as $brand_id=>$arBrandNames) {
									foreach($arBrandNames as $brand_name) {
										if(preg_match('/' . $brand_name . '/i',(string)$arItem->Title) or preg_match('/' . $brand_name . '/i',(string)$arItem->OriginalTitle))	 {
											$arPropsUpdate[$arSettings["PRODUCT_PROPERTIES"]['BRAND']] = $brand_id;	
										}
									}	
								}
							}
							if($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['SEARCH_WORDS']]) {
								$arPropsUpdate[$arSettings["PRODUCT_PROPERTIES"]['SEARCH_WORDS']] = array('VALUE'=>$arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['SEARCH_WORDS']]);	
							}
						} else {
							$product_id = $arCurrentProduct['ID'];	
							$obIblockElement->Update($arCurrentProduct['ID'], $arFieldsUpdate);
							log_data('Updating product #' . $arCurrentProduct['ID'] . " " . $arFieldsUpdate['NAME'] . '...');
						}
						CIBlockElement::SetPropertyValuesEx($product_id, $arSettings["MAIN_IBLOCK_ID"], $arPropsUpdate);
					} elseif($arCurrentProduct['ID'] and (((intval($arCurrentProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID']]) != $arCurrentProfile['ID']) and $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['IMPORTANT']]) or (intval($arCurrentProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID']]) == $arCurrentProfile['ID']))) {
						// only activate item
						$obIblockElement->Update($arCurrentProduct['ID'], array("ACTIVE" => "Y"));
					}
					if (time()>$START_TIME+$arSettings['TIME_LIMIT']) {
						file_put_contents($arSettings["OFFSET_FILE_NAME"],"".$total_items_loaded);
						log_data('Imported ' . $items_loaded_this_time . ' items. Time is up. Relax...',$arCurrentProfile['ID'],false,true,false);
						break 1;	
					}
				} else {
					// max items amount reached - next time we must start with next profile
					file_put_contents($arSettings["OFFSET_FILE_NAME"],"0");
					autoCalculateCatalogSectionFields($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['DEST_SECTION']],true); // see init.php - update prices range for section filter
					log_data('Finished! Total ' . ($total_items_loaded-1) . ' items imported.',$arCurrentProfile['ID'],false,true,false);
					if($_REQUEST['profile']) {
						$force_global_finish = true;
					}
					break 1;	
				}
				usleep($arSettings['SLEEP']);
			}
			if($items_watched_this_time == $arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['ONE_TIME_QUANTITY']]) {
				file_put_contents($arSettings["OFFSET_FILE_NAME"],"".$total_items_loaded);
				log_data('Imported ' . $items_loaded_this_time . ' items. One operation limit reached. Relax...',$arCurrentProfile['ID'],false,true,false);
				break 1;
			}
		}
		if(!$items_loaded_this_time) {
			// it can happen when taobao couldn't provide more items, but we haven't reached max amount yet
			file_put_contents($arSettings["OFFSET_FILE_NAME"],"0"); // next time work on next profile
			autoCalculateCatalogSectionFields($arCurrentProfile[$arSettings["PROFILE_PROPERTIES"]['DEST_SECTION']],true); // see init.php - update prices range for section filter
			log_data('Finished! Note: nothing imported this time...',$arCurrentProfile['ID'],true,true,false);	
			if($_REQUEST['profile']) {
				$force_global_finish = true;
			}
		} else {
			if($total_items_loaded > $OFFSET+1) {
				file_put_contents($arSettings["OFFSET_FILE_NAME"],"".$total_items_loaded);	
			} else {
				// something wrong happened - go to next profile
				file_put_contents($arSettings["OFFSET_FILE_NAME"],"0");		
			}
		}
	}
	
	?>
    <?if($force_global_finish and $_REQUEST['profile']):?>
    	<?
		file_put_contents($arSettings["LAST_PROFILE_FILE_NAME"],"0");
		?>
    	<p>************************************************</p>
		<p><?=date("Y.m.d H:i:s");?> IMPORT OF PROFILE #<?=$_REQUEST['profile']?> IS COMPLETED! CONGRATS!</p>
        <script type="text/javascript">
		alert("<?=date("Y.m.d H:i:s");?> IMPORT OF PROFILE #<?=$_REQUEST['profile']?> IS COMPLETED! CONGRATS!");
		</script>
    <?else:?>
        <p>************************************************</p>
        <p>Page will be refreshed automatically in <?=$arSettings['TIMEOUT']/1000?> seconds. <b>Don't close browser window</b> until operation is fully completed!!!</p>
        <script type="text/javascript">
            setTimeout("location.reload(true);",<?=$arSettings['TIMEOUT']?>);
        </script>
    <?endif;?>
	<?
	exit;

} else {
	
	file_put_contents($arSettings["LAST_PROFILE_FILE_NAME"],"0");
	file_put_contents($arSettings["OFFSET_FILE_NAME"],"0");
	file_put_contents($arSettings["LAST_RUN_FILE_NAME"],time());
	?>
	<p>************************************************</p>
	<p><?=date("Y.m.d H:i:s");?> OPERATION IS FULLY COMPLETED! CONGRATS!</p>
	<script type="text/javascript">
	alert("<?=date("Y.m.d H:i:s");?> OPERATION IS FULLY COMPLETED! CONGRATS!");
	</script>
	<?
	
}
?>