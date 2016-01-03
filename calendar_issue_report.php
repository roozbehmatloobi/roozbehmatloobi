#!/usr/bin/php5
<?php
// include required files
include_once 'script_bootstrap.php';

// get db
$db  = Zend_Registry::get('db');
$sql = "SELECT
            cu.id
	FROM
	    common_users cu
	    LEFT JOIN common_orders co ON (co.common_user_id = cu.id)
	    LEFT JOIN common_orders_adjustments coa ON (coa.common_order_id = co.id)
	WHERE
	    coa.reference = 947831
	    AND co.status NOT IN ('CANCELLED', 'PENDING') 
	GROUP BY
	    cu.id
	ORDER BY
	    cu.id";

$user_ids = $db->fetchCol($sql);
$first_order_count = 0;

foreach ($user_ids as $id) {
	
	// test if the first order used the 947831 (7th-Birthday) promo code
	$sql = "SELECT id FROM common_orders WHERE common_user_id = {$id} AND status NOT IN ('CANCELLED', 'PENDING') ORDER BY id ASC LIMIT 1";
	$first_order_id = $db->fetchOne($sql);
	
	// test if the first order used the 947831 (7th-Birthday) promo code
	$sql = "SELECT order_number FROM common_orders WHERE id = {$first_order_id}";
	$first_order_number = $db->fetchOne($sql);	
	
	$sql = "SELECT COUNT(id) FROM common_orders_adjustments WHERE common_order_id = {$first_order_id} AND reference = 947831";
	$used_promo = $db->fetchOne($sql);
	
	if ($used_promo > 0)  $first_order_count++;
	
	echo "$first_order_id : $first_order_number : $used_promo\n";
}

echo "TOTAL COUNT: " . sizeof($user_ids) . "\n";
echo "FIRST TIME COUNT: " . $first_order_count . "\n";
echo "Done\n";