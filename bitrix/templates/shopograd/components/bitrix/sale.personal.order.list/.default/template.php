<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult['ERRORS']['FATAL'])):?>

	<?foreach($arResult['ERRORS']['FATAL'] as $error):?>
		<?=ShowError($error)?>
	<?endforeach?>

<?else:?>

	<?if(!empty($arResult['ERRORS']['NONFATAL'])):?>

		<?foreach($arResult['ERRORS']['NONFATAL'] as $error):?>
			<?=ShowError($error)?>
		<?endforeach?>

	<?endif?>
    
    <p>	
    	<?if($_REQUEST["filter_history"] == 'Y'):?>
    	Показана <strong>история заказов</strong>.
        <?elseif($_REQUEST["show_all"] == 'Y'):?>
            Показаны <strong>все заказы</strong>.
        <?else:?>
            Показаны <strong>текущие заказы</strong>.
        <?endif;?>
        
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
    	Показать: &nbsp;&nbsp;&nbsp;

		<?$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);?>

		<?if($nothing || isset($_REQUEST["filter_history"])):?>
			<a class="bx_mo_link" href="<?=$arResult["CURRENT_PAGE"]?>?show_all=Y">все заказы</a> &nbsp;&nbsp;&nbsp;
		<?endif?>

		<?if($_REQUEST["filter_history"] == 'Y' || $_REQUEST["show_all"] == 'Y'):?>
			<a class="bx_mo_link" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=N">текущие заказы</a> &nbsp;&nbsp;&nbsp;
		<?endif?>

		<?if($nothing || $_REQUEST["filter_history"] == 'N' || $_REQUEST["show_all"] == 'Y'):?>
			<a class="bx_mo_link" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=Y">историю заказов</a>
		<?endif?>

	</p>
	
    <div class="gap5"></div>
    
	<?if(!empty($arResult['ORDERS'])):?>
        <div class="my_orders">
        	<?foreach($arResult['ORDERS'] as $arOrder):?>
            <div class="item">
                <h2>Заказ №<?=$arOrder['ORDER']['ID']?></h2>
                <?if(($arOrder['ORDER']['PAYED']=='N') and ($arOrder['ORDER']['CANCELED']!='Y')):?>
                	<p>Внимание! <b>Заказ не оплачен!</b> &nbsp;&nbsp;&nbsp; <a href="/katalog/oformlenie-zakaza/oplata/?ORDER_ID=<?=$arOrder['ORDER']['ID']?>">оплатить</a></p>
                <?endif;?>
                <p>
                    Дата: <?=FormatDate('j F Y',strtotime($arOrder['ORDER']['DATE_INSERT']));?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <?
					$arStatus = CSaleStatus::GetByID($arOrder['ORDER']['STATUS_ID']);
					?>
                    Текущий статус: <strong><?if($arOrder['ORDER']['CANCELED']=='Y'):?>отменен<?else:?><?=$arStatus['NAME']?><?endif;?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?if($arOrder['ORDER']['DATE_ALLOW_DELIVERY'] and ($arOrder['ORDER']['STATUS_ID']!='F')):?>
						<?
                        $arDelivery = CSaleDelivery::GetByID($arOrder['ORDER']['DELIVERY_ID']);
						?>
                        <?if($arDelivery['PERIOD_TO']):?>
							<?
                            $time_offset_unit = 'day';
                            if($arDelivery['PERIOD_TYPE'] == 'H') {
                                $time_offset_unit = 'hour';	
                            } elseif ($arDelivery['PERIOD_TYPE'] == 'M') {
                                $time_offset_unit = 'month';	
                            }
                            $delivery_timestamp = strtotime('+'.$arDelivery['PERIOD_TO'].$time_offset_unit,strtotime($arOrder['ORDER']['DATE_ALLOW_DELIVERY']));
                            ?>
                            <?if($delivery_timestamp>time()):?>
                            Ориентировочная дата доставки: <strong><?=FormatDate('j F Y',$delivery_timestamp);?></strong>
                            <?endif;?>
                        <?endif;?>
                    <?endif;?>
                </p>
                <?if($arOrder['ORDER']['TRACKING_NUMBER']):?>
                <p>Номер посылки: <span class="tracking_number bold"><?=$arOrder['ORDER']['TRACKING_NUMBER']?></span> <a href="http://gdeposylka.ru/<?=$arOrder['ORDER']['TRACKING_NUMBER']?>" target="_blank">отследить</a></p>
                <?endif;?>
                <table class="simple">
                	<?foreach($arOrder['BASKET_ITEMS'] as $arItem):?>
                    <tr>
                        <td><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></td>
                        <td class="text-right"><?=$arItem['QUANTITY']?> <span class="times">&times;</span> <?=print_price(CurrencyFormat($arItem['PRICE'], $arOrder['ORDER']['CURRENCY'])); // see helpers for main template?></td>
                    </tr>
                    <?endforeach;?>
                    <?if(intval($arOrder['ORDER']['PRICE_DELIVERY'])>0):?>
                    <tr>
                        <td>Доставка</td>
                        <td class="text-right"><?=print_price(CurrencyFormat($arOrder['ORDER']['PRICE_DELIVERY'], $arOrder['ORDER']['CURRENCY'])); // see helpers for main template?></td>
                    </tr>
                    <?endif;?>
                </table>
                <div class="total">
                    <div class="row collapse">
                        <div class="small-6 columns">
                            <a href="<?=$arOrder["ORDER"]["URL_TO_COPY"]?>" class="reorder">повторить заказ</a>
                            <?if(($arOrder['ORDER']['STATUS_ID']=='N') and ($arOrder["ORDER"]["CANCELED"] != "Y")):?>
                            &nbsp;&nbsp;&nbsp; <a href="<?=$arOrder["ORDER"]["URL_TO_CANCEL"]?>" class="cancel">отказаться</a>
                            <?endif;?>
                        </div>
                        <div class="small-6 columns text-right">
                            ИТОГО: <?=print_price($arOrder['ORDER']['FORMATED_PRICE']); // see helpers for main template?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gap20"></div>
            <?endforeach;?>
        </div>

		<?if(strlen($arResult['NAV_STRING'])):?>
			<?=$arResult['NAV_STRING']?>
		<?endif?>

	<?else:?>
		<p>Не найдено ни одного заказа... Начните со знакомства с нашим <a href="/katalog/">каталогом</a>!</p>
	<?endif?>

<?endif?>