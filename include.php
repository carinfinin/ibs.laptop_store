<?
Bitrix\Main\Loader::registerAutoloadClasses(
	"ibs.laptop_store",
	array(
		"LaptopStore\\Entity\\BrandTable" => "lib/brand.php",
		"LaptopStore\\Entity\\Brands" => "lib/brands.php",
		"LaptopStore\\Entity\\LapTopTable" => "lib/laptop.php",
		"LaptopStore\\Entity\\LapTopOptionTable" => "lib/laptop_option.php",
		"LaptopStore\\Entity\\ModelTable" => "lib/model.php",
		"LaptopStore\\Entity\\OptionTable" => "lib/option.php",
	)
);
