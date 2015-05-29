<?php
namespace Bitrix\Catalog;

use Bitrix\Main\Entity\DataManager as DataManager;
use Bitrix\Main\Application as Application;
use Bitrix\Main\Entity\Query as Query;
use Bitrix\Main\Config\Option as Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CatalogViewedProductTable extends DataManager
{
	/**
	 * @override
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_catalog_viewed_product';
	}

	/**
	 * @override
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			),

			'FUSER_ID' => array(
				'data_type' => 'integer',
			),

			'DATE_VISIT' => array(
				'data_type' => 'datetime',
			),
			'PRODUCT_ID' => array(
				'data_type' => 'integer'
			),
			'ELEMENT_ID' => array(
				'data_type' => 'integer'
			),
			'SITE_ID' => array(
				'data_type' => 'string'
			),
			'VIEW_COUNT' => array(
				'data_type' => 'integer'
			),
			'RECOMMENDATION' => array(
				'data_type' => 'string'
			),
			'ELEMENT' => array(
				'data_type' => '\Bitrix\Iblock\ElementTable',
				'reference' => array('=this.PRODUCT_ID' => 'ref.ID'),
				'join_type' => 'INNER'
			),
			'PRODUCT' => array(
				'data_type' => '\Bitrix\Sale\ProductTable',
				'reference' => array('=this.PRODUCT_ID' => 'ref.ID')
			),
			'FUSER' => array(
				'data_type' => '\Bitrix\Sale\FuserTable',
				'reference' => array('=this.FUSER_ID' => 'ref.ID')
			)
		);
	}

	/**
	 * Common function, used to update/insert any product.
	 *
	 * @param int $productId			Id of product.
	 * @param int $fuserId				User basket id.
	 * @param string $siteId			Site id.
	 * @param int $elementId			Parent id.
	 * @param string $recommendationId	Bigdata recommendation id.
	 *
	 * @return int Id of row.
	 */
	public static function refresh($productId, $fuserId, $siteId = SITE_ID, $elementId = 0, $recommendationId = '')
	{
		$connection = Application::getConnection();

		$productId = (int)$productId;
		if ($productId <= 0)
			return -1;

		$fuserId = (int)$fuserId;
		if ($fuserId <= 0)
			return -1;

		if (!is_string($siteId) || strlen($siteId) <= 0)
			return -1;


		$filter = array("FUSER_ID" => $fuserId, "SITE_ID" => $siteId);

		if ($elementId > 0)
		{
			$filter["ELEMENT_ID"] = $elementId;

			// Delete parent product id (for capability)
			if ($elementId != $productId)
				$connection->query("DELETE FROM
										b_catalog_viewed_product
									WHERE
										PRODUCT_ID = " . intval($elementId) . "
										AND
										FUSER_ID = " . intval($fuserId) . "
										AND
										SITE_ID = '" . $connection->getSqlHelper()->forSql($siteId) . "'"
				);
		}
		else
		{
			$iblockId = (int)\CIBlockElement::getIBlockByID($productId);
			if ($iblockId <= 0)
				return -1;

			$productInfo = \CCatalogSKU::getProductInfo($productId, $iblockId);

			// Concrete SKU ID
			if (!empty($productInfo))
			{
				$filter['PRODUCT_ID'] = array();
				$siblings = array();

				// Delete parent product id (for capability)
				$connection->query("DELETE FROM
										b_catalog_viewed_product
									WHERE
										PRODUCT_ID = " . intval($productInfo['ID']) . "
										AND
										FUSER_ID = " . intval($fuserId) . "
										AND
										SITE_ID = '" . $connection->getSqlHelper()->forSql($siteId) . "'"
				);

				$skuInfo = \CCatalogSKU::getInfoByOfferIBlock($iblockId);
				$skus = \CIBlockElement::getList(
					array(),
					array('IBLOCK_ID' => $iblockId, 'PROPERTY_'.$skuInfo['SKU_PROPERTY_ID'] => $productInfo['ID']),
					false,
					false,
					array('ID', 'IBLOCK_ID')
				);
				while ($oneSku = $skus->fetch())
					$siblings[] = $oneSku['ID'];

				$filter["PRODUCT_ID"] = $siblings;
			}
			else
			{
				$filter["PRODUCT_ID"] = $productId;
			}
		}

		$iterator = static::getList(array(
			"filter" => $filter,
			"select" => array("ID", "FUSER_ID", "DATE_VISIT", "PRODUCT_ID", "SITE_ID", "VIEW_COUNT")
		));

		if ($row = $iterator->fetch())
		{
			static::update($row["ID"], array(
				"PRODUCT_ID" => $productId,
				"DATE_VISIT" => new \Bitrix\Main\Type\DateTime,
				'VIEW_COUNT' => $row['VIEW_COUNT'] + 1,
				"ELEMENT_ID" => $elementId,
				"RECOMMENDATION" => $recommendationId
			));

			return $row['ID'];
		}
		else
		{
			$result = static::add(array(
				"FUSER_ID" => $fuserId,
				"DATE_VISIT" => new \Bitrix\Main\Type\DateTime(),
				"PRODUCT_ID" => $productId,
				"ELEMENT_ID" => $elementId,
				"SITE_ID" => $siteId,
				"VIEW_COUNT" => 1,
				"RECOMMENDATION" => $recommendationId
			));

			return $result->getId();
		}
	}


	/**
	 * Returns ids map: SKU_PRODUCT_ID => PRODUCT_ID
	 *
	 * @param array $originalIds Input products ids.
	 * @return integer[]
	 */
	public static function getProductsMap(array $originalIds = array())
	{
		if(!is_array($originalIds) || !count($originalIds))
			return array();

		$newIds = array();
		$catalogIterator = \CCatalog::getList(array("IBLOCK_ID" => "ASC"), array("!SKU_PROPERTY_ID" => 0), false, false, array("IBLOCK_ID", "SKU_PROPERTY_ID"));
		while($catalog = $catalogIterator->fetch())
		{
			$elementIterator = \CIBlockElement::getList(
				array(),
				array("ID" => $originalIds, "IBLOCK_ID" => $catalog['IBLOCK_ID']),
				false,
				false,
				array("ID", "IBLOCK_ID", "PROPERTY_" . $catalog['SKU_PROPERTY_ID'])
			);

			while ($item = $elementIterator->fetch())
			{
				$propertyName = "PROPERTY_" . $catalog['SKU_PROPERTY_ID'] . "_VALUE";
				$parentId = $item[$propertyName];
				if (!empty($parentId))
				{
					$newIds[$item['ID']] = $parentId;
				}
				else
				{
					$newIds[$item['ID']] = $item['ID'];
				}
			}
		}

		// Push missing
		foreach ($originalIds as $id)
		{
			if (!isset($newIds[$id]))
			{
				$newIds[$id] = $id;
			}
		}

		// Resort map
		$tmpMap = array();
		foreach ($originalIds as $id)
		{
			$tmpMap[$id . ""] = $newIds[$id];
		}

		return $tmpMap;
	}

	/**
	 * Returns product map: array('PRODUCT_ID' => 'ELEMENT_ID')
	 *
	 * @param int $iblockId					Iblock Id.
	 * @param int $sectionId				Section Id.
	 * @param int $fuserId					Sale user Id.
	 * @param int $excludeProductId				Exclude item Id.
	 * @param int $limit					Max count.
	 * @param int $depth					Depth level.
	 * @param string|null $siteId			Site identifier.
	 * @return mixed
	 */
	public static function getProductSkuMap($iblockId, $sectionId, $fuserId, $excludeProductId, $limit, $depth = 0, $siteId = null)
	{
		if (empty($siteId))
		{
			$context = Application::getInstance()->getContext();
			$siteId = $context->getSite();
		}

		$map = array();

		$con = Application::getConnection();
		$sqlHelper = $con->getSqlHelper();

		$strSubSections = '';

		if ($depth > 0)
		{
			$strSql = "SELECT BSprS.ID AS ID
						FROM b_iblock_section BSprS
							INNER JOIN b_iblock_section BS1
							ON (
								BSprS.IBLOCK_ID = BS1.IBLOCK_ID
								AND BSprS.LEFT_MARGIN <= BS1.LEFT_MARGIN
								AND BSprS.RIGHT_MARGIN >= BS1.RIGHT_MARGIN
							)
						WHERE BS1.ID = " . intval($sectionId) . " AND BSprS.DEPTH_LEVEL = " . intval($depth);

			$result = $con->query($strSql, null);
			while ($item = $result->fetch())
				$strSubSections .= $item['ID'] . ", ";
		}

		$strSql = "SELECT CVP.PRODUCT_ID AS PRODUCT_ID, CVP.ELEMENT_ID AS ELEMENT_ID
					FROM b_catalog_viewed_product CVP
						INNER JOIN b_iblock_element BE ON BE.ID = CVP.ELEMENT_ID
						INNER JOIN (
							SELECT DISTINCT BSE.IBLOCK_ELEMENT_ID
							FROM b_iblock_section_element BSE
								INNER JOIN b_iblock_section BSubS ON BSE.IBLOCK_SECTION_ID = BSubS.ID
								INNER JOIN b_iblock_section BS ON (BSubS.IBLOCK_ID = BS.IBLOCK_ID
									AND BSubS.LEFT_MARGIN >= BS.LEFT_MARGIN
									AND BSubS.RIGHT_MARGIN <= BS.RIGHT_MARGIN)
							WHERE ".( !empty($strSubSections) ? "BS.ID IN (".$strSubSections."0)
							OR " : "").
							"BS.ID = ".intval($sectionId)."
						) BES ON BES.IBLOCK_ELEMENT_ID = BE.ID

					WHERE CVP.FUSER_ID = ".intval($fuserId)."
						AND CVP.SITE_ID = '".$sqlHelper->forSql($siteId)."'
						AND BE.ID <> ".intval($excludeProductId)."
						AND BE.IBLOCK_ID = ".intval($iblockId)."
						AND (BE.WF_STATUS_ID = 1 AND BE.WF_PARENT_ELEMENT_ID IS NULL)
					ORDER BY CVP.DATE_VISIT DESC";

		$result = $con->query($strSql, null, 0, $limit);

		while($item = $result->fetch())
			$map[$item['PRODUCT_ID']] = $item['ELEMENT_ID'];

		return $map;
	}

	/**
	 * Clear table b_catalog_viewed_product
	 *
	 * @param int $liveTime Live time.
	 * @return void
	 */
	public static function clear($liveTime = 10)
	{
		$connection = Application::getConnection();
		$helper = $connection->getSqlHelper();
		$liveTime = (int)$liveTime;
		$liveTo = $helper->addSecondsToDateTime($liveTime * 24 * 3600, "DATE_VISIT");
		$now = $helper->getCurrentDateTimeFunction();

		$deleteSql = "DELETE FROM b_catalog_viewed_product WHERE $now > $liveTo";
		$connection->query($deleteSql);
	}

	/**
	 * For agent
	 *
	 * @return string
	 */
	public static function clearAgent()
	{
		self::clear(Option::get('catalog', 'viewed_time'));
		return '\Bitrix\Catalog\CatalogViewedProductTable::clearAgent(0);';
	}
}

?>