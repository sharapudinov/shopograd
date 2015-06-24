<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<?if ($APPLICATION->GetProperty("show_right_menu") == 'Y'): ?>
                                    </div>
                                </div>
                        <?
elseif ($APPLICATION->GetProperty("show_left_column") == 'Y'): ?>
                        			</div>
                                	<div class="small-12 medium-12 large-1 columns hide-for-large-up">
                                        <div class="gap20"></div>
                                    </div>
                                    <div class="small-12 medium-12 large-2 columns large-pull-10">
                                        <?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "page",
        "AREA_FILE_SUFFIX" => "inc_left_column",
        "EDIT_TEMPLATE" => ""
    ),
    false
); ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "sect",
            "AREA_FILE_SUFFIX" => "inc_left_column",
            "EDIT_TEMPLATE" => ""
        ),
        false
    ); ?>
                                    </div>
                                </div>
                        <?endif; ?>
<?if (!_INDEX_PAGE): ?>
                        	</div><!--.centered_wrapper-->
                        <?endif; ?>
                        <div class="gap40"></div>
                    </main>
                    <footer>
                    	<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "bottom",
    Array(
        "ROOT_MENU_TYPE" => "bottom",
        "MAX_LEVEL" => "2",
        "CHILD_MENU_TYPE" => "section",
        "USE_EXT" => "N",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_TIME" => "604800",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => array()
    ),
    false
); ?>
                        <div class="gap20"></div>
                        <div class="centered_wrapper footer_bottom">
                            <div class="row collapse">
                                <div class="small-12 medium-6 large-5 columns">
                                	<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "pay_systems",
    Array(
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "N",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "info",
        "IBLOCK_ID" => "12",
        "NEWS_COUNT" => "999",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "rand",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "",
        "FIELD_CODE" => array(),
        "PROPERTY_CODE" => array("LINK"),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "604800",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PAGER_TEMPLATE" => "",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
        "PAGER_SHOW_ALL" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    )
); ?>
                                    <div class="gap5"></div><div class="gap5"></div>
                                </div>
                                <div class="small-12 medium-6 large-4 columns text-center">
                                	<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => "/_includes/copyright.php",
        "EDIT_TEMPLATE" => ""
    ),
    false
); ?>
                                    <div class="gap5"></div><div class="gap5"></div>
                                </div>
                                <div class="small-12 medium-12 large-3 columns">
                                	<div class="share_wrapper">
										<script type="text/javascript">(function() {
                                          if (window.pluso)if (typeof window.pluso.start == "function") return;
                                          if (window.ifpluso==undefined) { window.ifpluso = 1;
                                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                            s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                                            s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                                            var h=d[g]('body')[0];
                                            h.appendChild(s);
                                          }})();</script>
                                        <div class="pluso" data-background="transparent" data-options="medium,round,line,horizontal,counter,theme=04" data-services="facebook,vkontakte,twitter"></div>
                                    </div>
                                    <div class="gap5"></div><div class="gap5"></div>
                                </div>
                            </div>
                        </div>
                        <div class="gap5"></div><div class="gap5"></div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-44078576-4', 'auto');
        ga('send', 'pageview');

    </script>
    <!-- BEGIN JIVOSITE CODE {literal} -->
	<script type='text/javascript'>
    (function(){ var widget_id = 'UG4QWxUSjj';
    var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
    <!-- {/literal} END JIVOSITE CODE -->
    
    <!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter26637093 = new Ya.Metrika({id:26637093, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/26637093" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

	<?$APPLICATION->ShowProperty("delayed_code_before_body"); // used for order payment - see order.ajax template ?>

    </body>
</html>

<?
include_once("/_includes/google_analitics.php");
include('helpers/delayed.php');
