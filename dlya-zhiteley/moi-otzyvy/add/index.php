<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Добавить отзыв");
?>
<?
if($_REQUEST['hash'] and $_REQUEST['ID']) {
	$arSettings = array(
		"IBLOCK_ID"=>8
	);
	CModule::IncludeModule("iblock");
	$arReview = CIBlockElement::GetList(array(), array('IBLOCK_ID'=>$arSettings['IBLOCK_ID'],'ID'=>intval(trim($_REQUEST['ID'])),'ACTIVE'=>'N', 'PROPERTY_SECRET'=>htmlspecialchars(HTMLToTxt($_REQUEST['hash'])), 'PROPERTY_USER'=>$USER->GetID()), false, false, array('ID','IBLOCK_ID','PROPERTY_PRODUCT_NAME','PROPERTY_PRODUCT'))->GetNext();
	if($arReview['ID']) {
		?>
        <h2>
        	Мой отзыв
            <?if($arReview['PROPERTY_PRODUCT_NAME_VALUE']):?>
            	о товаре &laquo;<?=$arReview['PROPERTY_PRODUCT_NAME_VALUE'];?>&raquo;
            <?else:?>
            	о магазине Шопоград
            <?endif;?>
        </h2>
        <?
		$show_form = true;
		?>
        <?
        if($_REQUEST['RATING']) {
        	if((intval(trim($_REQUEST['RATING']))>0) and (intval(trim($_REQUEST['RATING']))<6)) {
				if(strlen(trim(htmlspecialchars(HTMLToTxt($_REQUEST['SHORT_REVIEW']))))) {
					// add information
					$obIblockElement = new CIBlockElement;
					$obIblockElement->Update($arReview['ID'], array('ACTIVE'=>'Y','PREVIEW_TEXT'=>trim(htmlspecialchars(HTMLToTxt($_REQUEST['SHORT_REVIEW']))), 'DETAIL_TEXT'=>trim(htmlspecialchars($_REQUEST['LONG_REVIEW'])), 'PREVIEW_TEXT_TYPE'=>'text', 'DETAIL_TEXT_TYPE'=>'text'));
					CIBlockElement::SetPropertyValueCode($arReview['ID'], "RATING", intval(trim($_REQUEST['RATING'])));
					?>
                    <p style="color:green">Спасибо за ваш отзыв! Он успешно опубликован на нашем сайте!</p>
                    <p><a href="http://www.shopograd.ru/o-shopograde/otzyvy-zhiteley/<?=$arReview['ID']?>/">посмотреть</a></p>
                    <?
					// send notification to admin
					$arEventFields = array(
						"REVIEW_ID" => $arReview['ID']
					);
					CEvent::Send("REVIEW_WAS_PUBLISHED_BY_USER", "s1", $arEventFields);
					// update reviews amount in product card
					autoCalculateCatalogProductFields($arReview['PROPERTY_PRODUCT_VALUE'],false,true,false);
					$show_form = false;
				} else {
					?>
                    <p style="color:red">Пожалуйста, напишите хотя бы пару слов...</p>
                    <?	
				}
			} else {
				?>
                <p style="color:red">Ошибка в значении рейтинга</p>
                <?	
			}
        }
		?>
        <?if($show_form):?>
        	<form class="custom_form" method="post">
            	<input type="hidden" name="hash" value="<?=htmlspecialchars(HTMLToTxt($_REQUEST['hash']))?>" />
                <input type="hidden" name="ID" value="<?=$arReview['ID']?>" />
                <script type="text/javascript">
					$(document).ready(function(){
						$('.rating_star').mouseenter(function(){
							$('.rating_star').addClass('rating_set');
							var $cur_rating;
							$cur_rating = parseInt($(this).prevAll().length + 1);
							$(this).nextAll().removeClass('rating_set');
							if($cur_rating == 1) {
								$('.rating_text').text('очень плохо');	
							}
							if($cur_rating == 2) {
								$('.rating_text').text('плохо');	
							}
							if($cur_rating == 3) {
								$('.rating_text').text('нормально');	
							}
							if($cur_rating == 4) {
								$('.rating_text').text('хорошо');	
							}
							if($cur_rating == 5) {
								$('.rating_text').text('отлично');	
							}
							$('input[name="RATING"]').val($cur_rating);
						});	
						$('.rating_star').click(function(){
							return false;	
						});
						$counter = 0;
						$initial_rating = $('input[name="RATING"]').val();
						$('.rating_star').each(function(){
							$counter++;
							if($counter <= $initial_rating) {
								$(this).mouseenter();	
							}	
						});
					});
                </script>		
                <style>
					.rating {
						font-size:36px;
						line-height:50px;
					}
					.rating_star {
						color:#CCC;	
						text-decoration:none !important;
					}
					.rating_star.rating_set {
						color:#ff4400;
					}
					.rating span {
						color:#CCC;
					}
				</style>	
                <input type="hidden" name="RATING" value="<?if((intval(trim($_REQUEST['RATING']))>0) and (intval(trim($_REQUEST['RATING']))<6)):?><?=intval(trim($_REQUEST['RATING']));?><?else:?>5<?endif;?>" />
                <div class="rating"><a class="rating_star">★</a> <a class="rating_star">★</a> <a class="rating_star">★</a> <a class="rating_star ">★</a> <a class="rating_star">★</a> &nbsp; <span class="rating_text">отлично</span></div>
                <p>Ваши впечатления в двух словах*:</p>
                <input type="text" name="SHORT_REVIEW" value="<?=preg_replace('/\"/','\"',htmlspecialchars(HTMLToTxt($_REQUEST['SHORT_REVIEW'])));?>" />
                <div class="gap5"></div><div class="gap5"></div>
                <p>Расскажите подробнее:</p>
                <textarea name="LONG_REVIEW" rows="5"><?=htmlspecialchars(HTMLToTxt($_REQUEST['LONG_REVIEW']));?></textarea>
                <div class="gap5"></div><div class="gap5"></div><div class="gap5"></div><div class="gap5"></div>
                <input type="submit" value="Опубликовать" />
                <div class="gap5"></div><div class="gap5"></div>
				<p>* &mdash; обязательные поля</p>
            </form>
        <?endif;?>
        <?
	} else {
		?>
        <p style="color:red">Добавление отзыва невозможно, либо он уже был опубликован.</p>
        <?	
	}
} else {
	?>
    <p style="color:red">Добавление отзыва невозможно.</p>
    <?	
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>