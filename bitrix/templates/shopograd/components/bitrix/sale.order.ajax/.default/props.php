<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
function PrintPropsForm($arSource=Array(), $locationTemplate = ".default")
{
	if (!empty($arSource))
	{
		$current_group = false;
		foreach($arSource as $arProperties)
		{
			if($arProperties["GROUP_NAME"] != $current_group)
			{
				?>
                <?if($current_group):?>
                	</div>
                    </div>
                <?endif;?>
                	<h2><?=$arProperties["GROUP_NAME"] ?>:</h2>
                    <div style="margin-left:-0.625em; margin-right:-0.625em">
                	<div class="row">
				<?
				$current_group = $arProperties["GROUP_NAME"];
			}
			?>
            <?if($arProperties['CODE']!='LOCATION_OTHER'):?>
			<div class="<?if($arProperties["CODE"]=='ADDRESS'):?>large-6 medium-6 small-12<?else:?>large-3 medium-3 small-6<?endif;?> columns">
                <!--<label for="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["NAME"] ?><?if(false and ($arProperties["REQUIED_FORMATED"]=="Y")):?>*<?endif;?>:</label>-->
                <?
				if($arProperties["TYPE"] == "CHECKBOX")
				{
					?>
					<!--<input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">-->
					<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?> />
					<?
				}
				elseif($arProperties["TYPE"] == "TEXT")
				{
					?>
					<input type="text" maxlength="250" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" <?if (false and strlen($arProperties["DESCRIPTION"]) > 0):?>placeholder="<?=$arProperties["DESCRIPTION"]?>"<?endif;?> placeholder="<?=$arProperties["NAME"] ?><?if($arProperties["REQUIED_FORMATED"]=="Y"):?>*<?endif;?>" />
					<?
				}
				elseif($arProperties["TYPE"] == "SELECT")
				{
					?>
					<select name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
					<?
					foreach($arProperties["VARIANTS"] as $arVariants)
					{
						?>
						<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
						<?
					}
					?>
					</select>
					<?
				}
				elseif ($arProperties["TYPE"] == "LOCATION")
				{
					$value = 0;
					if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
					{
						foreach ($arProperties["VARIANTS"] as $arVariant)
						{
							if ($arVariant["SELECTED"] == "Y")
							{
								$value = $arVariant["ID"];
								break;
							}
						}
					}

					$GLOBALS["APPLICATION"]->IncludeComponent(
						"bitrix:sale.ajax.locations",
						'popup',
						array(
							"AJAX_CALL" => "N",
							"COUNTRY_INPUT_NAME" => "COUNTRY",//.$arProperties["FIELD_NAME"],
							"REGION_INPUT_NAME" => "REGION",//.$arProperties["FIELD_NAME"],
							"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
							"CITY_OUT_LOCATION" => "Y",
							"LOCATION_VALUE" => $value,
							"ORDER_PROPS_ID" => $arProperties["ID"],
							"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
							"SIZE1" => $arProperties["SIZE1"],
						),
						null,
						array('HIDE_ICONS' => 'Y')
					);
				}
				elseif ($arProperties["TYPE"] == "RADIO")
				{
					foreach($arProperties["VARIANTS"] as $arVariants)
					{
						?>
						<input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>" value="<?=$arVariants["VALUE"]?>"<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label> &nbsp;
						<?
					}
				}
				?>
                <div class="gap5"></div>
            </div>
            <?endif;?>
			<?
		} // endforeach
		?>
        </div> <!--.row-->
        </div><!--negative margin-->
		<?
		return true;
	}
	return false;
}
?>
<?
if(!empty($arResult["ORDER_PROP"]["USER_PROFILES"]))
{
	?>
            <h2>Данные из прошлых заказов:</h2>
            <select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
                <option value="0">Не использовать (указать новые)</option>
                <?
                foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
                {
                    ?>
                    <option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
                    <?
                }
                ?>
            </select>
	<?
}

?>
<div style="display:none;">
<?
	$APPLICATION->IncludeComponent(
		"bitrix:sale.ajax.locations",
		$arParams["TEMPLATE_LOCATION"],
		array(
			"AJAX_CALL" => "N",
			"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
			"REGION_INPUT_NAME" => "REGION_tmp",
			"CITY_INPUT_NAME" => "tmp",
			"CITY_OUT_LOCATION" => "Y",
			"LOCATION_VALUE" => "",
			"ONCITYCHANGE" => "submitForm()",
		),
		null,
		array('HIDE_ICONS' => 'Y')
	);
?>
</div>

<?
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"]);
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"]);
?>