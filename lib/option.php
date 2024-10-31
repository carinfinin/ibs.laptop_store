<?php
namespace LaptopStore\Entity;


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class OptionTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> ACTIVE bool ('N', 'Y') optional default 'Y'
 * <li> NAME string(50) optional
 * </ul>
 *
 * @package Bitrix\Option
 **/

class OptionTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_option';
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
                    'title' => Loc::getMessage('OPTION_ENTITY_ID_FIELD'),
                ]
            ),
            new BooleanField(
                'ACTIVE',
                [
                    'values' => ['N', 'Y'],
                    'default' => 'Y',
                    'title' => Loc::getMessage('OPTION_ENTITY_ACTIVE_FIELD'),
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
                    'title' => Loc::getMessage('OPTION_ENTITY_NAME_FIELD'),
                ]
            ),
        ];
    }
}