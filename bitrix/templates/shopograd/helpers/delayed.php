<?
if($APPLICATION->GetProperty("noindex") == 'Y') {
	$APPLICATION->SetPageProperty('delayed_header_meta', '<meta name="robots" content="noindex, nofollow">');
}
if($APPLICATION->GetProperty("show_right_menu") == 'Y') {
	ob_start();
	?>
    <div class="row collapse">
        <div class="small-12 medium-12 large-2 columns large-push-10">
        	<?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "side",
                Array(
                    "ROOT_MENU_TYPE" => "section",
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "604800",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array(),
                    "_MOBILE_TITLE" => $APPLICATION->GetProperty("right_menu_title")
                ),
                false
            );?>
        </div>
        <div class="small-12 medium-12 large-1 columns hide-for-large-up">
            <div class="gap20"></div>
        </div>
        <div class="small-12 medium-12 large-9 columns large-pull-3">
    <?
	$APPLICATION->SetPageProperty('delayed_content_before', ob_get_clean());
} elseif($APPLICATION->GetProperty("show_left_column") == 'Y') {
	ob_start();
	?>
    <div class="row collapse">
        <div class="small-12 medium-12 large-9 columns large-push-3">
    <?
	$APPLICATION->SetPageProperty('delayed_content_before', ob_get_clean());
}
?>