#!/usr/bin/php5
<?php
// include required files
include_once 'script_bootstrap.php';
$db     = Zend_Registry::get('db');
$sql    = "SELECT id, cart_data FROM common_users WHERE cart_data IS NOT NULL OR cart_data != ''";
$users  = $db->fetchAll($sql);
$gcitem = 0;

foreach ($users as $user) {
	$cart_data = unserialize($user['cart_data']);
	if (isset($cart_data['items']) && is_array($cart_data['items']) && sizeof($cart_data['items']) > 0) {
		
		foreach ($cart_data['items'] as $item) {
			$cp_id = $item['product_id'];
			if (!$cp_id) continue;

			if (!empty($item['options'])) continue;
			
			$sql   = "SELECT type FROM client_products WHERE id = $cp_id";
			$cp    = $db->fetchRow($sql);
			if ($cp && $cp['type'] == 'GiftCard') {
				$gcitem++;
				echo "{$user['id']}\n";
				Zend_Debug::dump($cart_data);
			}
		}
	}
}


echo "\nFound $gcitem gc items\n";
echo "\nDone.\n";
