<?php

/**
 * FhirCoverageRestController
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Yash Bothra <yashrajbothra786@gmail.com>
 * @copyright Copyright (c) 2020 Yash Bothra <yashrajbothra786@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\RestControllers\FHIR;

use OpenEMR\Services\FHIR\FhirCoverageService;
use OpenEMR\Services\FHIR\FhirResourcesService;
use OpenEMR\RestControllers\RestControllerHelper;
use OpenEMR\FHIR\R4\FHIRResource\FHIRBundle\FHIRBundleEntry;

class FhirCoverageRestController
{
    private $fhirCoverageService;
    private $fhirService;

    public function __construct()
    {
        $this->fhirCoverageService = new FhirCoverageService();
        $this->fhirService = new FhirResourcesService();
    }

    /**
     * Queries for a single FHIRrCoverage resource by FHIR id
     * @param $fhirId The FHIRrCoverage resource id (uuid)
     * @returns 200 if the operation completes successfully
     */
    public function getOne($fhirId)
    {
        $processingResult = $this->fhirCoverageService->getOne($fhirId);
        return RestControllerHelper::handleProcessingResult($processingResult, 200);
    }

    /**
     * Queries for FHIRrCoverage resources using various search parameters.
     * Search parameters include:
     * - patient (puuid)
     * @return FHIR bundle with query results, if found
     */
    public function getAll($searchParams)
    {
        foreach($searchParams as $key=>$value) {
            error_log("The key log is " . $key);
            error_log("The value log is " . $value);
        }
        $processingResult = $this->fhirCoverageService->getAll($searchParams);
        $bundleEntries = array();
        foreach ($processingResult->getData() as $index => $searchResult) {
            $bundleEntry = [
                'fullUrl' =>  \RestConfig::$REST_FULL_URL . '/' . $searchResult->getId(),
                'resource' => $searchResult
            ];
            $fhirBundleEntry = new FHIRBundleEntry($bundleEntry);
            array_push($bundleEntries, $fhirBundleEntry);
        }
        $bundleSearchResult = $this->fhirService->createBundle('Coverage', $bundleEntries, false);
        $searchResponseBody = RestControllerHelper::responseHandler($bundleSearchResult, null, 200);
        return $searchResponseBody;
    }
}
