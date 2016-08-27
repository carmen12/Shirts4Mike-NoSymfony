<?php
function get_list_view_html ($product){
//    $product = get_products_all();
    $output = "";
    $output = $output . "<li>";
    $output = $output . '<a href="'.BASE_URL.'shirts/'.$product["sku"].'/">';
    $output = $output . '<img src="'.BASE_URL.$product["img"].'" alt="'.$product["name"].'">';
    $output = $output . "<p>View Details</p>";
    $output = $output . "</a>";
    $output = $output . "</li>";
return $output;
}

function get_products_recent() {

    require (ROOT_PATH . "inc/database.php");

    try{
        $results = $db->query("
            SELECT name, price, img, sku, paypal
            FROM products
            ORDER BY sku DESC
            LIMIT 4");
    }catch(Exception $e){
        echo "Problems with the get_products_recent function.";
        exit;
    }
    $recent = $results->fetchAll(PDO::FETCH_ASSOC);
    $recent = array_reverse($recent);

    return $recent;
}

function get_products_count() {
    require (ROOT_PATH . "inc/database.php");

    try{
        $results = $db->query("
            SELECT COUNT(sku)
            FROM products");
    }catch(Exception $e){
        echo "Problems with the get_products_count function.";
        exit;
    }

    return intval($results->fetchColumn(0));
}

function get_products_subset($positionStart, $positionEnd) {
    $offset = $positionStart -1;
    $row = $positionEnd - $positionStart + 1;
    require (ROOT_PATH . "inc/database.php");
    try{
        $results = $db->prepare("
            SELECT name, price, img, sku, paypal
            FROM products
            ORDER BY sku ASC
            LIMIT ?, ?");
      $results->bindParam(1,$offset,PDO::PARAM_INT);
      $results->bindParam(2,$row,PDO::PARAM_INT);
      $results->execute();
    }catch(Exception $e){
        echo "Problems with the get_products_subset function.";
        exit;
    }
    $subset = $results->fetchAll(PDO::FETCH_ASSOC);
    return $subset;
}

function get_products_search($s) {
    require (ROOT_PATH . "inc/database.php");
    try{
        $results = $db->prepare("
            SELECT name, price, img, sku, paypal
            FROM products
            WHERE name LIKE ?
            ORDER BY sku");
        $results->bindValue(1,"%".$s."%");
        $results->execute();
    }catch(Exception $e){
        echo "Problems with the get_products_search function.";
        exit;
    }

    $matches =  $results->fetchAll(PDO::FETCH_ASSOC);
    return $matches;
}

function get_products_all(){

        require(ROOT_PATH . "inc/database.php");
//First QUERY to display the shirts
        try{
            $results = $db->query("SELECT name, price, img, sku, paypal FROM products ORDER BY sku ASC");
        }catch(Exception $e){
            echo "Data could not be retrieved from the database.";
            exit;
        }

        $products = $results->fetchAll(PDO::FETCH_ASSOC);

        return $products;
}

    function get_product_single($sku){

        require(ROOT_PATH . "inc/database.php");
//Second QUERY to display every single shirt(shirt detail)
        try{
            $results = $db->prepare("SELECT name, price, img, sku, paypal FROM products WHERE sku=?");
            $results->bindParam(1,$sku);
            $results->execute();
        }catch(Exception $e){
            echo "Data could not be retrieved from the database.";
            exit;
        }
        $product = $results->fetch(PDO::FETCH_ASSOC);

        if($product === false){return $product;}
//Third QUERY to display the shirt sizes on the shirt detail pages
        $product["sizes"] = array();

        try {
            $results = $db->prepare("
            SELECT size
            FROM   products_sizes ps
            INNER JOIN sizes s ON ps.size_id = s.id
            WHERE product_sku = ?
            ORDER BY `order`");
            $results->bindParam(1,$sku);
            $results->execute();
        } catch (Exception $e) {
            echo "Data could not be retrieved from the database.";
            exit;
        }

        while( $row = $results->fetch(PDO::FETCH_ASSOC)){
            $product["sizes"][] = $row["size"];
        }

        return $product;
    }
?>














