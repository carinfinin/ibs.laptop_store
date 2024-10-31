<?php
namespace LaptopStore\Entity;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class BrandTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> ACTIVE bool ('N', 'Y') optional default 'Y'
 * <li> NAME string(50) optional
 * </ul>
 *
 * @package Bitrix\Brand
 **/

class BrandTable extends DataManager
{

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_brand';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('BRAND_ENTITY_ID_FIELD'),
                ]
            ),
            new BooleanField(
                'ACTIVE',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'Y',
                    'title' => Loc::getMessage('BRAND_ENTITY_ACTIVE_FIELD'),
                ]
            ),
            new StringField(
                'CODE',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => Loc::getMessage('BRAND_ENTITY_NAME_FIELD'),
                ]
            ),
            new StringField(
                'NAME',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => Loc::getMessage('BRAND_ENTITY_NAME_FIELD'),
                ]
            ),
        ];
    }
}