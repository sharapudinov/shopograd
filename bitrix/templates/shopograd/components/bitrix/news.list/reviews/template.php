<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"])):?>
	<style>
    @media only screen and (min-width: 641px) {
        .reviews {
            margin-left:-20px;
            margin-right:-20px;
        }
        .reviews .item {
            margin-left:20px;
            margin-right:20px;
        }
    }
    .reviews .item .review {
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        background:#EEE;
        padding:20px;
        padding-top:15px;
        padding-bottom:15px;
        font-size:12px;
        color:#000;
        position:relative;
    }
	.reviews .item .review .moderation {
		font-size:10px;
		padding-top:5px;
		color:#999;	
	}
    .reviews .item .review .tail {
        position:absolute;
        left:0px;
        bottom:-18px;
        width:18px;
        height:25px;
        background:url(<?=$templateFolder;?>/img/tail.png);	
    }
    .reviews .item .descr {
        margin-left:30px;
        font-size:12px;
        color:#999;
        padding-top:10px;
    }
    </style>
    <?if($arParams['_TITLE']):?>
    	<h2><?=$arParams['_TITLE']?></h2>
    <?endif;?>
    <div class="reviews">
        <ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-2">
            <?foreach($arResult["ITEMS"] as $k=>$arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
                ?>
                <li>
                    <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                        <div class="review">
                            <?=$arItem["PREVIEW_TEXT"];?>
                            <?if($arItem['ACTIVE'] == 'N'):?>
                            	<div class="moderation">отзыв проверяется и скоро будет опубликован</div>
                            <?else:?>
                            	<?if(strlen($arItem["DETAIL_TEXT"])):?>
                                &nbsp;
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">подробнее</a>
                                <?endif;?>
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
<?endif;?>