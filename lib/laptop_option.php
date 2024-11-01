<?php
namespace LaptopStore\Entity;


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\Entity;


/**
 * Class TopOptionTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> VALUE string(255) optional
 * <li> LAP_TOP_ID int mandatory
 * <li> OPTION_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Lap
 **/

class LapTopOptionTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_laptop_option';
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
                    'title' => Loc::getMessage('TOP_OPTION_ENTITY_ID_FIELD'),
                ]
            ),
            new IntegerField(
                'VALUE',
                [
                    'required' => true,
                    'title' => Loc::getMessage('TOP_OPTION_ENTITY_VALUE_FIELD'),
                ]
            ),
            new IntegerField(
                'LAPTOP_ID',
                [
                    'title' => Loc::getMessage('TOP_OPTION_ENTITY_LAP_TOP_ID_FIELD'),
                ]
            ),
            new IntegerField(
                'OPTION_ID',
                [
                    'required' => true,
                    'title' => Loc::getMessage('TOP_OPTION_ENTITY_OPTION_ID_FIELD'),
                ]
            ),
            new Entity\ReferenceField(
                'LAPTOP',
                'LaptopStore\Entity\LapTopTable',
                [
                    '=this.LAPTOP_ID' => 'ref.ID',
                ]
            ),
            new Entity\ReferenceField(
                'OPTION',
                'LaptopStore\Entity\OptionTable',
                [
                    '=this.OPTION_ID' => 'ref.ID',
                ]
            ),
        ];
    }
}