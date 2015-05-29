<?
/*
SEARCH TYPES
1 = search by phrase
2 = search by brand
3 = search new products
4 = search special offers
5 = search products with reviews
*/
$GLOBAL_SEARCH_TYPE = false; // used to pass param to the deeper sections
$GLOBAL_SEARCH_CONDITION = false; // used to pass param to the deeper sections
$GLOBAL_SEARCH_FILTER = array(); // contains ready elements filter
if($_REQUEST['GLOBAL_SEARCH_TYPE'] and $_REQUEST['GLOBAL_SEARCH_CONDITION']) {
	if(intval($_REQUEST['GLOBAL_SEARCH_TYPE']) == 1) {
		$GLOBAL_SEARCH_TYPE = intval($_REQUEST['GLOBAL_SEARCH_TYPE']);
		$GLOBAL_SEARCH_CONDITION = str_replace(array('<','>','$'),'',urldecode($_REQUEST['GLOBAL_SEARCH_CONDITION']));
		//$arWords = explode('~', preg_replace('/\s+/','~',preg_replace('/[^\d\s\w]/','',urldecode($_REQUEST['GLOBAL_SEARCH_CONDITION']))));
		$str = $GLOBAL_SEARCH_CONDITION;
		$str = str_replace(array('"','\'',',','.'),'',$str);
		$str = str_replace('-',' ',$str);
		$str = str_replace(' ','~',$str);
		$arWords = explode('~',$str);
		$arFinalWords = array();
		foreach($arWords as $k=>$v) {
			if(strlen(trim($v))>1) {
				$arFinalWords[] = trim($v);
			}
		}
		$GLOBAL_SEARCH_FILTER = array('%PROPERTY_AUTO_CALCULATED_SEARCH_WORDS'=>$arFinalWords);
		if(count($arFinalWords) and (strlen($_REQUEST['GLOBAL_SEARCH_CONDITION'])>1)) {
			?>
            <p>Показаны товары с фразой <b>&laquo;<?=htmlspecialchars($GLOBAL_SEARCH_CONDITION)?>&raquo;</b> &nbsp; <a href="<?=$APPLICATION->GetCurPageParam("", array("GLOBAL_SEARCH_TYPE", "GLOBAL_SEARCH_CONDITION"));?>">отменить</a></p>
            <div class="gap20"></div>
            <?
		} else {
			unset($GLOBAL_SEARCH_TYPE);
			unset($GLOBAL_SEARCH_CONDITION);
			unset($GLOBAL_SEARCH_FILTER);
		}
	} elseif (intval($_REQUEST['GLOBAL_SEARCH_TYPE']) == 2) {
		$arBrands = array();
		$obBrandsDataCache = new CPHPCache;
		if($obBrandsDataCache->InitCache(86400, "BrandsDataCache", '/custom_cache/')) {
			$arBrands = $obBrandsDataCache->GetVars();
		} else {
			$arBrands = array();
			$obBrandsDataCache->StartDataCache(86400, "BrandsDataCache", '/custom_cache/');
			CModule::IncludeModule("iblock");
			$obBrands = CIBlockElement::GetList(array("SORT"=>"ASC"), array('ACTIVE'=>'Y', 'IBLOCK_ID'=>11), false, false, array('ID', 'NAME', 'DETAIL_PAGE_URL'));
			while($arBrand = $obBrands->GetNext()) {
				$arBrands[$arBrand['ID']] = $arBrand;	
			}
			$obBrandsDataCache->EndDataCache($arBrands);
		}
		if(count($arBrands)) {
			$brand = intval(trim($_REQUEST['GLOBAL_SEARCH_CONDITION']));
			if(array_key_exists($brand,$arBrands)) {
				?>
                <p>Показаны товары бренда <b><a href="<?=$arBrands[$brand]['DETAIL_PAGE_URL']?>" style="color:#000; text-decoration:none;">&laquo;<?=$arBrands[$brand]['NAME']?>&raquo;</a></b> &nbsp; <a href="<?=$APPLICATION->GetCurPageParam("", array("GLOBAL_SEARCH_TYPE", "GLOBAL_SEARCH_CONDITION"));?>">отменить</a></p>
                <div class="gap20"></div>
                <?	
				$GLOBAL_SEARCH_FILTER = array('PROPERTY_BRAND'=>$brand);
				$GLOBAL_SEARCH_TYPE = intval($_REQUEST['GLOBAL_SEARCH_TYPE']);
				$GLOBAL_SEARCH_CONDITION = $brand;
			}	
		}
	}
} elseif($arParams['_GLOBAL_SEARCH_TYPE']) {
	if($arParams['_GLOBAL_SEARCH_TYPE'] == 3) {
		$GLOBAL_SEARCH_FILTER = array(">PROPERTY_NEW_TILL" => date("Y-m-d H:i:s"));	
	}
	if($arParams['_GLOBAL_SEARCH_TYPE'] == 4) {
		$GLOBAL_SEARCH_FILTER = array(">PROPERTY_SPECIAL_OFFER_TILL" => date("Y-m-d H:i:s"));	
	}
	if($arParams['_GLOBAL_SEARCH_TYPE'] == 5) {
		$GLOBAL_SEARCH_FILTER = array(">=PROPERTY_AUTO_CALCULATED_REVIEWS_AMOUNT" => 1);	
	}
}
?>