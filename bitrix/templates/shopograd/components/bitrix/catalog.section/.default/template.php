<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult['ITEMS'])>0):?>
<style>
#catalogue_items {
	position:relative;
}
#catalogue_items .blocked {
	position:absolute;
	z-index:10;
	left:0px;
	top:0px;
	width:100%;
	height:100%;
	cursor:wait;
	display:none; /*shown on js event, also used in catalog sections.list component template*/
}
.catalogue_items {
	margin-left:-10px;
	margin-right:-10px;
}
.catalogue_items .item_wrapper {
	float:left;
	width:50%;
}
@media only screen and (min-width: 768px) {
	.catalogue_items .item_wrapper {
		width:33.333%;
	}	
}
@media only screen and (min-width: 1024px) {	
	.catalogue_items .item_wrapper {
		width:25%;
	}
	.catalogue_items.less_columns .item_wrapper {
		width:33.333%;
	}
}
@media only screen and (min-width: 1280px) {	
	.catalogue_items .item_wrapper {
		width:20%;
	}
	.catalogue_items.less_columns .item_wrapper {
		width:25%;
	}
}
@media only screen and (min-width: 1680px) {	
	.catalogue_items .item_wrapper {
		width:16.666%;
	}
	.catalogue_items.less_columns .item_wrapper {
		width:20%;
	}
}
.catalogue_items a.item {
	position:relative;
	cursor:pointer;
	display:block;
	border:1px solid #EEE;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	color:#000;
	margin-left:10px;
	margin-right:10px;
	margin-bottom:20px;
	-webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
.catalogue_items a.item:hover, .catalogue_items a.item:active {
	-webkit-box-shadow: 0 0 10px 0 rgba(0,0,0,0.75);
	-moz-box-shadow: 0 0 10px 0 rgba(0,0,0,0.75);
	box-shadow: 0 0 10px 0 rgba(0,0,0,0.75);
}
.catalogue_items a.item .loading {
	position:absolute;
	z-index:9;
	left:0px;
	top:0px;
	width:100%;
	height:100%;
	background:url(<?=$templateFolder?>/img/loading.gif) center center no-repeat;
	background-color:#FFF;
	opacity:0.75;
	display:none; /*shown on js-event*/
}
.catalogue_items a.item .image {
	position:relative;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
	background-position:center center;
	background-repeat:no-repeat;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
.catalogue_items a.item .do_not_crop {
	-webkit-background-size:contain !important;
	-moz-background-size:contain !important;
	-o-background-size:contain !important;
	background-size:contain !important;
}
.catalogue_items a.item .image img {
	width:100%;	
}
.catalogue_items a.item .image .label {
	position:absolute;
	left:10px;
	top:10px;
	padding-left:10px;
	padding-right:10px;
	height:25px;
	line-height:25px;
	font-size:12px;
	color:#FFF;
	background:#ff4400;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	text-decoration:none !important;
}
.catalogue_items a.item .image .price {
	position:absolute;
	bottom:0px;
	left:0px;
	width:100%;
	height:40px;
	line-height:40px;
	font-size:18px;
	background:rgba(255,255,255,0.85);
	padding-left:20px;
	padding-right:20px;
	overflow:hidden;
	text-align:right;
	white-space:nowrap;
	text-decoration:none !important;
}
.catalogue_items a.item .image .price .old_price {
	color:#999;
	text-decoration:line-through;	
}
.catalogue_items a.item .image .price .price_prefix {
	color:#999;	
}
.catalogue_items a.item .descr {
	background:#EEE;
	padding:20px;
	padding-top:15px;
	padding-bottom:15px;
	-webkit-border-bottom-right-radius: 5px;
	-webkit-border-bottom-left-radius: 5px;
	-moz-border-radius-bottomright: 5px;
	-moz-border-radius-bottomleft: 5px;
	border-bottom-right-radius: 5px;
	border-bottom-left-radius: 5px;
}
.catalogue_items a.item .descr .name {
	height:30px;
	line-height:15px;
	margin-bottom:10px;
	overflow:hidden;
}
.catalogue_items a.item .descr .vars, .catalogue_items a.item .descr .reviews {
	float:left;
	white-space:nowrap;
	font-size:10px;
	color:#999;
	text-decoration:none !important;
}
.catalogue_items a.item .descr .reviews {
	float:right;
	color:#ff4400;	
}
.catalogue_items a.item .descr .reviews img {
	margin-right:2px;	
}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$('#catalogue_items').on('click','a.item', function(){
			$.cookie('cartbackurl', window.location.href, { expires: 999, path: '/' });
			if($(this).attr('data-prepare-url')) {
				if(!$(this).hasClass('processing')) {
					$('#catalogue_items .blocked').show();
					$(this).addClass('processing');
					$(this).find('.loading').fadeIn('fast');
					$.ajax({url: $(this).attr('data-prepare-url'), context: this})
					.done(function(data) {
						if(data == 'OK') {
							$(location).attr('href',$(this).attr('href'));
						} else {
							alert("Произошла ошибка, просмотр товара невозможен. " + data);
							$(this).fadeOut('fast',function(){ $(this).remove() });
							$('#catalogue_items .blocked').hide();
						}
					})
					.fail(function() {
						alert("Произошла ошибка, просмотр товара невозможен");
						$(this).fadeOut('fast',function(){ $(this).remove() });
						$('#catalogue_items .blocked').hide();
					});
				}
				return false;	
			}	
		});
		$('#catalogue_items').on('contextmenu','a.item', function(e){
			if($(this).attr('data-prepare-url')) {
				return false;    
			}
		});
	});
</script>
	<div id="catalogue_items">
        <div class="blocked"></div>
        <div class="catalogue_items<?if($arParams['_LESS_COLUMNS']):?> less_columns<?endif;?> ajax_paged"> <!--.ajax_paged is used in pager template-->
            <?foreach($arResult['ITEMS'] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
                ?>
                <div class="item_wrapper">
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>"<?if(strlen($arItem['PROPERTIES']['IMPORT_PROFILE_ID']['VALUE'])):?> data-prepare-url="/_tools/import/product_prepare.php?id=<?=$arItem['ID']?>&hash=<?=md5($arItem['ID'].'taobaoproduct')?>"<?endif;?><?if(strlen($arItem['PROPERTIES']['IMPORT_FULL_DESCRIPTION']['VALUE'])<1):?> rel="nofollow"<?endif;?>>
                        <?if($arItem['PROPERTIES']['IMPORT_PROFILE_ID']):?>
                        <div class="loading"></div>
                        <?endif;?>
                        <div class="image<?if($arItem['PROPERTIES']['NOT_CROP_PREVIEW_IMG']['VALUE'] or $arItem['PROPERTIES']['NOT_CROP_ALL_IMAGES']['VALUE']):?> do_not_crop<?endif;?>" style="background-image:url(<?if($arItem['PROPERTIES']['NOT_CROP_PREVIEW_IMG']['VALUE']):?><?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arItem["PREVIEW_PICTURE"]["SRC"] . '&w=400&f=png');?><?else:?><?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arItem["PREVIEW_PICTURE"]["SRC"] . '&w=360&h=400&zc=1&f=jpg&q=85');?><?endif;?>);">
                            <img src="<?=$templateFolder;?>/img/px.gif" />
                            <?if($arItem['_LABEL']):?>
                            <div class="label"><?=$arItem['_LABEL'];?></div>
                            <?endif;?>
                            <div class="price">
                                <?if($arItem['_PRICE_PREFIX']):?><span class="price_prefix"><?=$arItem['_PRICE_PREFIX']?> </span><?endif;?><?if($arItem['_OLD_PRICE']):?><span class="old_price"><?=$arItem['_OLD_PRICE']?></span> &nbsp; <?endif;?><b><?=$arItem['_PRICE']?></b>
                            </div>
                        </div>
                        <div class="descr">
                            <div class="name">
                                <?=$arItem['NAME']?>
                            </div>
                            <div class="vars">
                                <?=$arItem['_OFFERS_AMOUNT']?>
                            </div>
                            <?if($arItem['DISPLAY_PROPERTIES']['AUTO_CALCULATED_REVIEWS_AMOUNT']['VALUE']>0):?>
                            <div class="reviews">
                                <img src="<?=$templateFolder;?>/img/reviews.png" /> Есть отзывы
                            </div>
                            <?endif;?>
                            <div class="clear"></div>
                        </div>
                    </a>
                </div>
            <?endforeach;?>
        </div>
        <div class="clear"></div>
        <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
            <?=$arResult["NAV_STRING"];?>
        <?endif;?>
    </div>
<?else:?>
<div id="catalogue_items">
</div>
<?endif;?>