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
            $arParams['FILTER']['ACTIVE'] = 'Y';
            if (!is_array($arParams['FILTER'])) {
                $arParams['FILTER'] = ['ACTIVE' => 'Y'];
            }
        }
        return $arParams;
    }


    protected function getResult()
    {
        $this->arResult = [];
        $this->arResult['gridId'] = $this->arParams['GRID_ID'];
        $this->arResult['columns'] = [];
        $this->arResult['pageSizes'] = [
            ['NAME' => "5", 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
        ];
        $grid_options = new Options($this->arResult['gridId']);
        $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $nav_params = $grid_options->GetNavParams();

        $this->arResult['nav'] = new PageNavigation('our_custom_grid_nav');
        $this->arResult['nav']->allowAllRecords(false)
            ->setPageSize($nav_params['nPageSize'])
            ->setPageSizes($this->arResult['pageSizes'])
            ->initFromUri();






        if ($this->startResultCache(false, [$_REQUEST, $this->arParams, $grid_options, $sort, $nav_params, $this->arResult['pageSizes']])) {


            $entity = new $this->arParams['ENTITY'];

            /**  TODO */


            $sortValid = [];
            foreach ($entity->getMap() as $field) {
                $temp = [
                    'id' => $field->getName(),
                    'name' => $field->getName(),
                    'sort' => '',
                    'default' => true
                ];
                if($field->getName() == 'ID' || $field->getName() == 'NAME')
                    $temp['sort'] = $field->getName();

                $this->arResult['columns'][] = $temp;

                if($temp['sort'])
                    $sortValid[] = $temp['sort'];
                unset($temp);
            }
            unset($field);


            if($this->arParams['DOP_FIELDS']) {
                $this->arResult['columns'] = array_merge($this->arResult['columns'], $this->arParams['DOP_FIELDS']);
            }


            foreach ($this->arParams['DOP_FIELDS'] as $FIELD) {
                $sortValid[] = $FIELD['sort'];

            }

            $select = array_merge($this->arParams['DEFAULT_OPTIONS'], $this->arParams['OPTIONS']);




            if ($sort['sort']) {
                foreach ($sort['sort'] as $code => $value) {
                    if(!in_array($code, $sortValid)) {
                        unset($sort['sort'][$code]);
                    }
                }

            }
            unset($code);
            unset($value);



            $rows = $entity::getList([
                "select" => $select,
                "filter" => $this->arParams['FILTER'],
                "order" => $sort['sort'],
                "count_total" => true,
                "offset" => $this->arResult['nav']->getOffset(),
                "limit" => $this->arResult['nav']->getLimit(),
            ]);

            $this->arResult['nav']->setRecordCount($rows->getCount());


            while($row = $rows->fetch()) {


                $this->arResult['LIST'][$row['ID']]['id'] = 'id_'.$row['ID'];
                $this->arResult['LIST'][$row['ID']]['actions'] = [
                    [
                        'text'    => 'Просмотр',
                        'default' => true,
                        'onclick' => 'document.location.href="?op=view&id='.$row['ID'].'"'
                    ]
                ];


                foreach ($row as $code => $field) {

                    if($code == 'OPTIONS_') {

                        $this->arResult['LIST'][$row['ID']]['data'][$row['OPTIONS_NAME']] = $row['OPTIONS_'];
                    }

                    $DetailUrl = str_replace('#code#', $row['CODE'], $this->arParams['DETAIL_PAGE_URL']);
                    if($code == 'NAME') {
                        $this->arResult['LIST'][$row['ID']]['data']['NAME'] = '<a href="'.$DetailUrl.'">'.$row['NAME'].'</a>';
                    }else{
                        // удаляем нижнее подчёркивание для алиасов
                        $this->arResult['LIST'][$row['ID']]['data'][rtrim($code, '_')] = $field;
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
                    "not found",
                    true,
                    true
                );
            }
        }
    }
}
