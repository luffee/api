<?php


class Product
{
    // database connection and table name
    private $conn;


    // object properties
    public $id;
    public $name;
    public $price;
    public $sku;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT vm_p.virtuemart_product_id AS id, product_sku AS sku, product_name AS name, product_price AS price
                    FROM b635h_virtuemart_products vm_p, b635h_virtuemart_products_sr_yu vm_p_sr, b635h_virtuemart_product_prices vm_pr
                    WHERE 
                    vm_p.virtuemart_product_id= vm_p_sr.virtuemart_product_id
                    AND 
                    vm_p_sr.virtuemart_product_id = vm_pr.virtuemart_product_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;


    }

    public function search($keyword)
    {
        $query = "SELECT DISTINCT vm_p.virtuemart_product_id AS id, product_sku AS sku, product_name AS name, product_price AS price, file_url AS url_thumb
                    FROM 
                        b635h_virtuemart_products vm_p, b635h_virtuemart_products_sr_yu vm_p_sr, b635h_virtuemart_product_prices vm_pr, b635h_virtuemart_product_medias vm_pm, b635h_virtuemart_medias m
                    WHERE
                        vm_p.virtuemart_product_id = vm_p_sr.virtuemart_product_id
                    AND
                        vm_p_sr.virtuemart_product_id = vm_pr.virtuemart_product_id
                    AND   
                        vm_p.virtuemart_product_id = vm_pm.virtuemart_product_id
                    AND  
                        vm_pm.virtuemart_media_id = m.virtuemart_media_id
                    AND  
                        vm_p.product_sku LIKE  ?";

        $stmt = $this->conn->prepare($query);

        $keyword = htmlspecialchars(strip_tags($keyword));
        $keyword = "%{$keyword}%";

        //bind
        $stmt->bindParam(1, $keyword);

        $stmt->execute();

        return $stmt;
    }


}