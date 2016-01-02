#!/usr/bin/php5
<?php
// include required files
include_once 'script_bootstrap.php';

// get db
$db             = Zend_Registry::get('db');
$sql = "SELECT cu.id, n.email FROM newsletter n 
		            LEFT JOIN common_users cu ON (n.email = cu.email)";
$common_users = $db->fetchAll($sql);
$filename = APPLICATION_PATH.'/../logs/report/file.csv';
$fp = fopen($filename, 'w');

$headers = array(' Email',
		 ' name',
		 ' Sub',
                 ' Uploads',
		 ' PaidOrders',
		 ' Copies',
		 ' LastUpload',
		 ' LastOrder',
                 ' Photobookuploads',
                 ' PaidOrders',
                 ' ProductCopies',
                 ' LastOrder',
                 ' A3',' A4',' A5',
		 ' Deluxe', ' Fusion', ' Premium', ' Classic', ' Printed', ' Faux',
                 ' Satin170g', ' Satin250g', ' Glass170g', ' Glass250g', ' Art140g'
	    );

fputcsv($fp, $headers);

foreach ($common_users  as $common_user) {
	$id = $common_user['id'];
	$email = $common_user['email'];
        if ($id) { 
	    $traction       = new Base_Traction($id);
            $email          = $traction->email($email);
            $name           = $traction->name($id, $email);
	    $name 	    = str_replace(" ", "", $name);
            $sub            = $traction->sub($id);
	    $sub 	    = str_replace(" ", "", $sub);
	    $uploads        = $traction->productUploads();
            $paidOrders     = $traction->paidOrders();
            $productCopies  = $traction->productCopies();
            $LastUpload     = $traction->lastProductUploadDate();
	    $LastUpload     = str_replace(" ", "|", $LastUpload);
            $LastOrder      = $traction->lastOrderDate();
	    $LastOrder     = str_replace(" ", "|", $LastOrder);
            $photobookuploads       = $traction->productUploads('6');
            $photobookpaidOrders    = $traction->paidOrders('6');
            $photobookproductCopies = $traction->productCopies('6');
            $photobookLastOrder     = $traction->lastOrderDate('6');
	    $photobookLastOrder     = str_replace(" ", "|", $photobookLastOrder);
            $photobooksizeA3        = $traction->sizes('A3','6');
	    $photobooksizeA4        = $traction->sizes('A4','6');
	    $photobooksizeA5        = $traction->sizes('A5','6');
            $photobookDeluxe        = $traction->cover_types('Deluxe', '6');
	    $photobookFusion        = $traction->cover_types('Fusion', '6');
	    $photobookPremium       = $traction->cover_types('Premium', '6');
	    $photobookClassic       = $traction->cover_types('Classic', '6');
	    $photobookPrinted       = $traction->cover_types('Printed', '6');
	    $photobookFaux          = $traction->cover_types('Faux Leather', '6');
            $photobookSatin170g     = $traction->stocksOrdered('Satin 170g', '6');
	    $photobookSatin250g     = $traction->stocksOrdered('Satin 250g', '6');
	    $photobooksGlass170g    = $traction->stocksOrdered('Glass 170g', '6');
	    $photobookGlass250g     = $traction->stocksOrdered('Glass 250g', '6');
	    $photobookArt140g       = $traction->stocksOrdered('Art 140g', '6');
	    $data=array($email, $name,$sub,$uploads,$paidOrders,$productCopies,$LastUpload,$LastOrder,$photobookuploads,$photobookpaidOrders,$photobookproductCopies,$photobookLastOrder,$photobooksizeA3,$photobooksizeA4,$photobooksizeA5,$photobookDeluxe,$photobookFusion,$photobookPremium,$photobookClassic,$photobookPrinted,$photobookFaux,$photobookSatin170g,$photobookSatin250g,$photobooksGlass170g,$photobookGlass250g,$photobookArt140g);
            fputcsv($fp, $data);
	} else {
	    $email          = $traction->email($email);
            $name           = $traction->name($id, $email);
	    $name 	    = str_replace(" ", "", $name);
	    $sub            = $traction->sub(null, $email);
	    $data=array($email, $name,$sub);
            fputcsv($fp, $data);
	}
        
        if ($id % 1000 == 0) {
		echo "\n\nsleeping for 1 seconds\n\n";
		//sleep(1);
	}
}