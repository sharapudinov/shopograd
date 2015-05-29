<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$templateFolder = '/bitrix/templates/shopograd/components/bitrix/breadcrumb/.default';
// not show last item
unset($arResult[count($arResult)-1]);

$arResult = array_reverse($arResult);

//delayed function must return a string
if(empty($arResult))
	return "";
	
$strReturn = '<span class="breadcrumb">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{
	if($index>0) {
		$strReturn .= '<span>';		
	}
	
	$strReturn .= '<img src="' . $templateFolder . '/img/arr.png" /> ';	
	
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	
	if($arResult[$index]["LINK"] <> "") 
		$strReturn .= ' <a href="'.$arResult[$index]["LINK"].'">'.$title.'</a> ';
	else
		$strReturn .= ' ' . $title . ' ';
		
}
for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++) {
	$strReturn .= '</span>';
}

return $strReturn;
?>