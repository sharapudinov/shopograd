    <?
	global $APPLICATION;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$templateFolder = '/bitrix/templates/shopograd/components/bitrix/breadcrumb/.default';
// not show last item
unset($arResult[count($arResult)-1]);

$arResult = array_reverse($arResult);

//delayed function must return a string
if(empty($arResult))
	return "";
	
$strReturn = '<ul class="breadcrumb"  itemscope itemtype="http://schema.org/BreadcrumbList">
                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{
	if($index>0) {
		$strReturn .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" style="opacity:'.(1-$index*0.25).';">';
	}
	$class=($index==0)?'class="first"':'';
	$strReturn .= '<img '.$class.' src="' . $templateFolder . '/img/arr.png" /> ';
	
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	
	if($arResult[$index]["LINK"] <> "") 
		$strReturn .= ' <a href="'.$arResult[$index]["LINK"].'" itemprop="item"><span itemprop="name">'.$title.'</span> </a>';
	else
		$strReturn .= '<span itemprop="name"> ' . $title . ' </span>';

    $strReturn .= '<meta itemprop="position" content="'.($itemSize-$index-1).'" /></li>';

}
    $strReturn.='</ul>';

return $strReturn;
?>