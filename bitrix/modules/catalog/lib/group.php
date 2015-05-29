<?php
namespace Bitrix\Catalog;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class GroupTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(100) mandatory
 * <li> BASE bool optional default 'N'
 * <li> SORT int optional default 100
 * <li> XML_ID string(255) optional
 * <li> TIMESTAMP_X datetime optional
 * <li> MODIFIED_BY int optional
 * <li> DATE_CREATE datetime optional
 * <li> CREATED_BY int optional
 * </ul>
 *
 * @package Bitrix\Catalog
 **/

class GroupTable extends Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_catalog_group';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('GROUP_ENTITY_ID_FIELD'),
			)),
			'NAME' => new Entity\StringField('NAME', array(
				'required' => true,
				'validation' => array(__CLASS__, 'validateName'),
				'title' => Loc::getMessage('GROUP_ENTITY_NAME_FIELD'),
			)),
			'BASE' => new Entity\BooleanField('BASE', array(
				'values' => array('N', 'Y'),
				'title' => Loc::getMessage('GROUP_ENTITY_BASE_FIELD'),
			)),
			'SORT' => new Entity\IntegerField('SORT', array(
				'title' => Loc::getMessage('GROUP_ENTITY_SORT_FIELD'),
			)),
			'XML_ID' => new Entity\StringField('XML_ID', array(
				'validation' => array(__CLASS__, 'validateXmlId'),
				'title' => Loc::getMessage('GROUP_ENTITY_XML_ID_FIELD'),
			)),
			'TIMESTAMP_X' => new Entity\DatetimeField('TIMESTAMP_X', array(
				'title' => Loc::getMessage('GROUP_ENTITY_TIMESTAMP_X_FIELD'),
				'default_value' => new Type\DateTime()
			)),
			'MODIFIED_BY' => new Entity\IntegerField('MODIFIED_BY', array(
				'title' => Loc::getMessage('GROUP_ENTITY_MODIFIED_BY_FIELD'),
			)),
			'DATE_CREATE' => new Entity\DatetimeField('DATE_CREATE', array(
				'title' => Loc::getMessage('GROUP_ENTITY_DATE_CREATE_FIELD'),
				'default_value' => new Type\DateTime()
			)),
			'CREATED_BY' => new Entity\IntegerField('CREATED_BY', array(
				'title' => Loc::getMessage('GROUP_ENTITY_CREATED_BY_FIELD'),
			)),
			'CREATED_BY_USER' => new Entity\ReferenceField(
				'CREATED_BY_USER',
				'Bitrix\Main\User',
				array('=this.CREATED_BY' => 'ref.ID')
			),
			'MODIFIED_BY_USER' => new Entity\ReferenceField(
				'MODIFIED_BY_USER',
				'Bitrix\Main\User',
				array('=this.MODIFIED_BY' => 'ref.ID')
			),
			'LANG' => new Entity\ReferenceField(
				'LANG',
				'Bitrix\Catalog\GroupLang',
				array('=this.ID' => 'ref.CATALOG_GROUP_ID')
			)
		);
	}
	/**
	 * Returns validators for NAME field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return array(
			new Entity\Validator\Length(null, 100),
		);
	}
	/**
	 * Returns validators for XML_ID field.
	 *
	 * @return array
	 */
	public static function validateXmlId()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}
}