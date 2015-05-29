<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
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
.reviews .item .descr img {
	vertical-align:middle;
	margin-left:1px;
	margin-right:1px;
	margin-top:-2px;
}
.reviews .item .descr span {
	color:#CCC;	
}
</style>
<div class="reviews">
    <ul class="small-block-grid-1 medium-block-grid-1 large-block-grid-2">
    	<?foreach($arResult["ITEMS"] as $k=>$arItem):?>
        	<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
			?>
            <li>
                <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <div class="review">
                        <?=$arItem["PREVIEW_TEXT"];?>
                        <?if(strlen($arItem["DETAIL_TEXT"])):?>
                        &nbsp;
                        <a href='<?=$arItem["DETAIL_PAGE_URL"]?>'>подробнее</a>
                        <?endif;?>
                        <div class="tail"></div>
                    </div>
                    <div class="descr">
                    	<?
						$arUser = CUser::GetByID($arItem['DISPLAY_PROPERTIES']['USER']['VALUE'])->Fetch();
						$ob = CIBlockElement::GetList(false, array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'PROPERTY_USER'=>$arUser['ID']), array('IBLOCK_ID'));
						$el = $ob->Fetch();
						$total_elements = intval($el['CNT']);
						// rich russian language...
						$num_lang_transform = "отзывов";
						if (!(($total_elements>10) and ($total_elements<20))) {
							preg_match('/\d$/', $total_elements, $matches);
							if ($matches[0] == 1) {
								$num_lang_transform = "отзыв";
							} else {
								if (($matches[0]>1) and ($matches[0]<5)) {
									$num_lang_transform = "отзыва";
								}
							}
						}
						?>
                        <?=$arUser['NAME']?> &nbsp;&nbsp;&nbsp; <span><?=$total_elements?> <?=$num_lang_transform?></span> &nbsp;&nbsp;&nbsp; Вердикт:<?for($i=0;$i<$arItem['DISPLAY_PROPERTIES']['RATING']['VALUE'];$i++):?> <img src="<?=$templateFolder;?>/img/star.png" /><?endfor;?>
                    </div>
                </div>
            </li>
        <?endforeach;?>
    </ul>
</div>