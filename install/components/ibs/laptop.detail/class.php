<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\SystemException,
    Bitrix\Main\Loader,
    Bitrix\Main\Grid\Options,
    Bitrix\Main\Entity\Base,
    Bitrix\Main\UI\PageNavigation,
    Bitrix\Iblock\PropertyEnumerationTable;

class ComponentCustomList extends CBitrixComponent
{
    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }

    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    protected function checkModules()
    {
        if (!Loader::includeModule('ibs.laptop_store'))
            throw new SystemException(Loc::getMessage('MODULE_NOT_INSTALLED'));
    }

    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }
        $arParams['DEFAULT_OPTIONS'] = ['*'];

        if (!empty($arParams["FILTER_NAME"]) && preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {

            $arParams['FILTER'] = $GLOBALS[$arParams["FILTER_NAME"]] ?? [];
            if (!is_array($arParams['FILTER'])) {
                $arParams['FILTER'] = [];
            }
        }
        return $arParams;
    }


    protected function getResult()
    {

        if ($this->startResultCache(false, [$_REQUEST, $this->arParams])) {


            $entity = new LaptopStore\Entity\LapTopTable();

            $rows = $entity::query()
                ->setSelect([
                    '*',
                    'MODEL_NAME' => 'MODEL.NAME',
                    'BRAND_NAME' => 'MODEL.BRAND.NAME',
                    'OPTIONS_VALUE' => 'OPTIONS.VALUE',
                    'OPTIONS_NAME' => 'OPTIONS.OPTION.NAME'
                ])
                ->setFilter(['CODE' => $this->arParams['ELEMENT_CODE']])
                ->exec();



            while($row = $rows->fetch()) {

                foreach ($row as $code => $field) {
                    if($code == 'OPTIONS_VALUE' || $code == 'OPTIONS_NAME') {
                        $this->arResult['item']['OPTIONS'][$code][] = $field;
                    }else{
                        $this->arResult['item'][$code] = $field;
                    }
                }

            }



            if (!empty($this->arResult)) {
                $this->SetResultCacheKeys(
                    array()
                );
                $this->IncludeComponentTemplate();
            } else {
                $this->AbortResultCache();
                \Bitrix\Iblock\Component\Tools::process404(
                    "Секция не найдена",
                    true,
                    true
                );
            }
        }
    }
}
