<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\SystemException,
    Bitrix\Main\Loader,
    Bitrix\Main\Grid\Options,
    Bitrix\Main\Entity\Base,
    Bitrix\Main\UI\PageNavigation,
    Bitrix\Iblock\PropertyEnumerationTable;

class CIblocList extends CBitrixComponent
{
    // выполняет основной код компонента, аналог конструктора (метод подключается автоматически)
    public function executeComponent()
    {
        try {
            // подключаем метод проверки подключения модуля «Информационные блоки»
            $this->checkModules();
            // подключаем метод подготовки массива $arResult
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
//        todo
//        $arParams['GRID_ID'] = $arParams['GRID_ID'];


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
        // если нет валидного кеша, получаем данные из БД
        if ($this->startResultCache()) {



            $entity = new $this->arParams['ENTITY'];

            /**  TODO */



            $this->arResult = [];
            $this->arResult['gridId'] = $this->arParams['GRID_ID'];
            $this->arResult['columns'] = [];
            $this->arResult['pageSizes'] = [
                ['NAME' => "5", 'VALUE' => '5'],
                ['NAME' => '10', 'VALUE' => '10'],
                ['NAME' => '20', 'VALUE' => '20'],
            ];

            foreach ($entity->getMap() as $field) {
                $this->arResult['columns'][] = [
                    'id' => $field->getName(),
                    'name' => $field->getName(),
                    'sort' => $field->getName(),
                    'default' => true
                ];
            }
            if($this->arParams['DOP_FIELDS']) {
                $this->arResult['columns'] = array_merge($this->arResult['columns'], $this->arParams['DOP_FIELDS']);
            }


            $grid_options = new Options($this->arResult['gridId']);
            $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
            $nav_params = $grid_options->GetNavParams();





            $this->arResult['nav'] = new PageNavigation('our_custom_grid_nav');
            $this->arResult['nav']->allowAllRecords(false)
                ->setPageSize($nav_params['nPageSize'])
                ->setPageSizes($this->arResult['pageSizes'])
                ->initFromUri();

//            $rows = $entity::query()
//                ->setSelect(array_merge($this->arParams['DEFAULT_OPTIONS'], $this->arParams['OPTIONS']))
//                ->setOffset($this->arResult['nav']->getOffset())
//                ->setLimit($this->arResult['nav']->getLimit())
//                ->setFilter($this->arParams['FILTER'])
//                ->setOrder($sort['sort'])
//                ->exec();
//

            $rows = $entity::getList([
                "select" => array_merge($this->arParams['DEFAULT_OPTIONS'], $this->arParams['OPTIONS']),
                "filter" => $this->arParams['FILTER'],
                "order" => $sort['sort'],
                "count_total" => true,
                "offset" => $this->arResult['nav']->getOffset(),
                "limit" => $this->arResult['nav']->getLimit(),
            ]);

            $this->arResult['nav']->setRecordCount($rows->getCount());

            /*
            $collections = $rows->fetchCollection();
            foreach ($collections as $collection) {
                $temp = [];
                $DetailUrl = str_replace('#code#', $collection->get('CODE'), $this->arParams['DETAIL_PAGE_URL']);
                $temp['id'] = 'id_'.$collection->get('ID');
                $temp['actions'] = [
                    [
                        'text'    => 'Просмотр',
                        'default' => true,
                        'onclick' => 'document.location.href="'.$DetailUrl.'"'
                    ]
                ];
                $temp['data'] = [];

                foreach ($entity->getMap() as $field) {



                    if($field->getName() == 'NAME') {
                        $temp['data']['NAME'] = '<a href="'.$DetailUrl.'">'.$collection->get('CODE').'</a>';
                    }
                    elseif(is_object($collection->get($field->getName())) && $field->getName() != 'OPTIONS') {
                        $temp['data'][$field->getName()] = $collection->get($field->getName())->getName();
                    }
                    elseif($field->getName() == 'OPTIONS') {

                        echo "<pre>";
                        print_r($collection->get('OPTIONS')->getOption()->getName());
                        echo "</pre>";

                        echo "<pre>";
                        print_r($collection->get('OPTIONS')->getValue());
                        echo "</pre>";




                    }else {
                        $temp['data'][$field->getName()] = $collection->get($field->getName());
                    }

                }


                $this->arResult['LIST'][] = $temp;
            }
            */

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
                        $this->arResult['LIST'][$row['ID']]['data'][$code] = $row[$code];
                    }
                }

            }

            // кэш не затронет весь код ниже, он будут выполняться на каждом хите, здесь работаем с другим $arResult, будут доступны только те ключи массива, которые перечислены в вызове SetResultCacheKeys()
            if (!empty($this->arResult)) {
                // ключи $arResult перечисленные при вызове этого метода, будут доступны в component_epilog.php и ниже по коду, обратите внимание там будет другой $arResult
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
