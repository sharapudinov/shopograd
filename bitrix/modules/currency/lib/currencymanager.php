<?php
namespace Bitrix\Currency;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\LanguageTable;

Loc::loadMessages(__FILE__);

/**
 * Class CurrencyTable
 *
 * @package Bitrix\Currency
 **/
class CurrencyManager
{
	const CACHE_BASE_CURRENCY_ID = 'currency_base_currency';
	const CACHE_CURRENCY_LIST_ID = 'currency_currency_list';

	protected static $baseCurrency = '';
	protected static $datetimeTemplate = null;

	/**
	 * @param string $currency	Currency id.
	 * @return bool|string
	 */
	public static function checkCurrencyID($currency)
	{
		$currency = (string)$currency;
		return ($currency === '' || strlen($currency) > 3 ? false : $currency);
	}

	/**
	 * @param string $language	Language.
	 * @return bool|string
	 */
	public static function checkLanguage($language)
	{
		$language = (string)$language;
		return ($language === '' || strlen($language) > 2 ? false : $language);
	}

	/**
	 * @return string
	 */
	public static function getBaseCurrency()
	{
		if (self::$baseCurrency === '')
		{
			$skipCache = (defined('CURRENCY_SKIP_CACHE') && CURRENCY_SKIP_CACHE);
			$tableName = CurrencyTable::getTableName();
			$currencyFound = false;
			$currencyFromCache = false;
			if (!$skipCache)
			{
				$cacheTime = (int)(defined('CURRENCY_CACHE_TIME') ? CURRENCY_CACHE_TIME : CURRENCY_CACHE_DEFAULT_TIME);
				$managedCache = Application::getInstance()->getManagedCache();
				$currencyFromCache = $managedCache->read($cacheTime, self::CACHE_BASE_CURRENCY_ID, $tableName);
				if ($currencyFromCache)
				{
					$currencyFound = true;
					self::$baseCurrency = (string)$managedCache->get(self::CACHE_BASE_CURRENCY_ID, $tableName);
				}
			}
			if ($skipCache || !$currencyFound)
			{
				$currencyIterator = CurrencyTable::getList(array(
					'select' => array('CURRENCY'),
					'filter' => array('BASE' => 'Y', 'AMOUNT' => 1)
				));
				if ($currency = $currencyIterator->fetch())
				{
					$currencyFound = true;
					self::$baseCurrency = $currency['CURRENCY'];
				}
				unset($currency, $currencyIterator);
			}
			if (!$skipCache && $currencyFound && !$currencyFromCache)
			{
				$managedCache->set(self::CACHE_BASE_CURRENCY_ID, self::$baseCurrency, $tableName);
			}
		}
		return self::$baseCurrency;
	}

	/**
	 * @return array
	 */
	public static function getInstalledCurrencies()
	{
		$installedCurrencies = (string)Option::get('currency', 'installed_currencies');
		if ($installedCurrencies === '')
		{
			$currencyList = array();
			$searched = false;
			$languageIterator = LanguageTable::getList(array(
				'select' => array('ID'),
				'filter' => array('ID' => 'ua')
			));
			if ($oneLanguage = $languageIterator->fetch())
			{
				$currencyList = array('RUB', 'USD', 'EUR', 'UAH');
				$searched = true;
			}
			if (!$searched)
			{
				$languageIterator = LanguageTable::getList(array(
					'select' => array('ID'),
					'filter' => array('ID' => 'ru')
				));
				if ($oneLanguage = $languageIterator->fetch())
				{
					$currencyList = array('RUB', 'USD', 'EUR', 'UAH');
					$searched = true;
				}
			}
			if (!$searched)
			{
				$currencyList = array('USD', 'EUR');
			}
			Option::set('currency', 'installed_currencies', implode(',', $currencyList), '');
			return $currencyList;
		}
		else
		{
			return explode(',', $installedCurrencies);
		}
	}

	/**
	 * @return void
	 */
	public static function clearCurrencyCache()
	{
		$currencyTableName = CurrencyTable::getTableName();

		$managedCache = Application::getInstance()->getManagedCache();
		$managedCache->clean(self::CACHE_CURRENCY_LIST_ID, $currencyTableName);
		$languageIterator = LanguageTable::getList(array(
			'select' => array('ID')
		));
		while ($oneLanguage = $languageIterator->fetch())
		{
			$managedCache->clean(self::CACHE_CURRENCY_LIST_ID.'_'.$oneLanguage['ID'], $currencyTableName);
		}
		unset($oneLanguage, $languageIterator);
		$managedCache->clean(self::CACHE_BASE_CURRENCY_ID, $currencyTableName);

		global $stackCacheManager;
		$stackCacheManager->Clear('currency_rate');
		$stackCacheManager->Clear('currency_currency_lang');
	}

	/**
	 * @param string $currency	Currency id.
	 * @return void
	 */
	public static function clearTagCache($currency)
	{
		if (defined('BX_COMP_MANAGED_CACHE'))
		{
			$currency = (string)$currency;
			if ($currency !== '')
			{
				$taggedCache = Application::getInstance()->getTaggedCache();
				$taggedCache->clearByTag('currency_id_'.$currency);
			}
		}
	}

	/**
	 * @return string
	 */
	public static function getDatetimeExpressionTemplate()
	{
		if (self::$datetimeTemplate === null)
		{
			$helper = Application::getConnection()->getSqlHelper();
			$format = Context::getCurrent()->getCulture()->getDateTimeFormat();
			$datetimeFieldName = '#FIELD#';
			$datetimeField = $datetimeFieldName;
			if (\CTimeZone::Enabled())
			{
				$diff = \CTimeZone::GetOffset();
				if ($diff <> 0)
					$datetimeField = $helper->addSecondsToDateTime($diff, $datetimeField);
				unset($diff);
			}
			self::$datetimeTemplate = str_replace(
				array('%', $datetimeFieldName),
				array('%%', '%1$s'),
				$helper->formatDate($format, $datetimeField)
			);
			unset($datetimeField, $datetimeFieldName, $format, $helper);
		}
		return self::$datetimeTemplate;
	}
}