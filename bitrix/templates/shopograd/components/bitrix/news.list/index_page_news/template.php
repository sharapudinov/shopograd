<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="index_page_news">
	<?foreach($arResult["ITEMS"] as $arItem):?>
    	<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
		?>
        <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
        </div>
	<?endforeach;?>
</div>