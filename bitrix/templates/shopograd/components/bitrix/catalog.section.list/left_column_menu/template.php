<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="show-for-large-up">
    <div class="side_menu">
    	<?foreach($arResult["SECTIONS"] as $arSection):?>
        	<?
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => "Really?"));
			?>
        	<a href="<?=$arSection["SECTION_PAGE_URL"]?>"<?if($arSection["ID"] == $arParams['_CURRENT']):?> class="current"<?endif;?> id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$arSection["NAME"]?></a>
        <?endforeach;?>
    </div>
</div>
<div class="hide-for-large-up">
    <select class="custom" id="side_menu">
    	<option value="">— Выберите категорию —</option>
        <?foreach($arResult["SECTIONS"] as $arSection):?>
        <option value="<?=$arSection["SECTION_PAGE_URL"]?>"<?if($arSection["ID"] == $arParams['_CURRENT']):?> selected<?endif;?>><?=$arSection["NAME"]?></option>
        <?endforeach;?>
    </select>
</div>