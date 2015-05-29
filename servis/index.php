<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("show_right_menu", "N");
$APPLICATION->SetTitle("Сервис");
?><?$APPLICATION->IncludeComponent("bitrix:catalog.section", "section_navigation_icons", Array(
	"AJAX_MODE" => "N",	// Включить режим AJAX
		"IBLOCK_TYPE" => "system",	// Тип инфоблока
		"IBLOCK_ID" => "14",	// Инфоблок
		"SECTION_ID" => "29",	// ID раздела
		"SECTION_CODE" => "",	// Код раздела
		"SECTION_USER_FIELDS" => "",	// Свойства раздела
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
		"ELEMENT_SORT_FIELD2" => "name",	// Поле для второй сортировки элементов
		"ELEMENT_SORT_ORDER2" => "asc",	// Порядок второй сортировки элементов
		"FILTER_NAME" => "",	// Имя массива со значениями фильтра для фильтрации элементов
		"INCLUDE_SUBSECTIONS" => "A",	// Показывать элементы подразделов раздела
		"SHOW_ALL_WO_SECTION" => "N",	// Показывать все элементы, если не указан раздел
		"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
		"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
		"SECTION_ID_VARIABLE" => "",	// Название переменной, в которой передается код группы
		"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
		"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
		"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
		"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
		"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"DISPLAY_COMPARE" => "N",	// Выводить кнопку сравнения
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
		"PAGE_ELEMENT_COUNT" => "999",	// Количество элементов на странице
		"LINE_ELEMENT_COUNT" => "",	// Количество элементов выводимых в одной строке таблицы
		"PROPERTY_CODE" => array(	// Свойства
			0 => "LINK",
		),
		"OFFERS_LIMIT" => "",	// Максимальное количество предложений для показа (0 - все)
		"PRICE_CODE" => "",	// Тип цены
		"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
		"SHOW_PRICE_COUNT" => "",	// Выводить цены для количества
		"PRICE_VAT_INCLUDE" => "N",	// Включать НДС в цену
		"BASKET_URL" => "",	// URL, ведущий на страницу с корзиной покупателя
		"ACTION_VARIABLE" => "",	// Название переменной, в которой передается действие
		"PRODUCT_ID_VARIABLE" => "",	// Название переменной, в которой передается код товара для покупки
		"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
		"ADD_PROPERTIES_TO_BASKET" => "N",	// Добавлять в корзину свойства товаров и предложений
		"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
		"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
		"PRODUCT_PROPERTIES" => "",	// Характеристики товара
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "604800",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
		"PAGER_TITLE" => "",	// Название категорий
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
		"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "N",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>