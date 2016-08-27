<?php
try{
    $db = new PDO("mysql:host=localhost;dbname=shirts4mike;port=3306", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $db->exec("SET NAMES 'utf8'");
}catch(Exception $e){
    echo "Could not connect to the database.";
    exit;
}

//echo "Woo-hoo!";

try{
    $results = $db->query("SELECT name, price, img, sku, paypal FROM products ORDER BY sku ASC");
    echo "Our query ran successfully.";

}catch(Exception $e){
    echo "Data could not be retrieved.";
}

echo "<pre>";
$product = $results->fetchAll(PDO::FETCH_ASSOC);
//var_dump($product);