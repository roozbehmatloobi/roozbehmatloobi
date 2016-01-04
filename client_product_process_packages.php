#!/usr/bin/php5
<?php
/**
 * process all products packages
 *
 */
include_once 'script_bootstrap.php';

$config_interval = 250;
$config_sleep    = 15;

// get the start and end date
$start_id = isset($argv[2]) ? (int) $argv[2] : null;
$end_id   = isset($argv[3]) ? (int) $argv[3] : null;
if (empty($start_id) || empty($end_id)) die("Missing start OR end ID\n");

$db          = Zend_Registry::get('db');
$sql         = "SELECT id FROM client_products WHERE id BETWEEN $start_id AND $end_id ORDER BY id DESC";
$product_ids = $db->fetchCol($sql);

$cpModel = Base::getModel('Client_Products');
$counter = 0;
foreach($product_ids as $product_id) {
    
    $product = $cpModel->fetchRow("id = $product_id");
    if ($product) {
        echo "processing product {$product->id} ... ";
        $product->processPackages();
        echo "[INV:".$counter % $config_interval."][MEM:" . memuse(memory_get_usage(true)) . "]\n";
    }
    $counter++;
    if ($counter % $config_interval === 0) {
        echo "Sleeping for $config_sleep sec...\n";
        sleep($config_sleep);
    }
}

echo "Done.\n";


// returns the memory usage
function memuse($size) {
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . '' . $unit[$i];
}