<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 4/23/14
 * Time: 4:25 PM
 */

require_once 'utils_zoho_request.php';

function addRelatedProductsWithZohoContact($contact_id, $product_name) {
    $zohoConnector = new ZohoDataSync();
/*    $zohoContactDetails = $zohoConnector->searchRecordsByPDC(CONTACT_MODULE, 'email', $contact_email);
    $xmlContact = simplexml_load_string($zohoContactDetails);

    if (isset($xmlContact->result) && isset($xmlContact->result->Contacts)) {
        $contact_id = trim($xmlContact->result->Contacts->row->FL[0]);
    } else {
        return 'No contact found with that email!';
    }*/

    $zohoProductDetails = $zohoConnector->searchRecordsByPDC(PRODUCT_MODULE, 'productname', $product_name);
    $xmlProduct = simplexml_load_string($zohoProductDetails);

    if (isset($xmlProduct->result) && isset($xmlProduct->result->Products)) {
        $product_id = trim($xmlProduct->result->Products->row->FL[0]);
    } else {
        return 'No product found with that name!';
    }

    $xmlArray = array(
        1 => array(
            'PRODUCTID' => "$product_id",
        ),
    );

    $associateRelatedRecords = $zohoConnector->addRelatedRecords(CONTACT_MODULE, $contact_id, PRODUCT_MODULE, $xmlArray);
    $xml = simplexml_load_string($associateRelatedRecords);

    return ($xml->result->status->code === 200) ? true : false;
}