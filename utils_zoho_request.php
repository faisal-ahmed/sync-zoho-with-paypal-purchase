<?php

include_once 'Utilities.php';
include_once 'ZohoIntegrator.php';

class ZohoDataSync extends ZohoIntegrator
{
    public function __construct()
    {
        $this->resetWithDefaults();
        $authtokenSet = $this->setZohoAuthToken(AUTH_TOKEN);
        if ($authtokenSet !== true) {
            echo 'Please provide authtoken or set auth token first';
            die();
        }
    }

    public function doRequest()
    {
        $response = $this->buildRequestUri();
        if ($response !== true) return $response;
        $response = $this->buildUriParameter();
        if ($response !== true) return $response;
        return $this->sendCurl();
    }

    // Visit https://www.zoho.com/crm/help/api/getsearchrecordsbypdc.html to see the PDC lists
    public function searchRecordsByPDC($moduleName, $fieldName, $fieldValue)
    {
        $this->resetWithDefaults();
        $this->setZohoModuleName("$moduleName");
        $this->setZohoApiOperationType('getSearchRecordsByPDC');
        $extraParameter = array(
            "searchColumn" => "$fieldName",
            "searchValue" => "$fieldValue",
            "selectColumns" => "All",
        );

        $this->setZohoExtendedUriParameter($extraParameter);

        return $this->doRequest();
    }

    public function addRelatedRecords($moduleName, $id, $relatedModule, $xmlArray)
    {
        $this->resetWithDefaults();
        $this->setZohoModuleName("$moduleName");
        $this->setZohoApiOperationType('updateRelatedRecords');
        $this->setRequestMethod('POST');
        $extraParameter = array(
            "id" => "$id",
            "relatedModule" => "$relatedModule"
        );
        if (($xmlSet = $this->setZohoXmlColumnNameAndValue($xmlArray)) !== true) return $xmlSet;

        $this->setZohoExtendedUriParameter($extraParameter);

        return $this->doRequest();
    }

    public function getRelatedRecords($moduleName, $id, $relatedModule)
    {
        $this->resetWithDefaults();
        $this->setZohoModuleName("$moduleName");
        $this->setZohoApiOperationType('getRelatedRecords');
        $this->setRequestMethod('POST');
        $extraParameter = array(
            "id" => "$id",
            "parentModule" => "$relatedModule",
            "fromIndex" => 1,
            "toIndex" => 200,
            "newFormat" => 2,
        );

        $this->setZohoExtendedUriParameter($extraParameter);

        return $this->doRequest();
    }

    public function updateRecords($moduleName, $id, $xmlArray, $wfTrigger = 'false')
    {
        $this->resetWithDefaults();
        $this->setZohoModuleName("$moduleName");
        $this->setZohoApiOperationType('updateRecords');
        $this->setRequestMethod('POST');
        $extraParameter = array(
            "id" => "$id",
        );
        if ($wfTrigger != 'false') $this->setWfTrigger($wfTrigger);
        if (($xmlSet = $this->setZohoXmlColumnNameAndValue($xmlArray)) !== true) return $xmlSet;

        $this->setZohoExtendedUriParameter($extraParameter);

        return $this->doRequest();
    }
}

?>