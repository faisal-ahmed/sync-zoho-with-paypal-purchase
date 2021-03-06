<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 4/23/14
 * Time: 4:41 PM
 */

require_once 'syncZoho.php';
require_once 'updateCasesStatus.php';

if (isset($_SERVER['REQUEST_METHOD']) && (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' || strtoupper($_SERVER['REQUEST_METHOD']) === 'GET')) {
    if (isset($_REQUEST['security_token']) && $_REQUEST['security_token'] === 'gatekeeper404') {
        /*$contact_email = urldecode(trim($_REQUEST['email']));*/
        $contact_id = urldecode(trim($_REQUEST['id']));
        $product_name = urldecode(trim($_REQUEST['product_name']));

        if ($contact_id !== '' && $product_name !== '') {
            addRelatedProductsWithZohoContact($contact_id, $product_name);
        }
    } else if (isset($_REQUEST['security_token']) && $_REQUEST['security_token'] === 'gatekeeperCasesStatus') {
        $cases_id = urldecode(trim($_REQUEST['id']));

        if ($cases_id !== '') {
            updateCaseStatus($cases_id);
        }
    }
}