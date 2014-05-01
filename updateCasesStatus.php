<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 5/1/14
 * Time: 8:01 PM
 */

require_once 'utils_zoho_request.php';

function updateCaseStatus($cases_id) {
    $zohoConnector = new ZohoDataSync();

    $zohoCasesDetails = $zohoConnector->getRelatedRecords(TASK_MODULE, $cases_id, CASE_MODULE);
    $xmlCase = simplexml_load_string($zohoCasesDetails);

    $flag = 1;
    if (isset($xmlCase->result) && isset($xmlCase->result->Tasks)) {
        foreach ($xmlCase->result->Tasks->row as $key => $value) {
            foreach ($value->FL as $key => $row) {
                $temp_value = (string)$row['val'];
                if (strtolower($temp_value) == 'status' && strtolower(trim($row)) !== 'completed') {
                    $flag = 0;
                    break;
                }
            }
            if (!$flag) {
                break;
            }
        }
    } else {
        return 'No product found with that name!';
    }

    if ($flag) {
        $xmlArray = array(
            1 => array(
                'Status' => "Closed",
            ),
        );

        $zohoCasesUpdates = $zohoConnector->updateRecords(CASE_MODULE, $cases_id, $xmlArray, 'true');
        return true;
    }

    return false;
}