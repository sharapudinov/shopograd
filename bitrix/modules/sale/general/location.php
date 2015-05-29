<?
/**
 * The content of this file was marked as deprecated.
 * It will be removed from future releases. Do not rely on this code.
 *
 * @access private
 */

use Bitrix\Main;
use Bitrix\Main\DB;
use Bitrix\Main\Config;
use Bitrix\Main\Entity;
use Bitrix\Sale\Location;
use Bitrix\Sale\Delivery;
use Bitrix\Main\Localization;

class CAllSaleLocation
{
	const LOC2_OPT = 					'sale_locationpro_enabled';
	const LOC2_M_OPT = 					'sale_locationpro_migrated';
	const LOC2_DEBUG_MODE_OPT = 		'location2_debug_mode';

	const SELF_ENTITY_NAME = 			'Bitrix\Sale\Location\Location';
	const NAME_ENTITY_NAME = 			'Bitrix\Sale\Location\Name\Location';
	const DEFAULT_SITE_ENTITY_NAME = 	'Bitrix\Sale\Location\DefaultSite';

	const ORIGIN_NAME_LANGUAGE_ID = 	'en';
	const ZIP_EXT_SERVICE_CODE =		'ZIP';

	const MODIFIER_SEARCH_R = 			'#^((!|\+)?(>=|>|<=|<|@|~|%|=)?)#';
	const LEADING_TILDA_SEARCH_R = 		'#^~#';

	/////////////////////////////////////////////
	// enable this when you want to turn on the new functionality

	public static function isLocationProEnabled()
	{
		return self::isLocationProMigrated() && Config\Option::get('sale', self::LOC2_OPT, '') == 'Y';
	}

	public static function locationProEnable()
	{
		Config\Option::set('sale', self::LOC2_OPT, 'Y', '');
	}

	public static function locationProDisable()
	{
		Config\Option::set('sale', self::LOC2_OPT, 'N', '');
	}

	// very temporal code
	public static function locationProCheckEnabled()
	{
		if(!self::isLocationProEnabled())
		{
			if($_REQUEST['l2switch'] == 'ON')
			{
				CSaleLocation::locationProEnable();
				return true;
			}
			
			?>
			<form action="" method="post">
				Location 2.0 were disabled.&nbsp;<button name="l2switch" value="ON">Enable</button>
			</form>
			<?
			return false;
		}
		return true;
	}

	// very temporal code
	public static function locationProControlForm()
	{
		?>

		<?if($_REQUEST['l2migrated']):?>
			<?if($_REQUEST['l2migrated'] == 'ON'):?>
				<?self::locationProSetMigrated();?>
			<?else:?>
				<?self::locationProSetRolledBack();?>
			<?endif?>
		<?endif?>

		<?if($_REQUEST['l2switch']):?>
			<?if($_REQUEST['l2switch'] == 'ON'):?>
				<?self::locationProEnable();?>
			<?else:?>
				<?self::locationProDisable();?>
			<?endif?>
		<?endif?>

		<?if($_REQUEST['l2debug']):?>
			<?if($_REQUEST['l2debug'] == 'ON'):?>
				<?self::locationProDebugEnable();?>
			<?else:?>
				<?self::locationProDebugDisable();?>
			<?endif?>
		<?endif?>

		<?$l2enabled = self::isLocationProEnabled();?>
		<?$l2migrated = self::isLocationProMigrated();?>
		<?$l2debug = self::isLocationProInDebug();?>

		<form action="" method="post">
			Migration: <br />
			<button name="l2migrated" value="<?=($l2migrated ? 'OFF' : 'ON')?>"><?=($l2migrated ? 'Go Down' : 'Go Up')?></button>
		</form>

		<form action="" method="post">
			Location 2.0: <br />
			<button name="l2switch" value="<?=($l2enabled ? 'OFF' : 'ON')?>"><?=($l2enabled ? 'Turn OFF' : 'Turn ON')?></button>
		</form>

		<form action="" method="post">
			Debug mode: <br />
			<button name="l2debug" value="<?=($l2debug ? 'OFF' : 'ON')?>"><?=($l2debug ? 'Turn OFF' : 'Turn ON')?></button>
		</form>

		<?
	}

	// for old admin pages the following function should be used
	// (for the new ones - direct call of sale.location.selector.*)
	public static function proxySaleAjaxLocationsComponent($parameters = array(), $additionalParams = array(), $template = '', $hideIcons = true, $wrapNewComponentWith = false)
	{
		global $APPLICATION;

		if(self::isLocationProEnabled())
		{
			if(!is_array($additionalParams))
				$additionalParams = array();

			$parametersProxed = array_merge(array(
				"ID" => $parameters["LOCATION_VALUE"],
				"CODE" => '',
				"INPUT_NAME" => $parameters["CITY_INPUT_NAME"],
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"PROVIDE_LINK_BY" => "id",
				"SEARCH_BY_PRIMARY" => "N",
				"SHOW_DEFAULT_LOCATIONS" => "N",
				//"JS_CALLBACK" => "submitFormProxy",
				//"JS_CONTROL_DEFERRED_INIT" => "soa_deferred"
			), $additionalParams);

			if(strlen($parameters['SITE_ID']) || ADMIN_SECTION != 'Y')
			{
				$parametersProxed["FILTER_BY_SITE"] = "Y";
				$parametersProxed["FILTER_SITE_ID"] = strlen($parameters['SITE_ID']) ? $parameters['SITE_ID'] : SITE_ID;
			}

			if(strlen($wrapNewComponentWith))
				print('<div class="'.$wrapNewComponentWith.'">');

			if(!strlen($template))
				$appearance = \Bitrix\Sale\Location\Admin\Helper::getWidgetAppearance();
			else
				$appearance = $template == 'popup' ? 'search' : 'steps';

			$GLOBALS["APPLICATION"]->IncludeComponent(
				"bitrix:sale.location.selector.".$appearance,
				"",
				$parametersProxed,
				null,
				array('HIDE_ICONS' => $hideIcons ? 'Y' : 'N')
			);

			if($wrapNewComponentWith)
				print('</div>');
		}
		else
		{
			$GLOBALS["APPLICATION"]->IncludeComponent(
				'bitrix:sale.ajax.locations',
				$template,
				$parameters,
				null,
				array('HIDE_ICONS' => $hideIcons ? 'Y' : 'N')
			);
		}
	}

	/////////////////////////////////////////////
	// enable this when you want to get debugging

	public static function isLocationProInDebug()
	{
		return Config\Option::get('sale', self::LOC2_DEBUG_MODE_OPT, '') == 'Y';
	}

	public static function locationProDebugEnable()
	{
		Config\Option::set('sale', self::LOC2_DEBUG_MODE_OPT, 'Y', '');
	}

	public static function locationProDebugDisable()
	{
		Config\Option::set('sale', self::LOC2_DEBUG_MODE_OPT, 'N', '');
	}

	/////////////////////////////////////////////
	// enable this when you had done the migration

	public static function isLocationProMigrated()
	{
		return Config\Option::get('sale', self::LOC2_M_OPT, '') == 'Y';
	}

	public static function locationProSetMigrated()
	{
		Config\Option::set('sale', self::LOC2_M_OPT, 'Y', '');
	}

	public static function locationProSetRolledBack()
	{
		Config\Option::set('sale', self::LOC2_M_OPT, 'N', '');
	}

	/////////////////////////////////////////////
	/////////////////////////////////////////////
	/////////////////////////////////////////////

	public static function getLocationIDbyCODE($code)
	{
		if(CSaleLocation::isLocationProEnabled() && strlen($code))
		{
			// we must convert CODE to ID, kz now we have CODE stored in profile
			$item = Location\LocationTable::getByCode($code, array(
				'select' => array(
					'*'
				),
			))->fetch();

			if(empty($item))
				return 0;

			return $item['ID'];
		}

		return $code;
	}

	public static function getLocationCODEbyID($id)
	{
		if(CSaleLocation::isLocationProEnabled() && intval($id))
		{
			// we must convert ID to CODE
			$item = Location\LocationTable::getById($id)->fetch();

			if(empty($item))
				return '';

			return $item['CODE'];
		}

		return $id;
	}

	private static function getLanguages()
	{
		$langs = array();
		$res = Localization\LanguageTable::getList();
		while($item = $res->fetch())
			$langs[] = $item['LID'];

		return $langs;
	}

	public static function getSites()
	{
		$sites = array();
		$res = Main\SiteTable::getList();
		while($item = $res->fetch())
			$sites[] = $item['LID'];

		return $sites;
	}

	public static function getTypes()
	{
		$types = array();
		$res = Location\TypeTable::getList();
		while($item = $res->fetch())
			$types[$item['CODE']] = $item['ID'];

		return $types;
	}

	private static function getZipId()
	{
		$res = Location\ExternalServiceTable::getList(array('filter' => array('=CODE' => self::ZIP_EXT_SERVICE_CODE), 'limit' => 1))->fetch();

		if($res)
			return $res['ID'];

		return false;
	}

	private static function refineFieldsForSaveCRC($id, $arFields)
	{
		if(!is_array($arFields[self::ORIGIN_NAME_LANGUAGE_ID]))
		{
			// make default names as en-names
			$arFields[self::ORIGIN_NAME_LANGUAGE_ID] = array(
				'LID' => self::ORIGIN_NAME_LANGUAGE_ID,
				'NAME' => $arFields['NAME'],
				'SHORT_NAME' => $arFields['SHORT_NAME']
			);
		}

		$names = array();
		foreach(self::getLanguages() as $lid)
		{
			if(is_array($arFields[$lid]))
			{
				unset($arFields[$lid]['LID']);
				$names[$lid] = $arFields[$lid];
			}
		}

		return array(
			'ID' => $id, // array should not be empty
			'NAME' => $names
		);
	}

	private static function getLocationIdByCountryId($legacyId)
	{
		$res = $item = Location\LocationTable::getList(array(
			'filter' => array(
				'=TYPE.CODE' => 'COUNTRY',
				'=COUNTRY_ID' => intval($legacyId),
				'=REGION_ID' => false,
				'=CITY_ID' => false
			),
			'select' => array(
				'ID'
			)
		))->fetch();

		return $res['ID'];
	}

	private static function getLocationIdByCityId($legacyId)
	{
		$res = Location\LocationTable::getList(array(
			'filter' => array(
				'=TYPE.CODE' => 'CITY',
				'=CITY_ID' => intval($legacyId),
			),
			'select' => array(
				'ID'
			)
		))->fetch();

		return $res['ID'];
	}

	private static function getLocationIdByRegionId($legacyId)
	{
		$res = Location\LocationTable::getList(array(
			'filter' => array(
				'=TYPE.CODE' => 'REGION',
				'=REGION_ID' => intval($legacyId),
				'=CITY_ID' => false
			),
			'select' => array(
				'ID'
			)
		))->fetch();

		return $res['ID'];
	}

	private static function checkLangPresenceInSelect($type, $fields)
	{
		return in_array($type.'_NAME_', $fields, true) || in_array($type.'_SHORT_NAME', $fields, true) || in_array($type.'_NAME', $fields, true) || in_array($type.'_NAME_LANG', $fields, true) || in_array($type.'_LID', $fields, true);
	}

	private static function getTypeValueToStore($type, $arFields)
	{
		if(isset($arFields[$type]) && is_array($arFields[$type]) && !empty($arFields[$type]))
			return $arFields[$type];

		if(isset($arFields[$type.'_ID']) && intval($arFields[$type.'_ID']))
			return intval($arFields[$type.'_ID']);

		return false;
	}

	protected static function GetLocationTypeList($typeCode = '', $arOrder = Array("NAME_LANG"=>"ASC"), $arFilter=Array(), $strLang = LANGUAGE_ID)
	{
		if(!in_array($typeCode, array('COUNTRY', 'REGION', 'CITY')))
			return false;

		$types = self::getTypes();

		if(empty($types) || !isset($types['COUNTRY']) || !isset($types['REGION']) || !isset($types['CITY']))
		{
			// no types, conditions will break
			$res = new CDBResult();
			$res->InitFromArray(array());
			return $res;
		}

		$query = new Entity\Query(self::SELF_ENTITY_NAME);

		$fieldMap = array(

			'ID',
			'NAME_ORIG' => 'NAME_ORIG_.NAME',
			'SHORT_NAME' => 'NAME_ORIG_.SHORT_NAME',

			'RNAME' => 'NAME.NAME',
			'NAME_LANG' => 'NAME_LANG',

			'COUNTRY_ID_' => 'TO_COUNTRY.ID',
			'REGION_ID_' => 'TO_REGION.ID'
		);
		$fieldProxy = array(
			'RID' => 'ID',
			'RNAME' => 'NAME',
			'COUNTRY_ID_' => 'COUNTRY_ID',
			'REGION_ID_' => 'REGION_ID'
		);

		////////////////////////////////////
		////////////////////////////////////
		////////////////////////////////////
		//if(in_array('COUNTRY_ID', $arFilter, true))
		//{
			$query->registerRuntimeField(
					'TO_COUNTRY',
					array(
						'data_type' => self::SELF_ENTITY_NAME,
						'reference' => array(

							// it should be ancestor
							'<=ref.LEFT_MARGIN' => 'this.LEFT_MARGIN',
							'>=ref.RIGHT_MARGIN' => 'this.RIGHT_MARGIN',

							// it should be COUNTRY
							'=ref.TYPE_ID' => array('?', $types['COUNTRY']),
						),
						'join_type' => 'left'
					)
				);
		//}

		//if(in_array('REGION_ID', $arFilter, true))
		//{
			$query->registerRuntimeField(
				'TO_REGION',
				array(
					'data_type' => self::SELF_ENTITY_NAME,
					'reference' => array(

						// REGION search for COUNTRY is meaningless
						array(
							'LOGIC' => 'OR',
							array(
								'=this.TYPE_ID' => array('?', $types['REGION']),
							),
							array(
								'=this.TYPE_ID' => array('?', $types['CITY']),
							),
						),

						// it should be ancestor
						'<=ref.LEFT_MARGIN' => 'this.LEFT_MARGIN',
						'>=ref.RIGHT_MARGIN' => 'this.RIGHT_MARGIN',

						// it should be REGION
						'=ref.TYPE_ID' => array('?', $types['REGION']),
					),
					'join_type' => 'left'
				)
			);
		//}

		////////////////////////////////////
		////////////////////////////////////
		////////////////////////////////////

		$selectFields = self::processSelectForGetList(array('*'), $fieldMap);

		// filter
		list($filterFields, $filterFieldsClean) = self::processFilterForGetList($arFilter, $fieldMap, $fieldProxy, $query);

		if($filterFields === false)
			$filterFields = array();
		$filterFields['=TYPE_ID'] = $types[$typeCode];

		// order
		$orderFields = self::processOrderForGetList($arOrder, $fieldMap, $fieldProxy);

		$query->registerRuntimeField(
			'NAME_ORIG_',
			array(
				'data_type' => self::NAME_ENTITY_NAME,
				'reference' => array(
					'=this.ID' => 'ref.LOCATION_ID',
					'=ref.LANGUAGE_ID' => array('?', self::ORIGIN_NAME_LANGUAGE_ID)
				),
				'join_type' => 'left'
			)
		);

		$nameJoinCondition = array(
			'=this.ID' => 'ref.LOCATION_ID',
		);
		if(strlen($strLang))
			$nameJoinCondition['=ref.LANGUAGE_ID'] = array('?', $strLang);

		$query->registerRuntimeField(
			'NAME',
			array(
				'data_type' => self::NAME_ENTITY_NAME,
				'reference' => $nameJoinCondition,
				'join_type' => 'left'
			)
		);

		$query->registerRuntimeField('NAME_LANG', array(
			'data_type' => 'string',
			'expression' => array(
				"CASE WHEN (%s IS NULL OR %s = '') THEN %s ELSE %s END",
				'NAME.NAME',
				'NAME.NAME',
				'NAME_ORIG_.NAME',
				'NAME.NAME'
			),
		));

		$query->setFilter($filterFields);
		$query->setSelect($selectFields);
		if(is_array($orderFields))
			$query->setOrder($orderFields);

		return new CDBResult(self::proxyFieldsInResult($query->exec(), $fieldProxy));
	}

	public static function checkLocationIdExists($id)
	{
		$res = Location\LocationTable::getById($id)->fetch();

		return intval($res['ID']);
	}

	public static function checkLocationCodeExists($code)
	{
		$res = Location\LocationTable::getByCode($code)->fetch();

		return intval($res['ID']);
	}

	public static function getFreeId($type)
	{
		$class = self::SELF_ENTITY_NAME.'Table';
		$item = $class::getList(array('select' => array($type.'_ID'), 'limit' => 1, 'order' => array($type.'_ID' => 'desc')))->fetch();

		$fromLocTable = intval($item[$type.'_ID']);
	
		$res = self::GetLocationTypeList($type, array('ID' => 'desc'))->fetch();
		$fromTypeTable = $res['ID'];

		return max($fromLocTable, $fromTypeTable) + 1;
	}

	public static function getTypeJOINCondition($ctx = 'this')
	{
		static $types;

		if($types == null)
		{
			$types = self::getTypes();
		}

		return array(
			'LOGIC' => 'OR',
			array('='.$ctx.'.TYPE_ID' => array('?', $types['COUNTRY'])),
			array('='.$ctx.'.TYPE_ID' => array('?', $types['REGION'])),
			array('='.$ctx.'.TYPE_ID' => array('?', $types['CITY'])),
		);
	}

	public static function getTypeFilterCondition()
	{
		static $types;

		if($types == null)
		{
			$types = self::getTypes();
		}

		return array($types['COUNTRY'], $types['REGION'], $types['CITY']);
	}

	public static function getDenormalizedLocationList($entityName, $arFilter = array())
	{
		$class = 				$entityName.'Table';
		$linkField = 			$class::getLinkField();
		$typeField = 			$class::getTypeField();
		$locationLinkField = 	$class::getLocationLinkField();
		$useGroups = 			$class::getUseGroups();

		$query = new Entity\Query($entityName);

		$fieldMap = array(
			'D_SPIKE' => 'D_SPIKE',
			'RLOCATION_ID' => 'RLOCATION_ID',
			'LCOUNTRY_ID' => 'C.ID',
			'LREGION_ID' => 'C.REGION_ID',
			'LCITY_ID' => 'C.CITY_ID',
			$linkField => $linkField
		);
		if($useGroups)
		{
			$fieldMap['LLOCATION_TYPE'] = $typeField;
		}

		$fieldProxy = array(
			'LLOCATION_ID' => $locationLinkField
		);
		if($useGroups)
			$fieldProxy['LLOCATION_TYPE'] = 'LOCATION_TYPE';

		$query->registerRuntimeField(
			'D_SPIKE',
			array(
				'data_type' => 'integer',
				'expression' => array(
					'distinct %s',
					$linkField
				)
			)
		);

		/////////////////////////////////////////////////
		// denormalized

		$joinCondition = array(
			self::getTypeJOINCondition('ref'),
			'=this.'.$locationLinkField => 'ref.ID',
		);
		if($useGroups)
			$joinCondition['=this.'.$typeField] = array('?', Location\Connector::DB_LOCATION_FLAG);

		$types = self::getTypes();

		$query->registerRuntimeField(
			'L',
			array(
				'data_type' => self::SELF_ENTITY_NAME,
				'reference' => $joinCondition,
				'join_type' => 'left'
			)
		);

		$query->registerRuntimeField(
			'C',
			array(
				'data_type' => self::SELF_ENTITY_NAME,
				'reference' => array(
					'LOGIC' => 'OR',
					array(
						self::getTypeJOINCondition('ref'),
						'>=ref.LEFT_MARGIN' => 'this.L.LEFT_MARGIN',
						'<=ref.RIGHT_MARGIN' => 'this.L.RIGHT_MARGIN'
					),
					/*
					array(
						'=ref.ID' => 'this.L.ID'
					)
					*/
				),
				'join_type' => 'left'
			)
		);

		$query->registerRuntimeField(
			'RLOCATION_ID',
			array(
				'data_type' => 'integer',
				'expression' => array(
					'case when %s is null then %s else %s end',
					'C.ID',
					'LOCATION_ID',
					'C.ID'
				)
			)
		);

		// select
		$selectFields = CSaleLocation::processSelectForGetList(array('*'), $fieldMap);

		// filter
		list($filterFields, $filterClean) = CSaleLocation::processFilterForGetList($arFilter, $fieldMap, $fieldProxy, $query);

		$query->setSelect($selectFields);
		$query->setFilter($filterFields);

		$res = $query->exec();
		$res->addReplacedAliases(array(
			'RLOCATION_ID' => 'LOCATION_ID',
			'LLOCATION_TYPE' => 'LOCATION_TYPE',
			'LCOUNTRY_ID' => 'COUNTRY_ID',
			'LREGION_ID' => 'REGION_ID',
			'LCITY_ID' => 'CITY_ID'
		));

		return $res;
	}

	/////////////////////////////////////////////
	/////////////////////////////////////////////
	/////////////////////////////////////////////
	// old api works in the old manner only when sale::isLocationProMigrated() returns false

	function GetLocationString($locationId, $siteId = SITE_ID, $langId = LANGUAGE_ID)
	{
		$locationString = '';

		if(!\Bitrix\Sale\SalesZone::checkLocationId($locationId, $siteId))
			$locationId = 0;

		$countryId = $regionId = $cityId = 0;
		if ($locationId > 0)
		{
			if ($arLocation = CSaleLocation::GetByID($locationId))
			{
				$countryId = $arLocation["COUNTRY_ID"];
				$regionId = $arLocation["REGION_ID"];
				$cityId = $arLocation["CITY_ID"];
			}
		}

		//check in location city
		$bEmptyCity = "N";
		$arCityFilter = array("!CITY_ID" => "NULL", ">CITY_ID" => "0");
		if ($countryId > 0)
			$arCityFilter["COUNTRY_ID"] = $countryId;
		$rsLocCount = CSaleLocation::GetList(array(), $arCityFilter, false, false, array("ID"));
		if (!$rsLocCount->Fetch())
			$bEmptyCity = "Y";

		//check in location region
		$bEmptyRegion = "N";
		$arRegionFilter = array("!REGION_ID" => "NULL", ">REGION_ID" => "0");
		if ($countryId > 0 && $regionId > 0)
			$arRegionFilter["COUNTRY_ID"] = $countryId;
		if ($regionId > 0)
			$arRegionFilter["REGION_ID"] = $regionId;
		$rsLocCount = CSaleLocation::GetList(array(), $arRegionFilter, false, false, array("ID"));
		if (!$rsLocCount->Fetch())
			$bEmptyRegion = "Y";

		//check if exist another city
		if ($bEmptyCity == "Y" && $bEmptyRegion == "Y")
		{
			$arCityFilter = array("!CITY_ID" => "NULL", ">CITY_ID" => "0");
			$rsLocCount = CSaleLocation::GetList(array(), $arCityFilter, false, false, array("ID"));
			if ($rsLocCount->Fetch())
				$bEmptyCity = "N";
		}

		//location value
		if ($locationId > 0 )
		{
			if ($arLocation = CSaleLocation::GetByID($locationId))
			{
				if ($bEmptyRegion == "Y" && $bEmptyCity == "Y")
					$countryId = $locationId;
				else
					$countryId = $arLocation["COUNTRY_ID"];

				if ($bEmptyCity == "Y")
					$regionId = $arLocation["ID"];
				else
					$regionId = $arLocation["REGION_ID"];

				$cityId = $locationId;
			}
		}

		//select country
		$arCountryList = array();

		if ($bEmptyRegion == "Y" && $bEmptyCity == "Y")
			$rsCountryList = CSaleLocation::GetList(array("SORT" => "ASC", "NAME_LANG" => "ASC"), array("LID" => $langId), false, false, array("ID", "COUNTRY_ID", "COUNTRY_NAME_LANG"));
		else
			$rsCountryList = CSaleLocation::GetCountryList(array("SORT" => "ASC", "NAME_LANG" => "ASC"));

		while ($arCountry = $rsCountryList->GetNext())
		{
			if(!\Bitrix\Sale\SalesZone::checkCountryId($arCountry["ID"], $siteId))
				continue;

			if ($bEmptyRegion == "Y" && $bEmptyCity == "Y")
				$arCountry["NAME_LANG"] = $arCountry["COUNTRY_NAME_LANG"];

			$arCountryList[] = $arCountry;
			if ($arCountry["ID"] == $countryId && strlen($arCountry["NAME_LANG"]) > 0)
				$locationString .= $arCountry["NAME_LANG"];
		}

		if (count($arCountryList) <= 0)
			$arCountryList = array();
		elseif (count($arCountryList) == 1)
			$countryId = $arCountryList[0]["ID"];

		//select region
		$arRegionList = array();
		if ($countryId > 0 || count($arCountryList) <= 0)
		{
			$arRegionFilter = array("LID" => $langId, "!REGION_ID" => "NULL", "!REGION_ID" => "0");
			if ($countryId > 0)
				$arRegionFilter["COUNTRY_ID"] = IntVal($countryId);

			if ($bEmptyCity == "Y")
				$rsRegionList = CSaleLocation::GetList(array("SORT" => "ASC", "NAME_LANG" => "ASC"), $arRegionFilter, false, false, array("ID", "REGION_ID", "REGION_NAME_LANG"));
			else
				$rsRegionList = CSaleLocation::GetRegionList(array("SORT" => "ASC", "NAME_LANG" => "ASC"), $arRegionFilter);

			while ($arRegion = $rsRegionList->GetNext())
			{
				if(!\Bitrix\Sale\SalesZone::checkRegionId($arRegion["ID"], $siteId))
					continue;

				if ($bEmptyCity == "Y")
					$arRegion["NAME_LANG"] = $arRegion["REGION_NAME_LANG"];

				$arRegionList[] = $arRegion;
				if ($arRegion["ID"] == $regionId && strlen($arRegion["NAME_LANG"]) > 0)
					$locationString = $arRegion["NAME_LANG"].", ".$locationString;
			}
		}
		if (count($arRegionList) <= 0)
			$arRegionList = array();
		elseif (count($arRegionList) == 1)
			$regionId = $arRegionList[0]["ID"];

		//select city
		$arCityList = array();
		if (
			$bEmptyCity == "N"
			&& ((count($arCountryList) > 0 && count($arRegionList) > 0 && $countryId > 0 && $regionId > 0)
				|| (count($arCountryList) <= 0 && count($arRegionList) > 0 && $regionId > 0)
				|| (count($arCountryList) > 0 && count($arRegionList) <= 0 && $countryId > 0)
				|| (count($arCountryList) <= 0 && count($arRegionList) <= 0))
		)
		{
			$arCityFilter = array("LID" => $langId);
			if ($countryId > 0)
				$arCityFilter["COUNTRY_ID"] = $countryId;
			if ($regionId > 0)
				$arCityFilter["REGION_ID"] = $regionId;

			$rsLocationsList = CSaleLocation::GetList(
				array(
					"SORT" => "ASC",
					"COUNTRY_NAME_LANG" => "ASC",
					"CITY_NAME_LANG" => "ASC"
				),
				$arCityFilter,
				false,
				false,
				array(
					"ID", "CITY_ID", "CITY_NAME"
				)
			);

			while ($arCity = $rsLocationsList->GetNext())
			{
				if(!\Bitrix\Sale\SalesZone::checkCityId($arCity["CITY_ID"], $siteId))
					continue;

				$arCityList[] = array(
					"ID" => $arCity["ID"],
					"CITY_ID" => $arCity['CITY_ID'],
					"CITY_NAME" => $arCity["CITY_NAME"],
				);
				if ($arCity["ID"] == $cityId)
					$locationString = (strlen($arCity["CITY_NAME"]) > 0 ? $arCity["CITY_NAME"].", " : "").$locationString;
			}//end while
		}

		return $locationString;
	}

	/////////////////////////////////////////////

	function CountryCheckFields($ACTION, &$arFields)
	{
		global $DB;

		if ((is_set($arFields, "NAME") || $ACTION=="ADD") && strlen($arFields["NAME"])<=0) return false;

		/*
		$db_lang = CLangAdmin::GetList(($b="sort"), ($o="asc"), array("ACTIVE" => "Y"));
		while ($arLang = $db_lang->Fetch())
		{
			if ((is_set($arFields[$arLang["LID"]], "NAME") || $ACTION=="ADD") && strlen($arFields[$arLang["LID"]]["NAME"])<=0) return false;
		}
		*/

		return True;
	}

	function UpdateCountry($ID, $arFields)
	{
		global $DB;

		$ID = intval($ID);

		if ($ID <= 0 || !CSaleLocation::CountryCheckFields("UPDATE", $arFields))
			return false;

		foreach (GetModuleEvents("sale", "OnBeforeCountryUpdate", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID, &$arFields))===false)
				return false;

		//////////////////////////////////////

		if(self::isLocationProMigrated())
		{
			try
			{
				// get location id by country id
				$locId = self::getLocationIdByCountryId($ID);

				if(!$locId)
					return false;

				$res = Location\LocationTable::update(
					$locId,
					self::refineFieldsForSaveCRC($item['ID'], $arFields),
					array('REBALANCE' => false)
				);

				if($res->isSuccess())
					return $ID;

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			$strUpdate = $DB->PrepareUpdate("b_sale_location_country", $arFields);
			$strSql = "UPDATE b_sale_location_country SET ".$strUpdate." WHERE ID = ".$ID."";
			$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			$db_lang = CLangAdmin::GetList(($b="sort"), ($o="asc"), array("ACTIVE" => "Y"));
			while ($arLang = $db_lang->Fetch())
			{
				if ($arCntLang = CSaleLocation::GetCountryLangByID($ID, $arLang["LID"]))
				{
					$strUpdate = $DB->PrepareUpdate("b_sale_location_country_lang", $arFields[$arLang["LID"]]);
					$strSql = "UPDATE b_sale_location_country_lang SET ".$strUpdate." WHERE ID = ".$arCntLang["ID"]."";
				}
				else
				{
					$arInsert = $DB->PrepareInsert("b_sale_location_country_lang", $arFields[$arLang["LID"]]);
					$strSql =
						"INSERT INTO b_sale_location_country_lang(COUNTRY_ID, ".$arInsert[0].") ".
						"VALUES(".$ID.", ".$arInsert[1].")";
				}
				$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			}
		}

		//////////////////////////////////////

		foreach (GetModuleEvents("sale", "OnCountryUpdate", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID, $arFields));

		return $ID;
	}

	function DeleteCountry($ID)
	{
		global $DB;
		$ID = IntVal($ID);

		foreach (GetModuleEvents("sale", "OnBeforeCountryDelete", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID))===false)
				return false;

		if(self::isLocationProMigrated())
		{
			try
			{
				/*
				$locId = self::getLocationIdByCountryId($ID);

				if(!$locId)
					return false;

				// just "unlink" it, so CSaleLocation::GetCountryByID() wont able to find it
				$res = Location\LocationTable::update($locId, array(
					'COUNTRY_ID' => ''
				));

				return $res->isSuccess();
				*/
				return true;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		
		// and also drop old records, if any
		$DB->Query("DELETE FROM b_sale_location_country_lang WHERE COUNTRY_ID = ".$ID."", true);
		$bDelete = $DB->Query("DELETE FROM b_sale_location_country WHERE ID = ".$ID."", true);

		foreach (GetModuleEvents("sale", "OnCountryDelete", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID));

		return $bDelete;
	}

	function GetCountryByID($ID)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				$res = Location\LocationTable::getList(array(
					'filter' => array(
						'=TYPE.CODE' => 'COUNTRY',
						'=ID' => intval($ID),
						'NAME.LANGUAGE_ID' => self::ORIGIN_NAME_LANGUAGE_ID
					),
					'select' => array(
						'ID',
						'LNAME' => 'NAME.NAME',
						'SHORT_NAME' => 'NAME.SHORT_NAME'
					)
				));
				$res->addReplacedAliases(array('LNAME' => 'NAME'));

				$item = $res->fetch();

				if($item)
					return $item;

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			global $DB;

			$ID = IntVal($ID);
			$strSql =
				"SELECT * ".
				"FROM b_sale_location_country ".
				"WHERE ID = ".$ID." ";
			$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if ($res = $db_res->Fetch())
			{
				return $res;
			}
			return False;

		}
	}

	function GetCountryLangByID($ID, $strLang = LANGUAGE_ID)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				$res = Location\LocationTable::getList(array(
					'filter' => array(
						'=TYPE.CODE' => 'COUNTRY',
						'=ID' => intval($ID),
						'NAME.LANGUAGE_ID' => trim($strLang)
					),
					'select' => array(
						'ID',
						'ID_' => 'NAME.ID',
						'LID' => 'NAME.LANGUAGE_ID',
						'LNAME' => 'NAME.NAME',
						'SHORT_NAME' => 'NAME.SHORT_NAME',
					)
				));

				$item = $res->fetch();

				if($item)
				{
					return array(
						'ID' => $item['ID_'],
						'COUNTRY_ID' => $item['ID'],
						'LID' => $item['LID'],
						'NAME' => $item['LNAME'],
						'SHORT_NAME' => $item['SHORT_NAME'],
					);
				}

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{

			global $DB;

			$ID = IntVal($ID);
			$strLang = Trim($strLang);

			$strSql =
				"SELECT * ".
				"FROM b_sale_location_country_lang ".
				"WHERE COUNTRY_ID = ".$ID." ".
				"	AND LID = '".$DB->ForSql($strLang, 2)."' ";
			$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if ($res = $db_res->Fetch())
			{
				return $res;
			}
			return False;

		}
	}

	/////////////////////////////////////////////
	
	function RegionCheckFields($ACTION, &$arFields)
	{
		if ((is_set($arFields, "NAME") || $ACTION=="ADD") && strlen($arFields["NAME"])<=0) return false;

		return True;
	}

	function UpdateRegion($ID, $arFields)
	{
		global $DB;

		$ID = intval($ID);

		if ($ID <= 0 || !CSaleLocation::RegionCheckFields("UPDATE", $arFields))
			return false;

		foreach (GetModuleEvents("sale", "OnBeforeRegionUpdate", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID, &$arFields))===false)
				return false;

		if(self::isLocationProMigrated())
		{
			try
			{
				$locId = self::getLocationIdByRegionId($ID);

				if(!$locId)
					return false;

				$res = Location\LocationTable::update(
					$locId,
					self::refineFieldsForSaveCRC($item['ID'], $arFields), 
					array('REBALANCE' => false)
				);

				if($res->isSuccess())
					return $ID;

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			$strUpdate = $DB->PrepareUpdate("b_sale_location_region", $arFields);
			$strSql = "UPDATE b_sale_location_region SET ".$strUpdate." WHERE ID = ".$ID."";
			$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			$db_lang = CLangAdmin::GetList(($b="sort"), ($o="asc"), array("ACTIVE" => "Y"));
			while ($arLang = $db_lang->Fetch())
			{
				if ($arCntLang = CSaleLocation::GetRegionLangByID($ID, $arLang["LID"]))
				{
					$strUpdate = $DB->PrepareUpdate("b_sale_location_region_lang", $arFields[$arLang["LID"]]);

					$strSql = "UPDATE b_sale_location_region_lang SET ".$strUpdate." WHERE ID = ".$arCntLang["ID"]."";
				}
				else
				{
					$arInsert = $DB->PrepareInsert("b_sale_location_region_lang", $arFields[$arLang["LID"]]);
					$strSql =
						"INSERT INTO b_sale_location_region_lang(REGION_ID, ".$arInsert[0].") ".
						"VALUES(".$ID.", ".$arInsert[1].")";
				}
				$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			}
		}

		foreach (GetModuleEvents("sale", "OnRegionUpdate", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID, $arFields));

		return $ID;
	}

	function DeleteRegion($ID)
	{
		// there is no such entity in terms of location 2.0, so... just delete old entity

		global $DB;
		$ID = IntVal($ID);

		foreach (GetModuleEvents("sale", "OnBeforeRegionDelete", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID))===false)
				return false;

		if(self::isLocationProMigrated())
		{
			try
			{
				/*
				$locId = self::getLocationIdByRegionId($ID);

				if(!$locId)
					return false;

				// just "unlink" it, so CSaleLocation::GetCityByID() wont able to find it
				$res = Location\LocationTable::update($locId, array(
					'REGION_ID' => ''
				));

				return $res->isSuccess();
				*/
				return true;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		
		// and also drop old records, if any
		$DB->Query("DELETE FROM b_sale_location_region_lang WHERE REGION_ID = ".$ID."", true);
		$bDelete = $DB->Query("DELETE FROM b_sale_location_region WHERE ID = ".$ID."", true);

		foreach (GetModuleEvents("sale", "OnRegionDelete", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID));

		return $bDelete;
	}

	function GetRegionByID($ID)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				$res = Location\LocationTable::getList(array(
					'filter' => array(
						'=TYPE.CODE' => 'REGION',
						'=ID' => intval($ID),
						'NAME.LANGUAGE_ID' => self::ORIGIN_NAME_LANGUAGE_ID
					),
					'select' => array(
						'ID',
						'LNAME' => 'NAME.NAME',
						'SHORT_NAME' => 'NAME.SHORT_NAME',
					)
				));
				$res->addReplacedAliases(array('LNAME' => 'NAME'));

				$item = $res->fetch();

				if($item)
					return $item;

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			global $DB;

			$ID = IntVal($ID);
			$strSql =
				"SELECT * ".
				"FROM b_sale_location_region ".
				"WHERE ID = ".$ID." ";
			$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if ($res = $db_res->Fetch())
			{
				return $res;
			}
			return False;
		}
	}

	function GetRegionLangByID($ID, $strLang = LANGUAGE_ID)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				$res = Location\LocationTable::getList(array(
					'filter' => array(
						'=TYPE.CODE' => 'REGION',
						'=ID' => intval($ID),
						'NAME.LANGUAGE_ID' => trim($strLang)
					),
					'select' => array(
						'ID',
						'ID_' => 'NAME.ID',
						'LID' => 'NAME.LANGUAGE_ID',
						'LNAME' => 'NAME.NAME',
						'SHORT_NAME' => 'NAME.SHORT_NAME',
					)
				));
				$res->addReplacedAliases(array('LNAME' => 'NAME'));

				$item = $res->fetch();

				if($item)
				{
					return array(
						'ID' => $item['ID_'],
						'REGION_ID' => $item['ID'],
						'LID' => $item['LID'],
						'NAME' => $item['LNAME'],
						'SHORT_NAME' => $item['SHORT_NAME'],
					);
				}

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			global $DB;

			$ID = IntVal($ID);
			$strLang = Trim($strLang);

			$strSql =
				"SELECT * ".
				"FROM b_sale_location_region_lang ".
				"WHERE REGION_ID = ".$ID." ".
				" AND LID = '".$DB->ForSql($strLang, 2)."' ";
			$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if ($res = $db_res->Fetch())
			{
				return $res;
			}
			return False;
		}
	}

	/////////////////////////////////////////////

	function CityCheckFields($ACTION, &$arFields)
	{
		global $DB;

		if ((is_set($arFields, "NAME") || $ACTION=="ADD") && strlen($arFields["NAME"])<=0) return false;

		/*
		$db_lang = CLangAdmin::GetList(($b="sort"), ($o="asc"), array("ACTIVE" => "Y"));
		while ($arLang = $db_lang->Fetch())
		{
			if ((is_set($arFields[$arLang["LID"]], "NAME") || $ACTION=="ADD") && strlen($arFields[$arLang["LID"]]["NAME"])<=0) return false;
		}
		*/

		return True;
	}

	function UpdateCity($ID, $arFields)
	{
		global $DB;

		$ID = intval($ID);

		if ($ID <= 0 || !CSaleLocation::CityCheckFields("UPDATE", $arFields))
			return false;

		foreach (GetModuleEvents("sale", "OnBeforeCityUpdate", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID, &$arFields))===false)
				return false;

		if(self::isLocationProMigrated())
		{
			try
			{
				$locId = self::getLocationIdByCityId($ID);

				if(!$locId)
					return false;

				$res = Location\LocationTable::update(
					$locId,
					self::refineFieldsForSaveCRC($item['ID'], $arFields), 
					array('REBALANCE' => false)
				);

				if($res->isSuccess())
					return $ID;

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			$strUpdate = $DB->PrepareUpdate("b_sale_location_city", $arFields);
			$strSql = "UPDATE b_sale_location_city SET ".$strUpdate." WHERE ID = ".$ID."";
			$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			$db_lang = CLangAdmin::GetList(($b="sort"), ($o="asc"), array("ACTIVE" => "Y"));
			while ($arLang = $db_lang->Fetch())
			{
				if ($arCntLang = CSaleLocation::GetCityLangByID($ID, $arLang["LID"]))
				{
					$strUpdate = $DB->PrepareUpdate("b_sale_location_city_lang", $arFields[$arLang["LID"]]);
					$strSql = "UPDATE b_sale_location_city_lang SET ".$strUpdate." WHERE ID = ".$arCntLang["ID"]."";
				}
				else
				{
					$arInsert = $DB->PrepareInsert("b_sale_location_city_lang", $arFields[$arLang["LID"]]);
					$strSql =
						"INSERT INTO b_sale_location_city_lang(CITY_ID, ".$arInsert[0].") ".
						"VALUES(".$ID.", ".$arInsert[1].")";
				}
				$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			}
		}

		foreach (GetModuleEvents("sale", "OnCityUpdate", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID, $arFields));

		return $ID;
	}

	function DeleteCity($ID)
	{
		// there is no such entity in terms of location 2.0, so... just delete old entity

		global $DB;
		$ID = IntVal($ID);

		foreach (GetModuleEvents("sale", "OnBeforeCityDelete", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID))===false)
				return false;

		if(self::isLocationProMigrated())
		{
			try
			{
				/*
				$locId = self::getLocationIdByCityId($ID);

				if(!$locId)
					return false;

				// just "unlink" it, so CSaleLocation::GetCityByID() wont able to find it
				$res = Location\LocationTable::update($locId, array(
					'CITY_ID' => ''
				));

				return $res->isSuccess();
				*/

				return true;
			}
			catch(Exception $e)
			{
				return false;
			}
		}

		// and also drop old records, if any
		$DB->Query("DELETE FROM b_sale_location_city_lang WHERE CITY_ID = ".$ID."", true);
		$bDelete = $DB->Query("DELETE FROM b_sale_location_city WHERE ID = ".$ID."", true);

		foreach (GetModuleEvents("sale", "OnCityDelete", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID));

		return $bDelete;
	}

	function GetCityByID($ID)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				$res = Location\LocationTable::getList(array(
					'filter' => array(
						'=TYPE.CODE' => 'CITY',
						'=ID' => intval($ID),
						'NAME.LANGUAGE_ID' => self::ORIGIN_NAME_LANGUAGE_ID
					),
					'select' => array(
						'ID',
						'LNAME' => 'NAME.NAME',
						'SHORT_NAME' => 'NAME.SHORT_NAME'
					)
				));
				$res->addReplacedAliases(array('LNAME' => 'NAME'));

				$item = $res->fetch();

				if($item)
					return $item;

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			global $DB;

			$ID = IntVal($ID);
			$strSql =
				"SELECT * ".
				"FROM b_sale_location_city ".
				"WHERE ID = ".$ID." ";
			$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if ($res = $db_res->Fetch())
			{
				return $res;
			}
			return False;
		}
	}

	function GetCityLangByID($ID, $strLang = LANGUAGE_ID)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				$res = Location\LocationTable::getList(array(
					'filter' => array(
						'=TYPE.CODE' => 'CITY',
						'=ID' => intval($ID),
						'NAME.LANGUAGE_ID' => trim($strLang)
					),
					'select' => array(
						'ID',
						'ID_' => 'NAME.ID',
						'LID' => 'NAME.LANGUAGE_ID',
						'LNAME' => 'NAME.NAME',
						'SHORT_NAME' => 'NAME.SHORT_NAME',
					)
				));
				$res->addReplacedAliases(array('LNAME' => 'NAME'));

				$item = $res->fetch();

				if($item)
				{
					return array(
						'ID' => $item['ID_'],
						'CITY_ID' => $item['ID'],
						'LID' => $item['LID'],
						'NAME' => $item['LNAME'],
						'SHORT_NAME' => $item['SHORT_NAME'],
					);
				}

				return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			global $DB;

			$ID = IntVal($ID);
			$strLang = Trim($strLang);

			$strSql =
				"SELECT * ".
				"FROM b_sale_location_city_lang ".
				"WHERE CITY_ID = ".$ID." ".
				"	AND LID = '".$DB->ForSql($strLang, 2)."' ";
			$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			if ($res = $db_res->Fetch())
			{
				return $res;
			}
			return False;
		}
	}

	/////////////////////////////////////////////

	private static function processOrderForGetList($arOrder, $fieldMap = array(), $fieldProxy = array())
	{
		if(is_array($arOrder))
		{
			$arOrderParsed = array();
			$fieldProxy = array_flip($fieldProxy);

			// parse order and stirp away unknown fields
			$arOrder = array_change_key_case($arOrder, CASE_UPPER);
			foreach($arOrder as $fld => $direction)
			{
				$direction = ToUpper($direction);

				if($direction != 'ASC' && $direction != 'DESC')
					continue;

				if(isset($fieldProxy[$fld]))
					$fld = $fieldProxy[$fld];

				if(!isset($fieldMap[$fld]))
					continue;

				$fld = $fieldMap[$fld];

				$arOrderParsed[$fld] = $direction;
			}

			return $arOrderParsed;
		}
		else
			return false;
	}

	public static function processFilterForGetList($arFilter, $fieldMap = array(), $fieldProxy = array(), $query = null)
	{
		if(is_array($arFilter))
		{
			$filterFieldsClean = array();
			$arFilterParsed = array();
			$pseudoFields = array();
			$fieldProxy = array_flip($fieldProxy);

			$dbConnection = Main\HttpApplication::getConnection();
			$dbHelper = $dbConnection->getSqlHelper();

			// parse filter and stirp away unknown fields
			$arFilter = array_change_key_case($arFilter, CASE_UPPER);
			foreach($arFilter as $fld => $value)
			{
				$fld = trim($fld);
				$fld = preg_replace(self::LEADING_TILDA_SEARCH_R, '', $fld);

				$found = array();
				preg_match(self::MODIFIER_SEARCH_R, $fld, $found);

				$modifier = strlen($found[1]) ? $found[1] : '';
				$fldClean = preg_replace(self::MODIFIER_SEARCH_R, '', $fld);

				if(isset($fieldProxy[$fldClean]))
					$fldClean = $fieldProxy[$fldClean];

				if(!isset($fieldMap[$fldClean]))
					continue;

				$fldClean = $fieldMap[$fldClean];

				if($modifier == '+') // ORM does not understand + modifier in a filter
				{
					if($query != null)
					{
						// $value could be array or scalar

						if(is_array($value))
						{
							foreach($value as $i => $v)
							{
								$value[$i] = "'".$dbHelper->forSql($v)."'";
							}
							$value = 'in ('.implode(', ', $value).')';
						}
						else
							$value = '= '.$dbHelper->forSql($value);

						$query->registerRuntimeField('PLUS_'.$fldClean, array(
							'data_type' => 'integer',
							'expression' => array(
								"case when ((%s ".$value.") or (%s is null) or (%s = 0)) then 1 else 0 end",
								$fldClean,
								$fldClean,
								$fldClean
							)
						));
						$arFilterParsed['=PLUS_'.$fldClean] = 1;
					}

					continue;
				}

				$arFilterParsed[$modifier.$fldClean] = $value;
				$filterFieldsClean[] = $fldClean;
			}

			return array($arFilterParsed, $filterFieldsClean, $pseudoFields);
		}
		else
			return array(false, array(), array());
	}

	public static function processSelectForGetList($arSelectFields, $fieldMap)
	{
		$selectFields = array();
		if(is_array($arSelectFields) && !empty($arSelectFields) && !in_array('*', $arSelectFields, true))
		{
			$arSelectFields = array_map("strtoupper", $arSelectFields);
			foreach($arSelectFields as $fld)
			{
				if(isset($fieldMap[$fld])) // legal one
					$selectFields[$fld] = $fieldMap[$fld];
			}
		}
		else
			$selectFields = $fieldMap;

		$select = array();
		foreach($selectFields as $fldFrom => $fldTo)
		{
			if($fldFrom == $fldTo)
				$select[] = $fldTo;
			else
				$select[$fldFrom] = $fldTo;
		}

		return $select;
	}

	private static function proxyFieldsInResult($res, $fieldProxy = array())
	{
		if(!is_array($fieldProxy) || empty($fieldProxy))
			return $res;

		$result = array(); 
		while($item = $res->fetch())
		{
			$pItem = array();
			foreach($item as $k => $v)
			{
				if(isset($fieldProxy[$k]))
					$pItem[$fieldProxy[$k]] = $v;
				else
					$pItem[$k] = $v;
			}

			$result[] = $pItem;
		}

		return new DB\ArrayResult($result);
	}

	private static function stripModifiers($filter)
	{
		if(is_array($filter) && !empty($filter))
		{
			$result = array();
			foreach($filter as $k => $v)
			{
				$k = preg_replace(self::MODIFIER_SEARCH_R, '', $k);
				$result[$k] = $v;
			}

			return $result;
		}

		return $filter;
	}

	/////////////////////////////////////////////

	function GetList(
		$arOrder = array("SORT"=>"ASC", "COUNTRY_NAME_LANG"=>"ASC", "CITY_NAME_LANG"=>"ASC"),
		$arFilter = array(),
		$arGroupBy = false,
		$arNavStartParams = false,
		$arSelectFields = array()
	)
	{
		$query = new Entity\Query(self::SELF_ENTITY_NAME);

		////////////////////
		// mapping
		////////////////////

		$fieldMap = array(
			'ID' => 'ID',
			'COUNTRY_ID_' => 'TO_COUNTRY.ID',
			'REGION_ID_' => 'TO_REGION.ID',
			'CITY_ID_' => 'TO_CITY.ID',
			'SORT' => 'SORT',
			'COUNTRY_NAME_ORIG' => 'COUNTRY_NAME_ORIG_.NAME',
			'COUNTRY_SHORT_NAME' => 'COUNTRY_NAME_.SHORT_NAME',
			'REGION_NAME_ORIG' => 'REGION_NAME_ORIG_.NAME',
			'CITY_NAME_ORIG' => 'CITY_NAME_ORIG_.NAME',
			'REGION_SHORT_NAME' => 'REGION_NAME_.SHORT_NAME',
			'CITY_SHORT_NAME' => 'CITY_NAME_.SHORT_NAME',
			'COUNTRY_LID' => 'COUNTRY_NAME_.LANGUAGE_ID',
			'COUNTRY_NAME' => 'COUNTRY_NAME_.NAME',
			'COUNTRY_NAME_LANG' => 'COUNTRY_NAME_.NAME',
			'REGION_LID' => 'REGION_NAME_.LANGUAGE_ID',
			'CITY_LID' => 'CITY_NAME_.LANGUAGE_ID',
			'REGION_NAME' => 'REGION_NAME_.NAME',
			'REGION_NAME_LANG' => 'REGION_NAME_.NAME',
			'CITY_NAME' => 'CITY_NAME_.NAME',
			'CITY_NAME_LANG' => 'CITY_NAME_.NAME',
			'LOC_DEFAULT' => 'LOC_DEFAULT'
		);
		$fieldProxy = array(
			'COUNTRY_ID_' => 'COUNTRY_ID',
			'REGION_ID_' => 'REGION_ID',
			'CITY_ID_' => 'CITY_ID',
		);
		$fieldSum = array_unique(array_merge(
			is_array($arOrder) ? array_keys($arOrder) : array(), 
			is_array($arFilter) ? array_keys(self::stripModifiers($arFilter)) : array(),
			is_array($arGroupBy) ? $arGroupBy : array(),
			is_array($arSelectFields) ? array_keys($arSelectFields) : array()
		));

		$types = self::getTypes();

		if(empty($types) || !isset($types['COUNTRY']) || !isset($types['REGION']) || !isset($types['CITY']))
		{
			// no types, conditions will break
			$res = new CDBResult();
			$res->InitFromArray(array());
			return $res;
		}

		////////////////////
		// select
		////////////////////

		if(in_array('COUNTRY_ID', $arSelectFields))
			$arSelectFields[] = 'COUNTRY_ID_';

		if(in_array('REGION_ID', $arSelectFields))
			$arSelectFields[] = 'REGION_ID_';

		if(in_array('CITY_ID', $arSelectFields))
			$arSelectFields[] = 'CITY_ID_';

		$selectFields = self::processSelectForGetList($arSelectFields, $fieldMap);

		////////////////////
		// filter
		////////////////////
		$filterByLang = false;

		// function may have another signature
		if(is_string($arGroupBy) && strlen($arGroupBy) == 2)
		{
			$filterByLang = $arGroupBy;
			$arGroupBy = false;
		}

		if(strlen($arFilter['LID']))
		{
			$filterByLang = $arFilter['LID'];
			unset($arFilter['LID']);
		}

		if(isset($arFilter['COUNTRY_ID']))
		{
			$arFilter['COUNTRY_ID_'] = $arFilter['COUNTRY_ID'];
			unset($arFilter['COUNTRY_ID']);
		}
		if(isset($arFilter['REGION_ID']))
		{
			$arFilter['REGION_ID_'] = $arFilter['REGION_ID'];
			unset($arFilter['REGION_ID']);
		}
		if(isset($arFilter['CITY_ID']))
		{
			$arFilter['CITY_ID_'] = $arFilter['CITY_ID'];
			unset($arFilter['CITY_ID']);
		}


		list($filterFields, $filterFieldsClean) = self::processFilterForGetList($arFilter, $fieldMap, array(), $query);

		$filterFields['TYPE_ID'] = array($types['COUNTRY'], $types['REGION'], $types['CITY']);

		////////////////////
		// order
		////////////////////
		$orderFields = self::processOrderForGetList($arOrder, $fieldMap);

		////////////////////
		// group
		////////////////////
		$groupFields = false;
		$showCount = false;
		if(is_array($arGroupBy))
		{
			$arGroupBy = array_map("strtoupper", $arGroupBy);
			$arGroupBy = array_change_key_case($arGroupBy, CASE_UPPER);

			if(empty($arGroupBy))
			{
				$query->registerRuntimeField(
						'CNT',
						array(
							'data_type' => 'integer',
							'expression' => array(
								'COUNT(*)'
							)
						)
					);

				$selectFields = array('CNT');
				$showCount = true;
			}
			else
			{
				$funcList = array('COUNT', 'AVG', 'MIN', 'MAX', 'SUM');
				$groupFields = array();
				$selectFields = array();
				foreach($arGroupBy as $k => $fld)
				{
					if(isset($fieldMap[$fld]))
					{
						if(in_array($k, $funcList, true))
						{
							$f = $fld.'_GROUP';

							$query->registerRuntimeField(
									$f,
									array(
										'data_type' => 'integer',
										'expression' => array(
											$k.'(%s)',
											$fld
										)
									)
								);

							$selectFields[] = $f;
							$fieldProxy[$f] = $fld; // orm has a limitation: cannot add field alias that is equal to an existing field name
						}
						else
						{
							$selectFields[] = $fld;
							$groupFields[] = $fld;
						}
					}
				}
			}

			//$orderFields = false;
		}

		////////////////////
		// less joins much joy
		////////////////////

		// selected fields
		$sselect = array();
		foreach($selectFields as $k => $v)
		{
			if(is_numeric($k))
				$sselect[] = $v;
			else
				$sselect[] = $k;
		}

		$fieldSum = array_unique(array_merge(
			$fieldSum,
			is_array($orderFields) ? array_keys($orderFields) : array(),
			is_array($filterFields) ? $filterFieldsClean : array(),
			is_array($groupFields) ? $groupFields : array(),
			$sselect
		));

		$fetchCountryLangNames = self::checkLangPresenceInSelect('COUNTRY', $fieldSum);
		$fetchRegionLangNames = self::checkLangPresenceInSelect('REGION', $fieldSum);
		$fetchCityLangNames = self::checkLangPresenceInSelect('CITY', $fieldSum);

		$fetchCountryOrigNames = in_array('COUNTRY_NAME_ORIG', $fieldSum, true);
		$fetchRegionOrigNames = in_array('REGION_NAME_ORIG', $fieldSum, true);
		$fetchCityOrigNames = in_array('REGION_NAME_ORIG', $fieldSum, true);

		$fetchDefault = in_array('LOC_DEFAULT', $fieldSum, true);

		////////////////////
		// query
		////////////////////

		if($fetchCountryLangNames || $fetchCountryOrigNames || in_array('COUNTRY_ID', $fieldSum, true) || in_array('COUNTRY_ID_', $fieldSum, true))
		{
			$query->registerRuntimeField(
					'TO_COUNTRY',
					array(
						'data_type' => self::SELF_ENTITY_NAME,
						'reference' => array(

							// it should be ancestor
							'<=ref.LEFT_MARGIN' => 'this.LEFT_MARGIN',
							'>=ref.RIGHT_MARGIN' => 'this.RIGHT_MARGIN',

							// it should be COUNTRY
							'=ref.TYPE_ID' => array('?', $types['COUNTRY']),
						),
						'join_type' => 'left'
					)
				);
		}

		if($fetchRegionLangNames || $fetchRegionOrigNames || in_array('REGION_ID', $fieldSum, true) || in_array('REGION_ID_', $fieldSum, true))
		{
			$query->registerRuntimeField(
				'TO_REGION',
				array(
					'data_type' => self::SELF_ENTITY_NAME,
					'reference' => array(

						// REGION search for COUNTRY is meaningless
						array(
							'LOGIC' => 'OR',
							array(
								'=this.TYPE_ID' => array('?', $types['REGION']),
							),
							array(
								'=this.TYPE_ID' => array('?', $types['CITY']),
							),
						),

						// it should be ancestor
						'<=ref.LEFT_MARGIN' => 'this.LEFT_MARGIN',
						'>=ref.RIGHT_MARGIN' => 'this.RIGHT_MARGIN',

						// it should be REGION
						'=ref.TYPE_ID' => array('?', $types['REGION']),
					),
					'join_type' => 'left'
				)
			);
		}

		if($fetchCityLangNames || $fetchCityOrigNames  || in_array('CITY_ID', $fieldSum, true) || in_array('CITY_ID_', $fieldSum, true))
		{
			$query->registerRuntimeField(
				'TO_CITY',
				array(
					'data_type' => self::SELF_ENTITY_NAME,
					'reference' => array(

						// if we seach for CITY, it is a CITY itself
						'=this.TYPE_ID' => array('?', $types['CITY']),
						'=this.ID' => 'ref.ID'
					),
					'join_type' => 'left'
				)
			);
		}
		////////////////////////////////////
		////////////////////////////////////
		////////////////////////////////////

		// lang names

		if($fetchCountryLangNames)
		{
			$joinCondition = array();
			if($filterByLang !== false)
				$joinCondition['=ref.LANGUAGE_ID'] = array('?', $filterByLang);
			$joinCondition['=this.TO_COUNTRY.ID'] = 'ref.LOCATION_ID';

			$query->registerRuntimeField(
				'COUNTRY_NAME_',
				array(
					'data_type' => self::NAME_ENTITY_NAME,
					'reference' => $joinCondition,
					'join_type' => 'left'
				)
			);
		}

		if($fetchRegionLangNames)
		{
			$joinCondition = array();
			if($filterByLang !== false)
				$joinCondition['=ref.LANGUAGE_ID'] = array('?', $filterByLang);
			$joinCondition['=this.TO_REGION.ID'] = 'ref.LOCATION_ID';

			$query->registerRuntimeField(
				'REGION_NAME_',
				array(
					'data_type' => self::NAME_ENTITY_NAME,
					'reference' => $joinCondition,
					'join_type' => 'left'
				)
			);
		}

		if($fetchCityLangNames)
		{
			$joinCondition = array();
			if($filterByLang !== false)
				$joinCondition['=ref.LANGUAGE_ID'] = array('?', $filterByLang);
			$joinCondition['=this.TO_CITY.ID'] = 'ref.LOCATION_ID';

			$query->registerRuntimeField(
				'CITY_NAME_',
				array(
					'data_type' => self::NAME_ENTITY_NAME,
					'reference' => $joinCondition,
					'join_type' => 'left'
				)
			);
		}

		// origin names
		if($fetchCountryOrigNames)
		{
			$query->registerRuntimeField(
				'COUNTRY_NAME_ORIG_',
				array(
					'data_type' => self::NAME_ENTITY_NAME,
					'reference' => array(
						'=this.TO_COUNTRY.ID' => 'ref.LOCATION_ID',
						'=ref.LANGUAGE_ID' => array('?', self::ORIGIN_NAME_LANGUAGE_ID)
					),
					'join_type' => 'left'
				)
			);
		}

		if($fetchRegionOrigNames)
		{
			$query->registerRuntimeField(
				'REGION_NAME_ORIG_',
				array(
					'data_type' => self::NAME_ENTITY_NAME,
					'reference' => array(
						'=this.TO_REGION.ID' => 'ref.LOCATION_ID',
						'=ref.LANGUAGE_ID' => array('?', self::ORIGIN_NAME_LANGUAGE_ID)
					),
					'join_type' => 'left'
				)
			);
		}

		if($fetchCityOrigNames)
		{
			$query->registerRuntimeField(
				'CITY_NAME_ORIG_',
				array(
					'data_type' => self::NAME_ENTITY_NAME,
					'reference' => array(
						'=this.TO_CITY.ID' => 'ref.LOCATION_ID',
						'=ref.LANGUAGE_ID' => array('?', self::ORIGIN_NAME_LANGUAGE_ID)
					),
					'join_type' => 'left'
				)
			);
		}

		if($fetchDefault)
		{
			$query->registerRuntimeField(
				'DEFAULT_ID',
				array(
					'data_type' => self::DEFAULT_SITE_ENTITY_NAME,
					'reference' => array(
						'=this.CODE' => 'ref.LOCATION_CODE'
					),
					'join_type' => 'left'
				)
			);
			$query->registerRuntimeField('LOC_DEFAULT', array(
				'data_type' => 'string',
				'expression' => array(
					"CASE WHEN %s IS NULL THEN 'N' ELSE 'Y' END",
					'DEFAULT_ID.LOCATION_CODE'
				),
			));
		}

		////////////////////////////////////
		////////////////////////////////////
		////////////////////////////////////

		$query->setSelect($selectFields);

		if($filterFields !== false)
			$query->setFilter($filterFields);

		if($orderFields !== false)
			$query->setOrder($orderFields);

		if($groupFields !== false)
			$query->setGroup($groupFields);

		////////////////////
		// nav
		////////////////////

		if(!$showCount && is_array($arNavStartParams) && !empty($arNavStartParams))
		{
			if(isset($arNavStartParams['nTopCount']))
			{
				$query->setLimit(intval($arNavStartParams['nTopCount']));
				$res = $query->exec();
			}
			else
			{
				// more complicated nav with outer args involved
				$res = new CDBResult();
				$res->NavQuery(
					$query->getQuery(),
					self::GetList(false, $arFilter, array(), false, array('ID')), // returns record count actually
					$arNavStartParams
				);
			}
		}
		else
			$res = $query->exec();

		if($showCount)
		{
			$item = $res->fetch();
			return $item['CNT'];
		}

		$res->addReplacedAliases($fieldProxy);

		return new CDBResult($res); // we want GetNext() method supported by the result
	}

	function GetByID($id, $strLang = LANGUAGE_ID)
	{
		if(!($id = intval($id)))
			return false;

		$filter = array('ID' => $id);
		if(strlen($strLang))
			$filter['LID'] = $strLang;

		return self::GetList(false, $filter)->fetch();
	}

	function LocationCheckFields($ACTION, &$arFields)
	{
		global $DB;

		if ((is_set($arFields, "SORT") || $ACTION=="ADD") && IntVal($arFields["SORT"])<=0) $arFields["SORT"] = 100;
		if (is_set($arFields, "COUNTRY_ID")) $arFields["COUNTRY_ID"] = IntVal($arFields["COUNTRY_ID"]);
		if (is_set($arFields, "CITY_ID")) $arFields["CITY_ID"] = IntVal($arFields["CITY_ID"]);

		return True;
	}

	function UpdateLocation($ID, $arFields)
	{
		// it seems that method is okay... we probably want to move region and city as it set in $arFields, but then we`ll have to adjsut the rest of locations

		global $DB;

		$ID = intval($ID);

		if ($ID <= 0 || !CSaleLocation::LocationCheckFields("UPDATE", $arFields))
			return false;

		foreach (GetModuleEvents("sale", "OnBeforeLocationUpdate", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID, &$arFields))===false)
				return false;

		$strUpdate = $DB->PrepareUpdate("b_sale_location", $arFields);
		$strSql = "UPDATE b_sale_location SET ".$strUpdate." WHERE ID = ".$ID."";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		foreach (GetModuleEvents("sale", "OnLocationUpdate", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID, $arFields));

		return $ID;
	}

	function CheckFields($ACTION, &$arFields)
	{
		global $DB;

		if (is_set($arFields, "CHANGE_COUNTRY") && $arFields["CHANGE_COUNTRY"]!="Y")
			$arFields["CHANGE_COUNTRY"] = "N";
		if (is_set($arFields, "WITHOUT_CITY") && $arFields["WITHOUT_CITY"]!="Y")
			$arFields["WITHOUT_CITY"] = "N";

		if (is_set($arFields, "COUNTRY_ID"))
			$arFields["COUNTRY_ID"] = trim($arFields["COUNTRY_ID"]);
		//	$arFields["COUNTRY_ID"] = IntVal($arFields["COUNTRY_ID"]);

		if (is_set($arFields, "CHANGE_COUNTRY") && $arFields["CHANGE_COUNTRY"]=="Y"
			&& (!is_set($arFields, "COUNTRY_ID") || $arFields["COUNTRY_ID"]<=0))
			return false;

		return True;
	}

	private static function AddLocationPart($creat, $type, $parent, $sort)
	{
		$langs = self::getLanguages();
		$types = self::getTypes();

		$creatFlds = array();
		if(is_numeric($creat))
		{

			// check whether location exists...
			$res = Location\LocationTable::getList(array('filter' => array('='.$type.'_ID' => $creat, 'TYPE_ID' => $types[$type]), 'select' => array('ID'), 'limit' => 1))->fetch();
			if($res['ID'])
			{
				$parent = intval($res['ID']);
			}
			else
			{
				if($type == 'COUNTRY')
					$res = self::GetCountryByID($creat); //!!!
				elseif($type == 'REGION')
					$res = self::GetRegionByID($creat); //!!!
				elseif($type == 'CITY')
					$res = self::GetCityByID($creat); //!!!

				if(!$res) // no such type exists, smth strange
					throw new Exception('No such '.$type);

				// create location using type found
				$creatFlds[$type.'_ID'] = $res['ID'];

				$creatFlds['NAME'] = array();
				foreach($langs as $lid)
				{
					if($type == 'COUNTRY')
						$name = self::GetCountryLangByID($res['ID'], $lid); //!!!
					elseif($type == 'REGION')
						$name = self::GetRegionLangByID($res['ID'], $lid); //!!!
					elseif($type == 'CITY')
						$name = self::GetCityLangByID($res['ID'], $lid); //!!!

					$creatFlds['NAME'][$lid] = array(
						'NAME' => $name['NAME'],
						'SHORT_NAME' => $name['SHORT_NAME']
					);
				}
			}
		}
		elseif(is_array($creat)) // should create type
		{
			$creatFlds[$type.'_ID'] = self::getFreeId($type);
			$creatFlds['NAME'] = array();

			foreach($creat as $lid => $name)
			{
				$creatFlds['NAME'][$lid] = array(
					'NAME' => $name['NAME'],
					'SHORT_NAME' => $name['SHORT_NAME']
				);
			}
		}

		if(!empty($creatFlds))
		{
			$creatFlds['PARENT_ID'] = $parent;
			$creatFlds['TYPE_ID'] = $types[$type];

			$creatFlds['CODE'] = rand(999,99999999);

			if($sort !== false)
				$creatFlds['SORT'] = $sort;

			$res = Location\LocationTable::add($creatFlds);
			if(!$res->isSuccess())
				throw new Exception('Cannot add location');

			$parent = $res->getId();
			Location\LocationTable::update($parent, array('CODE' => $parent));
		}

		return $parent;
	}

	function Add($arFields)
	{
		global $DB;

		if (!CSaleLocation::CheckFields("ADD", $arFields))
			return false;

		if(self::isLocationProMigrated())
		{
			try
			{
				$country = self::getTypeValueToStore('COUNTRY', $arFields);
				$region = self::getTypeValueToStore('REGION', $arFields);
				$city = self::getTypeValueToStore('CITY', $arFields);

				// Let`s treat a location 1.0 structure as a static structure where you can not move nodes up\down 
				// along a tree by passing just IDs in triplets like (COUNTRY_ID, REGION_ID, CITY_ID).
				// Then parse out some meaningless situations to preserve tree integrity:

				// you cann not add existing region to a non-existing country
				if($region && is_numeric($region) && $country && is_array($country))
					throw new Exception;

				// you cann not add existing city to a non-existing country
				if($city && is_numeric($city) && $country && is_array($country))
					throw new Exception;

				// you cann not add existing city to a non-existing region
				if($city && is_numeric($city) && $region && is_array($region))
					throw new Exception;

				$parent = 0;
				$sort = isset($arFields['SORT']) ? intval($arFields['SORT']) : false;

				if($country)
				{
					$parent = self::AddLocationPart($country, 'COUNTRY', $parent, $sort);
				}

				if($region)
				{
					$parent = self::AddLocationPart($region, 'REGION', $parent, $sort);
				}

				if($city)
				{
					$parent = self::AddLocationPart($city, 'CITY', $parent, $sort);
				}

				return $parent;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{

			if ((!is_set($arFields, "COUNTRY_ID") || IntVal($arFields["COUNTRY_ID"])<=0) && strlen($arFields["COUNTRY_ID"]) > 0)
			{
				$arFields["COUNTRY_ID"] = CSaleLocation::AddCountry($arFields["COUNTRY"]);
				if (IntVal($arFields["COUNTRY_ID"])<=0) return false;

				if ($arFields["WITHOUT_CITY"]!="Y" && strlen($arFields["REGION_ID"]) <= 0)
				{
					UnSet($arFields["CITY_ID"]);
					CSaleLocation::AddLocation($arFields);
				}
			}

			if ($arFields["REGION_ID"] <= 0 && $arFields["REGION_ID"] != "")
			{
				$arFields["REGION_ID"] = CSaleLocation::AddRegion($arFields["REGION"]);
				if (IntVal($arFields["REGION_ID"])<=0) return false;

				if ($arFields["WITHOUT_CITY"] != "Y")
				{
					//$arFieldsTmp = $arFields;
					UnSet($arFields["CITY_ID"]);
					CSaleLocation::AddLocation($arFields);
				}
			}
			elseif ($arFields["REGION_ID"] == '')
			{
				UnSet($arFields["REGION_ID"]);
			}

			if ($arFields["WITHOUT_CITY"]!="Y")
			{
				if (IntVal($arFields["REGION_ID"]) > 0)
					$arFields["CITY"]["REGION_ID"] = $arFields["REGION_ID"];
				$arFields["CITY_ID"] = CSaleLocation::AddCity($arFields["CITY"]);
				if (IntVal($arFields["CITY_ID"])<=0) return false;
			}
			else
			{
				UnSet($arFields["CITY_ID"]);
			}

			$ID = CSaleLocation::AddLocation($arFields);

			return $ID;

		}
	}

	function Update($ID, $arFields)
	{
		global $DB;

		if (!CSaleLocation::CheckFields("UPDATE", $arFields)) return false;

		if (!($arLocRes = CSaleLocation::GetByID($ID, LANGUAGE_ID))) return false;

		if(self::isLocationProMigrated())
		{
			try
			{
				// only partial support: name update functionality

				if(($arFields["CHANGE_COUNTRY"]=="Y" || intval($arFields["COUNTRY_ID"])) && is_array($arFields["COUNTRY"]))
					CSaleLocation::UpdateCountry($arFields["COUNTRY_ID"], $arFields["COUNTRY"]);

				if(intval($arFields["REGION_ID"]) && is_array($arFields["REGION"]))
					CSaleLocation::UpdateRegion($arFields["REGION_ID"], $arFields["REGION"]);

				if(intval($arFields["CITY_ID"]) && is_array($arFields["CITY"]))
					CSaleLocation::UpdateCity($arFields["CITY_ID"], $arFields["CITY"]);
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			if ((!is_set($arFields, "COUNTRY_ID") || IntVal($arFields["COUNTRY_ID"])<=0) && $arFields["COUNTRY_ID"] != "")
			{
				$arFields["COUNTRY_ID"] = CSaleLocation::AddCountry($arFields["COUNTRY"]);
				if (IntVal($arFields["COUNTRY_ID"])<=0) return false;

				UnSet($arFields["CITY_ID"]);
				UnSet($arFields["REGION_ID"]);
				CSaleLocation::AddLocation($arFields);
			}
			elseif ($arFields["CHANGE_COUNTRY"]=="Y" || $arFields["COUNTRY_ID"] == "")
			{
				CSaleLocation::UpdateCountry($arFields["COUNTRY_ID"], $arFields["COUNTRY"]);
			}

			//city
			if ($arFields["WITHOUT_CITY"]!="Y")
			{
				if (IntVal($arLocRes["CITY_ID"])>0)
				{
					CSaleLocation::UpdateCity(IntVal($arLocRes["CITY_ID"]), $arFields["CITY"]);
				}
				else
				{
					$arFields["CITY_ID"] = CSaleLocation::AddCity($arFields["CITY"]);
					if (IntVal($arFields["CITY_ID"])<=0) return false;
				}
			}
			else
			{
				CSaleLocation::DeleteCity($arLocRes["CITY_ID"]);
				$arFields["CITY_ID"] = false;
			}

			//region
			if (IntVal($arFields["REGION_ID"])>0)
			{
				CSaleLocation::UpdateRegion(IntVal($arLocRes["REGION_ID"]), $arFields["REGION"]);
			}
			elseif ($arFields["REGION_ID"] == 0 && $arFields["REGION_ID"] != '')
			{
				$db_res = CSaleLocation::GetRegionList(array("ID" => "DESC"), array("NAME" => $arFields["REGION"][LANGUAGE_ID]["NAME"]));
				$arRegion = $db_res->Fetch();

				if (count($arRegion) > 1)
					$arFields["REGION_ID"] = $arRegion["ID"];
				else
				{
					$arFields["REGION_ID"] = CSaleLocation::AddRegion($arFields["REGION"]);
					if (IntVal($arFields["REGION_ID"])<=0)
						return false;

					$arFieldsTmp = $arFields;
					UnSet($arFieldsTmp["CITY_ID"]);
					CSaleLocation::AddLocation($arFieldsTmp);
				}
			}
			elseif ($arFields["REGION_ID"] == '')
			{
				//CSaleLocation::DeleteRegion($arLocRes["REGION_ID"]);
				$arFields["REGION_ID"] = 0;
			}
			else
			{
				UnSet($arFields["REGION_ID"]);
			}

			CSaleLocation::UpdateLocation($ID, $arFields);

			return $ID;
		}
	}

	// ???
	function Delete($ID)
	{
		global $DB;
		$ID = IntVal($ID);

		if (!($arLocRes = CSaleLocation::GetByID($ID, LANGUAGE_ID)))
			return false;

		foreach (GetModuleEvents("sale", "OnBeforeLocationDelete", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, array($ID))===false)
				return false;

		if (IntVal($arLocRes["CITY_ID"]) > 0)
			CSaleLocation::DeleteCity($arLocRes["CITY_ID"]);

		$bDelCountry = True;
		$db_res = CSaleLocation::GetList(
				array("SORT" => "ASC"),
				array("COUNTRY_ID" => $arLocRes["COUNTRY_ID"], "!ID"=>$ID),
				LANGUAGE_ID
			);
		if ($db_res->Fetch())
			$bDelCountry = false;

		if ($bDelCountry && IntVal($arLocRes["COUNTRY_ID"]) > 0)
			CSaleLocation::DeleteCountry($arLocRes["COUNTRY_ID"]);

		$bDelRegion = True;
		$db_res = CSaleLocation::GetList(
				array("SORT" => "ASC"),
				array("REGION_ID" => $arLocRes["REGION_ID"], "!ID"=>$ID),
				LANGUAGE_ID
			);
		if ($db_res->Fetch())
			$bDelRegion = false;

		if ($bDelRegion && IntVal($arLocRes["REGION_ID"]) > 0)
			CSaleLocation::DeleteRegion($arLocRes["REGION_ID"]);

		$DB->Query("DELETE FROM b_sale_location2location_group WHERE LOCATION_ID = ".$ID."", true);
		$DB->Query("DELETE FROM b_sale_delivery2location WHERE LOCATION_ID = ".$ID." AND LOCATION_TYPE = 'L'", true);
		$DB->Query("DELETE FROM b_sale_location_zip WHERE LOCATION_ID = ".$ID."", true);

		if(self::isLocationProMigrated())
		{
			try
			{
				$res = Location\LocationTable::delete($ID); // the whole subtree will be deleted
				if(!$res->isSuccess())
					return false;

				$bDelete = true;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			$bDelete = $DB->Query("DELETE FROM b_sale_location WHERE ID = ".$ID."", true);
		}

		foreach (GetModuleEvents("sale", "OnLocationDelete", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, array($ID));

		return $bDelete;
	}

	function OnLangDelete($strLang)
	{
		global $DB;

		if(self::isLocationProMigrated())
		{
			$DB->Query("DELETE FROM ".Location\Name\LocationTable::getTableName()." WHERE LANGUAGE_ID = '".$DB->ForSql($strLang)."'", true);
			$DB->Query("DELETE FROM ".Location\Name\TypeTable::getTableName()." WHERE LANGUAGE_ID = '".$DB->ForSql($strLang)."'", true);
			$DB->Query("DELETE FROM ".Location\Name\GroupTable::getTableName()." WHERE LANGUAGE_ID = '".$DB->ForSql($strLang)."'", true);
		}

		$DB->Query("DELETE FROM b_sale_location_city_lang WHERE LID = '".$DB->ForSql($strLang)."'", true);
		$DB->Query("DELETE FROM b_sale_location_country_lang WHERE LID = '".$DB->ForSql($strLang)."'", true);

		return true;
	}

	function DeleteAll()
	{
		global $DB;

		foreach (GetModuleEvents("sale", "OnBeforeLocationDeleteAll", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent)===false)
				return false;

		if(self::isLocationProMigrated())
		{
			//main
			$DB->Query("DELETE FROM ".Location\LocationTable::getTableName());
			$DB->Query("DELETE FROM ".Location\GroupTable::getTableName());
			$DB->Query("DELETE FROM ".Location\TypeTable::getTableName());

			//names
			$DB->Query("DELETE FROM ".Location\Name\LocationTable::getTableName());
			$DB->Query("DELETE FROM ".Location\Name\GroupTable::getTableName());
			$DB->Query("DELETE FROM ".Location\Name\TypeTable::getTableName());

			//links
			$DB->Query("DELETE FROM ".Location\GroupLocationTable::getTableName());
			$DB->Query("DELETE FROM ".Location\SiteLocationTable::getTableName());
			$DB->Query("DELETE FROM ".Delivery\DeliveryLocationTable::getTableName());
			
			//other
			$DB->Query("DELETE FROM ".Location\DefaultSiteTable::getTableName());
			$DB->Query("DELETE FROM ".Location\ExternalTable::getTableName());
			$DB->Query("DELETE FROM ".Location\ExternalServiceTable::getTableName());
		}

		$DB->Query("DELETE FROM b_sale_location2location_group");
		$DB->Query("DELETE FROM b_sale_location_group_lang");
		$DB->Query("DELETE FROM b_sale_location_group");

		$DB->Query("DELETE FROM b_sale_delivery2location");
		$DB->Query("DELETE FROM b_sale_location");

		$DB->Query("DELETE FROM b_sale_location_city_lang");
		$DB->Query("DELETE FROM b_sale_location_city");

		$DB->Query("DELETE FROM b_sale_location_country_lang");
		$DB->Query("DELETE FROM b_sale_location_country");

		$DB->Query("DELETE FROM b_sale_location_region_lang");
		$DB->Query("DELETE FROM b_sale_location_region");

		$DB->Query("DELETE FROM b_sale_location_zip");

		foreach (GetModuleEvents("sale", "OnLocationDeleteAll", true) as $arEvent)
			ExecuteModuleEventEx($arEvent);

	}

	function GetLocationZIP($location)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				if(!intval($location))
					throw new Exception();

				return Location\ExternalTable::getList(array(
					'filter' => array(
						'=SERVICE.CODE' => self::ZIP_EXT_SERVICE_CODE,
						'=LOCATION_ID' => $location
					),
					'select' => array(
						'ID',
						'ZIP' => 'XML_ID'
					)
				));
			}
			catch(Exception $e)
			{
				return new DB\ArrayResult(array());
			}
		}
		else
		{
			global $DB;

			return $DB->Query("SELECT ZIP FROM b_sale_location_zip WHERE LOCATION_ID='".$DB->ForSql($location)."'");
		}
	}

	function GetByZIP($zip)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				if(!strlen($zip))
					throw new Exception();

				$res = Location\ExternalTable::getList(array(
					'filter' => array(
						'=SERVICE.CODE' => self::ZIP_EXT_SERVICE_CODE,
						'=XML_ID' => $zip
					),
					'select' => array(
						'LOCATION_ID',
					),
					'limit' => 1
				));

				if($item = $res->fetch())
					return self::GetByID($item['LOCATION_ID']);
				else
					return false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		else
		{
			global $DB;

			$dbRes = $DB->Query('SELECT LOCATION_ID FROM b_sale_location_zip WHERE ZIP=\''.$DB->ForSql($zip).'\'');
			if ($arRes = $dbRes->Fetch())
				return CSaleLocation::GetByID($arRes['LOCATION_ID']);
			else
				return false;
		}
	}

	function ClearLocationZIP($location)
	{
		global $DB;

		if(self::isLocationProMigrated())
		{
			$DB->Query("DELETE FROM ".Location\ExternalTable::getTableName()." WHERE LOCATION_ID='".$DB->ForSql($location)."'");
		}
		else
		{
			$query = "DELETE FROM b_sale_location_zip WHERE LOCATION_ID='".$DB->ForSql($location)."'";
			$DB->Query($query);

			return;
		}
	}

	function ClearAllLocationZIP()
	{
		global $DB;

		if(self::isLocationProMigrated())
		{
			$DB->Query("DELETE FROM ".Location\ExternalTable::getTableName());
		}
		else
		{
			$DB->Query("DELETE FROM b_sale_location_zip");
		}
	}

	function AddLocationZIP($location, $ZIP, $bSync = false)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				if($bSync)
				{
					$res = Location\ExternalTable::getList(array(
						'filter' => array(
							'=SERVICE.CODE' => self::ZIP_EXT_SERVICE_CODE,
							'=XML_ID' => $ZIP,
							'=LOCATION_ID' => $location
						),
						'select' => array(
							'ID',
						),
						'limit' => 1
					))->fetch();

					if($res)
					{
						if(!Location\ExternalTable::update($res['ID'], array(
							'LOCATION_ID' => $location,
							'XML_ID' => $ZIP
						))->isSuccess())
						{
							$bSync = false;
						}
					}
				}

				if(!$bSync)
				{
					$zipId = self::getZipId();
					if($zipId)
					{
						Location\ExternalTable::add(array(
							'LOCATION_ID' => $location,
							'XML_ID' => $ZIP,
							'SERVICE_ID' => $zipId
						));
					}
				}
			}
			catch(Exception $e)
			{
				return new DB\ArrayResult(array());
			}
		}
		else
		{
			global $DB;

			$arInsert = array(
				"LOCATION_ID" => intval($location),
				"ZIP" => intval($ZIP),
			);

			if ($bSync)
			{
				$cnt = $DB->Update(
					'b_sale_location_zip',
					$arInsert,
					"WHERE LOCATION_ID='".$arInsert["LOCATION_ID"]."' AND ZIP='".$arInsert["ZIP"]."'"
				);

				if ($cnt <= 0)
				{
					$bSync = false;
				}
			}

			if (!$bSync)
			{
				$DB->Insert('b_sale_location_zip', $arInsert);
			}

			return;
		}
	}

	function SetLocationZIP($location, $arZipList)
	{
		global $DB;

		if (is_array($arZipList))
		{
			CSaleLocation::ClearLocationZIP($location);

			$arInsert = array(
				"LOCATION_ID" => "'".$DB->ForSql($location)."'",
				"ZIP" => '',
			);

			foreach ($arZipList as $ZIP)
			{
				if (strlen($ZIP) > 0)
					self::AddLocationZIP($location, $ZIP);
			}
		}

		return;
	}

	function GetRegionsIdsByNames($arRegNames, $countryId = false)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				$types = self::getTypes();
				$query = new Entity\Query(self::SELF_ENTITY_NAME);

				$fieldMap = array(

					'RID' => 'REGION_ID',

					'RNAME' => 'NAME.NAME',
					'RSHORT_NAME' => 'NAME.SHORT_NAME'
				);

				$selectFields = $fieldMap;
				$filterFields = array(
					array(
						'LOGIC' => 'OR',
						'RNAME' => $arRegNames,
						'RSHORT_NAME' => $arRegNames,
					),
					'=TYPE_ID' => $types['REGION'],
					'!=REGION_ID' => '0'
				);
				
				if($countryId = intval($countryId))
					$filterFields['=COUNTRY_ID'] = $countryId;

				// order
				$orderFields = array(
					'RNAME' => 'asc',
					'RSHORT_NAME' => 'asc'
				);

				// group
				$groupFields = array(
					'RID'
				);

				$nameJoinCondition = array(
					'=this.ID' => 'ref.LOCATION_ID',
				);
				if(strlen($strLang))
					$nameJoinCondition['=ref.LANGUAGE_ID'] = array('?', $strLang);

				$query->registerRuntimeField(
					'NAME',
					array(
						'data_type' => self::NAME_ENTITY_NAME,
						'reference' => $nameJoinCondition,
						'join_type' => 'left'
					)
				);

				$query->setSelect($selectFields);
				$query->setFilter($filterFields);
				$query->setOrder($orderFields);
				$query->setGroup($groupFields);

				$result = array();

				$res = $query->exec();
				while($item = $res->fetch())
				{
					$result[strlen($item['RNAME']) ? $item['RNAME'] : $item['RSHORT_NAME']] = $item['RID'];
				}

				return $result;
			}
			catch(Exception $e)
			{
				return array();
			}
		}
		else
		{
			global $DB;
			$arResult = array();
			$arWhere = array();
			$arQueryFields = array('RL.NAME', 'RL.SHORT_NAME');

			if(is_array($arRegNames))
			{
				foreach ($arRegNames as $regName)
				{
					$regName = $DB->ForSql($regName);
					foreach ($arQueryFields as $field)
						$arWhere[] = $field." LIKE '".$regName."'";
				}

				if (count($arWhere) > 0)
				{
					$strWhere = implode(' OR ', $arWhere);

					$query = "	SELECT RL.REGION_ID, RL.NAME, RL.SHORT_NAME
								FROM b_sale_location_region_lang RL ";

					if ($countryId)
					{
						$strWhere = 'L.COUNTRY_ID=\''.intval($countryId).'\' AND ('.$strWhere.')';
						$query .= "LEFT JOIN b_sale_location L ON L.REGION_ID=RL.REGION_ID ";
					}

					$query .= "WHERE ".$strWhere;
					$query .= " GROUP BY RL.REGION_ID";
					$query .= " ORDER BY RL.NAME, RL.SHORT_NAME";

					$dbList = $DB->Query($query);

					$arRegionsLang = array();

					while($arRegion = $dbList->Fetch())
					{
						if(strlen($arRegion["NAME"]) > 0)
							$idx = $arRegion["NAME"];
						else
							$idx = $arRegion["SHORT_NAME"];

						$arResult[$idx] = $arRegion["REGION_ID"];
					}
				}
			}

			return $arResult;

		}
	}

	function GetRegionsNamesByIds($arIds, $lang = LANGUAGE_ID)
	{
		if(self::isLocationProMigrated())
		{
			try
			{
				if(!is_array($arIds) || empty($arIds))
					throw new Exception();

				$arIds = array_unique($arIds);
				$parsedList = array();
				foreach($arIds as $id)
				{
					if(intval($id))
						$parsedList[] = intval($id);
				}

				if(!strlen($lang))
					$lang = LANGUAGE_ID;

				$arResult = array();
				if(!empty($parsedList))
				{
					$res = self::GetLocationTypeList(
						'REGION',
						array('NAME' => 'asc', 'SHORT_NAME' => 'asc'),
						array('ID' => $parsedList),
						$lang
					);

					while($arRegion = $res->fetch())
					{
						$arResult[$arRegion["ID"]] = strlen($arRegion["NAME"]) > 0 ? $arRegion["NAME"] : $arRegion["SHORT_NAME"];
					}
				}

				return $arResult;
			}
			catch(Exception $e)
			{
				return array();
			}
		}
		else
		{
			global $DB;
			$arResult = array();
			$arWhere = array();

			if ('' == $lang)
				$lang = LANGUAGE_ID;

			if(!empty($arIds) && is_array($arIds))
			{
				foreach ($arIds as $id)
				{
					if(intval($id) > 0)
						$arWhere[] = intval($id);
				}

				if (!empty($arWhere))
				{
					$query = "select RL.REGION_ID, RL.NAME, RL.SHORT_NAME from b_sale_location_region_lang RL";
					$query .= " where REGION_ID IN(".implode(',', $arWhere).") and RL.LID='".$DB->ForSql($lang, 2)."'";
					$query .= " order by RL.NAME, RL.SHORT_NAME";

					$dbList = $DB->Query($query);

					while($arRegion = $dbList->Fetch())
						$arResult[$arRegion["REGION_ID"]] = strlen($arRegion["NAME"]) > 0 ? $arRegion["NAME"] : $arRegion["SHORT_NAME"];
				}
			}

			return $arResult;
		}
	}

	// location import is overwritten, and it is enabled when self::isLocationProMigrated() == true, so no proxy provided for the obsolete methods below

	function _GetZIPImportStats()
	{
		global $DB;

		$query = "SELECT COUNT(*) AS CNT, COUNT(DISTINCT LOCATION_ID) AS CITY_CNT FROM b_sale_location_zip";
		$rsStats = $DB->Query($query);
		$arStat = $rsStats->Fetch();

		return $arStat;
	}

	function _GetCityImport($arCityName, $country_id = false)
	{
		global $DB;

		$arQueryFields = array('LCL.NAME', 'LCL.SHORT_NAME');

		$arWhere = array();
		foreach ($arCityName as $city_name)
		{
			$city_name = $DB->ForSql($city_name);
			foreach ($arQueryFields as $field)
			{
				if (strlen($field) > 0)
					$arWhere[] = $field."='".$city_name."'";
			}
		}

		if (count($arWhere) <= 0) return false;
		$strWhere = implode(' OR ', $arWhere);

		if ($country_id)
		{
			$strWhere = 'L.COUNTRY_ID=\''.intval($country_id).'\' AND ('.$strWhere.')';
		}

		$query = "
SELECT L.ID, L.CITY_ID
FROM b_sale_location L
LEFT JOIN b_sale_location_city_lang LCL ON L.CITY_ID=LCL.CITY_ID
WHERE ".$strWhere;

		$dbList = $DB->Query($query);

		if ($arCity = $dbList->Fetch())
			return $arCity;
		else
			return false;
	}
}
?>