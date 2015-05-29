<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddChainItem("Auth");
?>
<div class="auth_page">
	<?if($arResult["AUTH_SERVICES"]):?>
    <div class="tabbed_content">
        <ul class="tabs">
            <!--<li><a href="#sms">SMS<div></div></a></li>-->
            <li><a href="#login">Логин-пароль</a></li>
            <li><a href="#social">Соц. сети</a></li>
        </ul>
        <div class="inner">
            <!--<div id="sms">
                <p>Введите Ваш мобильный телефон. На него поступит одноразовый пароль для входа на сайт.</p>
                <form class="custom">
                    <div class="row">
                        <div class="large-3 medium-4 small-12 columns">
                            <input type="text" placeholder="Мобильный телефон">
                            <div class="gap20"></div>
                        </div>
                        <div class="large-3 medium-4 small-6 columns text-right">
                            <img src="content/captcha.png" />
                            &nbsp;
                            <img src="components/auth_page/img/arr.png" />
                        </div>
                        <div class="large-3 medium-4 small-6 columns">
                            <input type="text" placeholder="Цифры с картинки">
                            <div class="gap20"></div>
                        </div>
                        <div class="large-3 medium-12 small-12 columns">
                            <input type="submit" value="Получить пароль">
                            <div class="gap20"></div>
                        </div>
                    </div>
                </form>
            </div>-->
            <div id="login">
	<?endif;?>
    			<?
				ShowMessage($arParams["~AUTH_RESULT"]);
				ShowMessage($arResult['ERROR_MESSAGE']);
				?>
                <p>Введите логин и пароль, присланные Вам ранее на электронную почту.</p>
                <form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" class="custom">
                	
                    <input type="hidden" name="AUTH_FORM" value="Y" />
                    <input type="hidden" name="TYPE" value="AUTH" />
                    <?if (strlen($arResult["BACKURL"]) > 0):?>
                    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                    <?endif?>
                    <?foreach ($arResult["POST"] as $key => $value):?>
                    <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
                    <?endforeach?>
                    <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                    <input type="hidden" name="USER_REMEMBER" value="Y" />
                    <?endif;?>
                    
                    <div class="row_negative_margins">
                        <?if($arResult["CAPTCHA_CODE"]):?>
                        	<div class="row">
								<div class="large-3 medium-4 small-6 columns">
                                    <input type="text" placeholder="Цифры с картинки" name="captcha_word" />
                                    <div class="gap20"></div>
                                </div>
                                <div class="large-9 medium-8 small-6 columns">
                                	<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
                                    <img src="<?=$templateFolder;?>/img/arr_left.png" />
                                    &nbsp;
                                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" style="height:35px;" />
                                    <div class="gap20"></div>
                                </div>
                            </div>
                        <?endif;?>
                        <div class="row">
                            <div class="large-4 medium-4 small-6 columns">
                                <input type="text" placeholder="Логин" name="USER_LOGIN" value="<?=$arResult["LAST_LOGIN"]?>" />
                                <div class="gap20"></div>
                            </div>
                            <div class="large-4 medium-4 small-6 columns">
                                <input type="password" placeholder="Пароль" name="USER_PASSWORD" />
                                <div class="gap20"></div>
                            </div>
                            <div class="large-2 medium-2 small-8 columns">
                                <input type="submit" value="Войти" name="Login">
                                <div class="gap20"></div>
                            </div>
                            <div class="large-2 medium-2 small-4 columns text-right">
                                <div class="gap5"></div><div class="gap5"></div>
                                <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>">забыли?</a>
                            </div>
                        </div>
                    </div>
                </form>
	<?if($arResult["AUTH_SERVICES"]):?>
            </div>
            <div id="social">
				<?
                $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
                    array(
                        "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                        "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
                        "AUTH_URL" => $arResult["AUTH_URL"],
                        "POST" => $arResult["POST"],
                        "SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
                        "FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
                        "AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
                    ),
                    $component,
                    array("HIDE_ICONS"=>"Y")
                );
                ?>
            </div>
        </div>
    </div>
    <?endif;?>
</div>
<h2>Впервые на сайте?</h2>
<p>Специально регистрироваться не нужно! Просто найдите интересные Вам <a href="/katalog/">товары</a> и оформите заказ. Регистрация произойдет автоматически!</p>
<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
	<p>Если Вы все же хотите стать жителем Шопограда заранее, Вам <a href="<?=$arResult["AUTH_REGISTER_URL"]?>">сюда</a>.</p>
<?endif;?>