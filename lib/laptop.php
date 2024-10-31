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
 * Class TopTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> ACTIVE bool ('N', 'Y') optional default 'Y'
 * <li> NAME string(50) optional
 * <li> MODEL_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Lap
 **/

class LapTopTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_laptop';
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
                    'title' => Loc::getMessage('TOP_ENTITY_ID_FIELD'),
                ]
            ),
            new BooleanField(
                'ACTIVE',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'Y',
                    'title' => Loc::getMessage('TOP_ENTITY_ACTIVE_FIELD'),
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
                    'title' => Loc::getMessage('TOP_ENTITY_NAME_FIELD'),
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
                    'title' => Loc::getMessage('TOP_ENTITY_NAME_FIELD'),
                ]
            ),
            new IntegerField(
                'MODEL_ID',
                [
                    'required' => true,
                    'title' => Loc::getMessage('TOP_ENTITY_MODEL_ID_FIELD'),
                ]
            ),
            new Entity\ReferenceField(
                'MODEL',
                ModelTable::class,
                array("=this.MODEL_ID" => "ref.ID")
            ),
            new Entity\ReferenceField(
                'OPTIONS',
                'LaptopStore\Entity\LapTopOptionTable',
                [
                    '=this.ID' => 'ref.LAPTOP_ID',
                ]
            ),

        ];
    }
}