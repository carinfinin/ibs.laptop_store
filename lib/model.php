<?php
namespace LaptopStore\Entity;


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\Entity;


/**
 * Class ModelTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> ACTIVE bool ('N', 'Y') optional default 'Y'
 * <li> NAME string(50) optional
 * <li> BRAND_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Model
 **/

class ModelTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_model';
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
                    'title' => Loc::getMessage('MODEL_ENTITY_ID_FIELD'),
                ]
            ),
            new BooleanField(
                'ACTIVE',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'Y',
                    'title' => Loc::getMessage('MODEL_ENTITY_ACTIVE_FIELD'),
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
                    'title' => Loc::getMessage('MODEL_ENTITY_NAME_FIELD'),
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
                    'title' => Loc::getMessage('MODEL_ENTITY_NAME_FIELD'),
                ]
            ),
            new IntegerField(
                'BRAND_ID',
                [
                    'required' => true,
                    'title' => Loc::getMessage('MODEL_ENTITY_BRAND_ID_FIELD'),
                ]
            ),
            new Entity\ReferenceField(
                'BRAND',
                BrandTable::class,
                array("=this.BRAND_ID" => "ref.ID")
            )

        ];
    }
}