<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<a name="order_fform"></a>
<div id="order_form_div" class="order-checkout order">
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>
<?
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			?>
			<script>
			<!--
			window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			//-->
			</script>
			<?
			die();
		}
		else
		{
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
		}
	}
	else
	{
		?>
		<script>
		<!--
		function submitForm(val)
		{
			if(val != 'Y') 
				BX('confirmorder').value = 'N';
			
			var orderForm = BX('ORDER_FORM');
			
			BX.ajax.submitComponentForm(orderForm, 'order_form_content', true);
			BX.submit(orderForm);

			return true;
		}
		function SetContact(profileId)
		{
			BX("profile_change").value = "Y";
			submitForm();
		}
		//-->
		</script>
        <div class="order_form">
        <div class="order">
        	<!--<h2><?=GetMessage("ORDER_TITLE");?></h2>-->
		<?if($_POST["is_ajax_post"] != "Y")
		{
			?><form action="" method="POST" name="ORDER_FORM" id="ORDER_FORM" class="custom">
			<?=bitrix_sessid_post()?>
			<div id="order_form_content">
			<?
		}
		else
		{
			$APPLICATION->RestartBuffer();
		}
		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
		{
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);

			?>
			<script>
				top.BX.scrollToNode(top.BX('ORDER_FORM'));
			</script>
			<?
		}
		?>
        <?
		if(count($arResult["PERSON_TYPE"]) > 1)
		{
			?>
            	<div class="gap5"></div><div class="gap5"></div>
                    	<?foreach($arResult["PERSON_TYPE"] as $v):?>
                    	<label class="inline_label"><input type="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE" value="<?= $v["ID"] ?>"<?if ($v["CHECKED"]=="Y") echo " checked=\"checked\"";?> onClick="submitForm()" /> <?=$v["NAME"] ?></label> &nbsp;&nbsp;&nbsp;
                        <?endforeach;?>
                <div class="gap40"></div>
                <input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>">
			<?
		}
		else
		{
			if(IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0)
			{
				?>
				<input type="hidden" name="PERSON_TYPE" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
				<input type="hidden" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
				<?
			}
			else
			{
				foreach($arResult["PERSON_TYPE"] as $v)
				{
					?>
					<input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?=$v["ID"]?>">
					<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$v["ID"]?>">
					<?
				}
			}
		}
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
		?>
        <h2><?=GetMessage("ORDER_COMMENTS");?>:</h2>
        <textarea name="ORDER_DESCRIPTION"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
        
        <p class="comment">* &mdash; обязательные поля</p>
        
        <div class="gap5"></div>
        
        <?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
        
        <?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");?>
        
        <?if($arResult['ORDER_WEIGHT']):?>
        <div class="gap20"></div>
        	Приблизительный вес посылки: <strong><?=$arResult['ORDER_WEIGHT']?> грамм</strong>
        <?endif;?>
        <div class="gap40"></div>
        

        <div class="summary">
            <div class="price">
                <div class="row">
                    <div class="large-7 medium-5 small-4 columns columns text-right">
                        <span class="summary_title">Итого к оплате:</span>
                    </div>
                    <div class="large-5 medium-7 small-8 columns">
                        <b>
                        <?=print_price($arResult['ORDER_TOTAL_PRICE_FORMATED']);?>
                        </b>
                    </div>
                </div>
            </div>
            <a href="javascript:void(0);" onClick="submitForm('Y');" class="submit_order"><b>Оформить заказ</b></a>
            <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
			<input type="hidden" name="profile_change" id="profile_change" value="N">
			<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
            <?if($arParams["DELIVERY_NO_AJAX"] == "N"):?>
				<script language="JavaScript" src="/bitrix/js/main/cphttprequest.js"></script>
				<script language="JavaScript" src="/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.js"></script>
			<?endif;?>
        </div>

        </div><!--.order-->
        </div><!--.order_form_content-->
        </div> <!--.order_form-->
        <?
	}
?>
</div>