#!/usr/bin/php5
<?php

// include required files
include_once 'script_bootstrap.php';

// create new discount (certificate) record
$cdModel  = Base::getModel('Client_Discounts');
$options = array(
	'date_start' => '2012-11-01',
	'date_end'   => '2013-01-30',
);


// 139 vouchers with $30 value, Expiry date of 30/1/13
for ($count = 0; $count <= 139; $count++) {
	$discount = $cdModel->createNewGiftPackDiscount(NULL, 'PROMO $30', 1, 30.00, $options);
	$discount->generateGiftpackPDF();
	echo $discount->id . "\n";
}

// 40 vouchers with $25 value, Expiry date of 30/1/13
for ($count = 0; $count <= 40; $count++) {
	$discount = $cdModel->createNewGiftPackDiscount(NULL, 'PROMO $25', 1, 25.00, $options);
	$discount->generateGiftpackPDF();
	echo $discount->id . "\n";
}

echo "\nDone.\n";
