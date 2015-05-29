<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h2>Вид доставки:</h2>
<?if(!count($arResult["DELIVERY"])):?>
	<p class="comment"><?=GetMessage("ORDER_DELIVERY_NO_CITY");?></p>
<?else:?>
	<div class="delivery">
        <select class="custom" name="DELIVERY_ID" onchange="submitForm();">
        <?
        $arCurrentDelivery = array();
        foreach($arResult["DELIVERY"] as $delivery_id => $arDelivery) {
            if(count($arDelivery["PROFILES"])) {
                foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile) {
                    ?>
                    <option value="<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "selected" : "";?> ><?=htmlspecialcharsbx($arDelivery["TITLE"])." (".htmlspecialcharsbx($arProfile["TITLE"]).")";?></option>
                    <?	
                    if($arProfile["CHECKED"] == "Y") {
                        $arCurrentDelivery = $arProfile;
                    }
                }
            } else {
                ?>
                <option value="<?=$arDelivery["ID"]?>" <?=$arDelivery["CHECKED"]=="Y" ? "selected" : "";?> ><?=htmlspecialcharsbx($arDelivery["NAME"]);?></option>
                <?	
                if($arDelivery["CHECKED"]=="Y") {
                    $arCurrentDelivery = $arDelivery;	
                }
            }
        }
        ?>
        </select>
        &nbsp;&nbsp;&nbsp;
        <?if($arCurrentDelivery['PERIOD_TEXT']):?>
        <span class="approx_time">
        	<?=$arCurrentDelivery['PERIOD_TEXT']?>
        </span>
        &nbsp;
        <?endif;?>
        <b><?=print_price($arCurrentDelivery['PRICE_FORMATED']); // see main template helpers/functions.php?></b>
    </div>
<?endif;?>