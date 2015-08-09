<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="brands">
    <ul class="small-block-grid-2 medium-block-grid-4 large-block-grid-6 small-text-center ajax_paged"> <!--.ajax_paged is used in pager template-->
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
        ?>
        <li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                <img src="<?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arItem["PREVIEW_PICTURE"]["SRC"] . '&w=250&h=150&far=C&f=png');?>" />
                <div class="gap5"></div><div class="gap5"></div>
				<?=$arItem['NAME']?>
            </a>
        </li>
    <?endforeach;?>
    </ul>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <?=$arResult["NAV_STRING"];?>
    <?endif;?>
</div>