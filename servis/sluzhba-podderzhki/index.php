<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Служба поддержки");
?> 
<p>Наши специалисты с радостью ответят на все Ваши вопросы в режиме онлайн, либо по e-mail <a href="mailto:support@shopograd.ru">support@shopograd.ru</a>.</p>

<style>.jivo-btn {   -webkit-box-sizing: border-box;   -moz-box-sizing: border-box;   box-sizing: border-box;   margin: 0;   text-transform: none;   cursor: pointer;   background-image: none;   display: inline-block;   padding: 6px 12px;   margin-bottom: 0;   font-size: 14px;   font-weight: normal;   line-height: 1.428571429;   text-align: center;   vertical-align: middle;   cursor: pointer;   border: 0px;   border-radius: 4px;   white-space: nowrap;   -webkit-user-select: none;   -moz-user-select: none;   -ms-user-select: none;   -o-user-select: none;   user-select: none;}.jivo-btn:hover {   box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 2px rgba(0,0,0,0.2), inset 0 0 20px 10px rgba(255,255,255,0.3);   -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 2px rgba(0,0,0,0.2), inset 0 0 20px 10px rgba(255,255,255,0.3);   -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 2px rgba(0,0,0,0.2), inset 0 0 20px 10px rgba(255,255,255,0.3);}.jivo-btn.jivo-btn-light:hover{   box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 2px rgba(0,0,0,0.3), inset 0 0 20px 10px rgba(255,255,255,0.1);   -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 2px rgba(0,0,0,0.3), inset 0 0 20px 10px rgba(255,255,255,0.1);   -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 2px rgba(0,0,0,0.3), inset 0 0 20px 10px rgba(255,255,255,0.1);}.jivo-btn.jivo-btn-light{   box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 1px rgba(0,0,0,0.3);   -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 1px rgba(0,0,0,0.3);   -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 1px 1px rgba(0,0,0,0.3);}.jivo-btn:active,.jivo-btn.jivo-btn-light:active{   box-shadow: 0 1px 0px rgba(255,255,255,0.4), inset 0 0 15px rgba(0,0,0,0.2);   -moz-box-shadow: 0 1px 0px rgba(255,255,255,0.4), inset 0 0 15px rgba(0,0,0,0.2);   -webkit-box-shadow: 0 1px 0px rgba(255,255,255,0.4), inset 0 0 15px rgba(0,0,0,0.2);   cursor: pointer;}.jivo-btn:active {   outline: 0;   background-image: none;   -webkit-box-shadow: inset 0 3px 5px rgba(0,0,0,0.125);   box-shadow: inset 0 3px 5px rgba(0,0,0,0.125);}.jivo-btn-gradient {   background-image: url(//static.jivosite.com/button/white_grad_light.png);   background-repeat: repeat-x;}.jivo-btn-light.jivo-btn-gradient {   background-image: url(//static.jivosite.com/button/white_grad.png);}.jivo-btn-icon {   width:17px;   height: 20px;   background-repeat: no-repeat;   display: inline-block;   vertical-align: middle;   margin-right: 10px;   margin-left: -5px;}.jivo-btn-light {   color: #fff;}..jivo-btn-dark {   color: #222;}</style><!--[if lte IE 7]><style type="text/css">.jivo-btn, .jivo-btn-icon  {   display: inline;}</style><![endif]--><div class="jivo-btn jivo-online-btn jivo-btn-light" onclick="jivo_api.open();" style="font-family: Arial, Arial;font-size: 14px;background-color: #008735;border-radius: 3px;-moz-border-radius: 3px;-webkit-border-radius: 3px;height: 35px;line-height: 35px;padding: 0 17px 0 17px;font-weight: normal;font-style: normal"><div class="jivo-btn-icon" style="background-image: url(//static.jivosite.com/button/chat_light.png);"></div>Начать онлайн-чат</div><div class="jivo-btn jivo-offline-btn jivo-btn-light" onclick="jivo_api.open();" style="font-family: Arial, Arial;font-size: 14px;background-color: #008735;border-radius: 3px;-moz-border-radius: 3px;-webkit-border-radius: 3px;height: 35px;line-height: 35px;padding: 0 17px 0 17px;display: none;font-weight: normal;font-style: normal"><div class="jivo-btn-icon" style="background-image: url(//static.jivosite.com/button/mail_light.png);"></div>Оставить сообщение специалисту</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>