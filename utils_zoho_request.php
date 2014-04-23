<?php

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

    //Sample XML xmlData=<Products><row no="2"><FL val="PRODUCTID">847862000000082102</FL></row></Products>
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

    public function errorFound($xml) {
        if ((isset($xml->nodata->code) && trim($xml->nodata->code) !== "")
            || (isset($xml->error->code) && trim($xml->error->code) !== "")) {
            return true;
        }
        return false;
    }
}

?>