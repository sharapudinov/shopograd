<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
function PrintLevel($arItems, $APPLICATION, $COMPONENT, $arParams) {
	foreach($arItems as $arItem) {
		$COMPONENT->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT"));
		$COMPONENT->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => 'Really?'));
		?>
        <li<?if(count($arItem['CHILD'])):?> class="icon icon-arrow-left"<?endif;?>>
            <a href="<?=$arItem['SECTION_PAGE_URL']?>" id="<?=$COMPONENT->GetEditAreaId($arItem['ID']);?>"><?=$arItem['NAME']?></a>
            <?if(count($arItem['CHILD'])):?>
            <div class="mp-level">
                <h2 class="icon icon-arrow-right"><?=$arItem['NAME']?></h2>
                <ul>
                    <?PrintLevel($arItem['CHILD'], $APPLICATION, $COMPONENT, $arParams);?>
                </ul>
            </div>
            <?endif;?>
        </li>
        <?
	}	
}
?>
<ul>
	<?PrintLevel($arResult['_STRUCTURED_SECTIONS']['ROOT']['CHILD'], $APPLICATION, $this, $arParams);?>
</ul>