<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="index_page_reviews">
    <ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-3">
    	<?foreach($arResult["ITEMS"] as $k=>$arItem):?>
        	<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
			?>
            <li<?if($k==3):?> class="show-for-medium-only"<?endif;?>>
                <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <div class="review">
                        <?=$arItem["PREVIEW_TEXT"];?>
                        <?if(strlen($arItem["DETAIL_TEXT"])):?>
                        &nbsp;
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">подробнее</a>
                        <?endif;?>
                        <div class="tail"></div>
                    </div>
                    <div class="descr">
                    	<?
						$arUser = CUser::GetByID($arItem['DISPLAY_PROPERTIES']['USER']['VALUE'])->Fetch();
						?>
                        <?=$arUser['NAME']?> о <?if(strlen($arItem['DISPLAY_PROPERTIES']['PRODUCT']['DISPLAY_VALUE'])):?>товаре <?=$arItem['DISPLAY_PROPERTIES']['PRODUCT']['DISPLAY_VALUE']?><?elseif(strlen($arItem['DISPLAY_PROPERTIES']['PRODUCT_NAME']['DISPLAY_VALUE'])):?>товаре <?=$arItem['DISPLAY_PROPERTIES']['PRODUCT_NAME']['DISPLAY_VALUE']?><?else:?>магазине Шопоград<?endif;?>
                    </div>
                </div>
            </li>
        <?endforeach;?>
    </ul>
</div>