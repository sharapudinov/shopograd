<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="index_page_news ajax_paged"> <!--.ajax_paged is used in pager template-->
	<?foreach($arResult["ITEMS"] as $arItem):?>
    	<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
		?>
        <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
            <?if(strlen($arItem['PREVIEW_TEXT'])):?>
            <div class="gap5"></div>
				<?=$arItem['PREVIEW_TEXT']?>
            <div class="gap5"></div>
            <?endif;?>
            <div class="gap5"></div>
        </div>
	<?endforeach;?>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"];?>
<?endif;?>