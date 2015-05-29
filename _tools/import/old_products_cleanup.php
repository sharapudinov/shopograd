<?
// USAGE: Call www.site.ru/_tools/import/old_products_cleanup.php?secret=cleanup from your browser

// SETTINGS
$arSettings = array(
	"TIMEOUT" => 2000, // 2 seconds pause before next run on JS
	"SECRET" => 'cleanup', // secret word to prevent unallowed runs
	"MAIN_IBLOCK_ID" => 1,
	"OFFERS_IBLOCK_ID" => 2,
	"DELETE_AFTER_PROP" => "DELETE_AFTER",
	"OFFERS_LINK_PROP" => "CML2_LINK",
	"LAST_ITEM_FILE_NAME" => "old_products_cleanup_last_item.txt" // stores the number of proceeded elements
);

if ($_REQUEST['secret'] != $arSettings['SECRET']) {
	exit;	
}

define("LANG", "ru"); 
define("NO_KEEP_STATISTIC", true); 
define("NOT_CHECK_PERMISSIONS", true); 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

// MAIN LOGICS

$last_item = intval(file_get_contents($arSettings["LAST_ITEM_FILE_NAME"]));
if(!$last_item) {
	$last_item = 0;
}

$obElements = CIBlockElement::GetList(
	array('ID'=>'ASC'),
    array(
    	'IBLOCK_ID'=>$arSettings["MAIN_IBLOCK_ID"],
    	"INCLUDE_SUBSECTIONS"=>"Y",
        array(
            "LOGIC" => "OR",
            array("<PROPERTY_".$arSettings['DELETE_AFTER_PROP']=>date("Y-m-d")),
            array("ACTIVE" => "N"),
        ),
        ">ID"=>$last_item
	), 
    false,
    array('nTopCount'=>50),
    array('ID','IBLOCK_ID', 'ACTIVE', 'PROPERTY_'.$arSettings['DELETE_AFTER_PROP'])
);
$counter = 0;
while($arElement = $obElements->GetNext()) {
	$counter++;
	?>
    <p>Deleting item ID=<?=$arElement['ID']?> (ACTIVE=<?=$arElement['ACTIVE']?>, DELETE_DEADLINE=<?=$arElement['PROPERTY_'.$arSettings['DELETE_AFTER_PROP'].'_VALUE']?>)</p>
    <?
	// check for product offers
	$obOffers = CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings["OFFERS_IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", "PROPERTY_".$arSettings['OFFERS_LINK_PROP']=>$arElement['ID']), false, false, array('ID','IBLOCK_ID'));
	while($arOffer = $obOffers->GetNext()) {
		CIBlockElement::Delete($arOffer['ID']);	
		?>
        <p>&mdash; Offer ID=<?=$arOffer['ID']?> was also deleted.</p>
        <?
	}
	CIBlockElement::Delete($arElement['ID']);	
	$last_item = $arElement['ID'];
}
if(!$counter or ($counter<50)) {
	$last_item = 0;
}
file_put_contents($arSettings["LAST_ITEM_FILE_NAME"],$last_item);
?>
<p>************************************************</p>
<?if(!$last_item):?>
<p>OPERATION FINISHED! SEE YOU SOON!</p>
<script type="text/javascript">
	alert('OPERATION FINISHED! SEE YOU SOON!');
</script>
<?else:?>
<p>Page will be refreshed automatically in <?=$arSettings['TIMEOUT']/1000?> seconds. <b>Don't close browser window</b> until operation is fully completed!!!</p>
<script type="text/javascript">
	setTimeout("location.reload(true);",<?=$arSettings['TIMEOUT']?>);
</script>
<?endif;?>