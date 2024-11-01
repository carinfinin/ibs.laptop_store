<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var  $arResult
 * @var  $APPLICATION

 */
$APPLICATION->SetTitle($arResult['item']['NAME']);


?>


<div class="container mt-5">


    <div class="row">
        <div class="col-md-6">
            <div class="product-image">
                Нет фото
            </div>
        </div>
        <div class="col-md-6">
            <h2>Описание</h2>
            <p><?=$arResult['item']['NAME']?></p>

            <h3 class="mt-3">Характеристики</h3>
            <ul>
                <li>Бренд: <?=$arResult['item']['BRAND_NAME']?></li>
                <li>Модель: <?=$arResult['item']['MODEL_NAME']?></li>
                <?foreach ($arResult['item']['OPTIONS']['OPTIONS_VALUE'] as $k => $v) {?>
                    <li><?=$arResult['item']['OPTIONS']['OPTIONS_NAME'][$k]?>: <?=$v?></li>
                <?}?>

            </ul>

            <button class="btn btn-primary btn-lg">Купить</button>
            <button class="btn btn-secondary btn-lg">Добавить в корзину</button>
        </div>
    </div>
</div>
