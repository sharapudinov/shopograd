<?
CModule::IncludeModule("currency");
$arFilterAndSorting = array(
	'ITEMS_FILTER'=>array(),
	'ALL_SORT_TYPES' => array(
		1 => "популярности",
		2 => "новизне",
		3 => "алфавиту",
		4 => "цене",
		5 => "кол-ву отзывов",
	),
	'ALL_ORDER_TYPES' => array(
		1 => "возрастанию",
		2 => "убыванию",
	),
);
if(intval(trim($_REQUEST['MIN_PRICE']))>0) {
	$arFilterAndSorting['MIN_PRICE'] = intval(trim($_REQUEST['MIN_PRICE']));
	$arFilterAndSorting['ITEMS_FILTER'][] = array("LOGIC" => "OR", array('>=PROPERTY_AUTO_CALCULATED_MIN_PRICE'=>CCurrencyRates::ConvertCurrency(intval(trim($_REQUEST['MIN_PRICE'])), _CURRENCY, str_replace('>','',str_replace('<','',trim(urldecode($_REQUEST['SECTION_CURRENCY'])))))), array('>=PROPERTY_AUTO_CALCULATED_MAX_PRICE'=>CCurrencyRates::ConvertCurrency(intval(trim($_REQUEST['MIN_PRICE'])), _CURRENCY, str_replace('>','',str_replace('<','',trim(urldecode($_REQUEST['SECTION_CURRENCY'])))))));
	//$arFilterAndSorting['ITEMS_FILTER']['>=PROPERTY_AUTO_CALCULATED_MIN_PRICE'] = CCurrencyRates::ConvertCurrency(intval(trim($_REQUEST['MIN_PRICE'])), _CURRENCY, str_replace('>','',str_replace('<','',trim(urldecode($_REQUEST['SECTION_CURRENCY'])))));	
}
if(intval(trim($_REQUEST['MAX_PRICE']))>0) {
	$arFilterAndSorting['MAX_PRICE'] = intval(trim($_REQUEST['MAX_PRICE']));
	$arFilterAndSorting['ITEMS_FILTER'][] = array("LOGIC" => "OR", array('<=PROPERTY_AUTO_CALCULATED_MAX_PRICE'=>CCurrencyRates::ConvertCurrency(intval(trim($_REQUEST['MAX_PRICE'])), _CURRENCY, str_replace('>','',str_replace('<','',trim(urldecode($_REQUEST['SECTION_CURRENCY'])))))), array('<=PROPERTY_AUTO_CALCULATED_MIN_PRICE'=>CCurrencyRates::ConvertCurrency(intval(trim($_REQUEST['MAX_PRICE'])), _CURRENCY, str_replace('>','',str_replace('<','',trim(urldecode($_REQUEST['SECTION_CURRENCY'])))))));
	//$arFilterAndSorting['ITEMS_FILTER']['<=PROPERTY_AUTO_CALCULATED_MAX_PRICE'] = CCurrencyRates::ConvertCurrency(intval(trim($_REQUEST['MAX_PRICE'])), _CURRENCY, str_replace('>','',str_replace('<','',trim(urldecode($_REQUEST['SECTION_CURRENCY'])))));	
}
for($i=1;$i<=5;$i++) {
	if($_REQUEST['FILTER_FIELD_' . $i]) {
		$arFilterAndSorting['FILTER_FIELD_' . $i] = str_replace('>','',str_replace('<','',trim(urldecode($_REQUEST['FILTER_FIELD_' . $i]))));
		$arFilterAndSorting['ITEMS_FILTER']['PROPERTY_FILTER_FIELD_' . $i] = $arFilterAndSorting['FILTER_FIELD_' . $i];
	}
}
if((intval(trim($_REQUEST['SORT']))>0) and (intval(trim($_REQUEST['SORT']))<=5)) {
	$arFilterAndSorting['SORT_TYPE'] = intval(trim($_REQUEST['SORT']));
	switch ($arFilterAndSorting['SORT_TYPE']) {
    	case 1:
			// 1 = by "popularity"
			$arFilterAndSorting['SORT'] = 'SORT';
			break;
    	case 2:
			// 2 = by newness
			$arFilterAndSorting['SORT'] = 'ID';
			break;
		case 3:
			// 3 = by name
			$arFilterAndSorting['SORT'] = 'NAME';
			break;
		case 4:
			// 4 = by price
			$arFilterAndSorting['SORT'] = 'PROPERTY_AUTO_CALCULATED_MIN_PRICE';
			break;
		case 5:
			// 5 = by reviews amount
			$arFilterAndSorting['SORT'] = 'PROPERTY_AUTO_CALCULATED_REVIEWS_AMOUNT';
			break;
	}
} else {
	$arFilterAndSorting['SORT_TYPE'] = 1;	
	$arFilterAndSorting['SORT'] = "SORT";
}
if((intval(trim($_REQUEST['ORDER']))>0) and (intval(trim($_REQUEST['ORDER']))<=2)) {
	$arFilterAndSorting['ORDER_TYPE'] = intval(trim($_REQUEST['ORDER']));
	if($arFilterAndSorting['SORT_TYPE'] == 1) {
		if($arFilterAndSorting['ORDER_TYPE'] == 1)	{
			$arFilterAndSorting['ORDER'] = "DESC";	
		} else {
			$arFilterAndSorting['ORDER'] = "ASC";	
		}
	} else {
		if($arFilterAndSorting['ORDER_TYPE'] == 1)	{
			$arFilterAndSorting['ORDER'] = "ASC";	
		} else {
			$arFilterAndSorting['ORDER'] = "DESC";	
		}	
	}
} else {
	$arFilterAndSorting['ORDER_TYPE'] = 2;	
	$arFilterAndSorting['ORDER'] = "ASC";	
}
?>