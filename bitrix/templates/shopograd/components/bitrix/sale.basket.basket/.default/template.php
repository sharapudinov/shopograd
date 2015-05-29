<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!count($arResult["ITEMS"]["AnDelCanBuy"])):
?>
<p>Нет товаров для заказа. Пожалуйста, выберите интересующие Вас товары в нашем <a href="/katalog/">каталоге</a>!</p>
<?
else:
?>
	<div class="basket">
    	<div class="loading" id="basket_loading"></div>
        <form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form" class="custom">
            <div class="order_items">
            	<?foreach($arResult['ITEMS']['AnDelCanBuy'] as $k=>$arItem):?>
                <div class="item" id="basket_item_<?=$arItem['ID']?>">
                	<?if($k>0):?>
                    <div class="divider">
                        <div class="gap20"></div>
                        <hr />
                        <div class="gap20 hide-for-small"></div>
                        <div class="gap5 show-for-small"></div>
                    </div>
                    <?endif;?>
                    <div class="row">
                        <div class="large-2 small-4 columns hide-for-medium-down">
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?if(preg_match('/^http/',$arItem["_PICTURE"])):?><?=$arItem["_PICTURE"]?><?else:?><?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arItem["_PICTURE"] . '&w=100&h=75&far=C&f=png');?><?endif;?>" width="100" /></a>
                        </div>
                        <div class="large-5 medium-5 small-12 columns">
                            <div class="gap20"></div>
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="item_name"><?=$arItem['NAME']?></a>
                            <div class="gap5"></div>
                            <span class="item_params"><?if($arItem['_CODE']):?>Артикул: <?=$arItem['_CODE']?><?if($arItem['WEIGHT']):?> &nbsp;&nbsp;&nbsp; <?endif;?><?endif;?><?if($arItem['WEIGHT']):?>Вес: <?=$arItem['WEIGHT']?> г<?endif;?></span>
                        </div>
                        <div class="large-2 medium-3 small-5 columns">
                            <div class="gap20"></div>
                            <div class="gap5"></div>
                            <b class="price">
                                <?=print_price($arItem['PRICE_FORMATED']); // see main template helpers/functions.php?>
                            </b>
                        </div>
                        <div class="large-1 medium-2 small-3 columns">
                            <div class="gap20"></div>
                            <div class="gap5"></div>
                            <input type="number" value="<?=$arItem["QUANTITY"]?>" name="QUANTITY_<?=$arItem["ID"]?>" class="quantity" min="1" max="999" />
                        </div>
                        <div class="large-1 medium-1 small-2 columns">
                            <div class="gap20"></div>
                            <div class="gap5"></div>
                            <span class="unit">шт.</span>
                        </div>
                        <div class="large-1 medium-1 small-2 columns">
                            <div class="gap20"></div>
                            <div class="gap5"></div>
                            <a href="<?=$APPLICATION->GetCurPage()."?action=delete&id=" . $arItem["ID"];?>" class="delete" rel="basket_item_<?=$arItem['ID']?>"></a>
                        </div>
                    </div>
                </div>
                <?endforeach;?>
            </div>
            <div class="order_items_summary">
                <div class="row">
                    <div class="large-5 medium-12 small-12 columns">
                    	<?if($arParams['HIDE_COUPON']!='Y'):?>
                        	<?if(!strlen($arResult["COUPON"])):?>
                        		<a href="#" class="coupon_toggle">У меня есть купон на скидку</a>
                        	<?endif;?>
                            <div class="coupon_input<?if(!strlen($arResult["COUPON"])):?> hidden<?endif;?>">
                                <input type="text" class="small_input" placeholder="Введите купон сюда" name="COUPON"<?if(strlen($arResult["COUPON"])):?> value="<?=$arResult["COUPON"]?>"<?endif;?> /> <input type="submit" value="ОК" class="small_button" />
                            </div>
                        <?endif;?>
                    </div>
                    <div class="large-2 medium-5 small-4 columns text-right">
                        <span class="summary_title">Итого:</span>
                    </div>
                    <div class="large-5 medium-7 small-8 columns" id="basket_items_total">
                        <b class="summary_price">
                            <?=print_price($arResult["allSum_FORMATED"]); // see main template helpers/functions.php?>
                        </b>
                    </div>
                </div>
            </div>
            <div class="gap20"></div>
            <input type="hidden" name="BasketRefresh" value="BasketRefresh" />
        </form>
	</div>
<?
endif;
?>