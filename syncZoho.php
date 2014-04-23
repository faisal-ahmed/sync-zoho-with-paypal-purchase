<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 4/23/14
 * Time: 4:25 PM
 */

require_once 'utils_zoho_request.php';

function addRelatedProductsWithZohoContact($contact_email, $product_name) {
    $zohoConnector = new ZohoDataSync();
    $zohoContactDetails = $zohoConnector->searchRecordsByPDC(CONTACT_MODULE, 'email', $contact_email);
    $zohoProductDetails = $zohoConnector->searchRecordsByPDC(PRODUCT_MODULE, 'Product Name', $product_name);

    debug($zohoContactDetails);
    debug($zohoProductDetails);
}

function debug($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}