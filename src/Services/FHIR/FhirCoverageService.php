<?php

/**
 * FhirCoverageService
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Yash Bothra <yashrajbothra786@gmail.com>
 * @copyright Copyright (c) 2020 Yash Bothra <yashrajbothra786@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Services\FHIR;

use OpenEMR\FHIR\R4\FHIRDomainResource\FHIRCoverage;
use OpenEMR\FHIR\R4\FHIRElement\FHIRContactPoint;
use OpenEMR\FHIR\R4\FHIRElement\FHIRId;
use OpenEMR\Services\InsuranceService;

class FhirCoverageService extends FhirServiceBase
{
    /**
     * @var coverageService
     */
    private $coverageService;

    public function __construct()
    {
        parent::__construct();
        $this->coverageService = new InsuranceService();
    }

    /**
     * Returns an array mapping FHIR Coverage Resource search parameters to OpenEMR Coverage search parameters
     * @return array The search parameters
     */
    protected function loadSearchParameters()
    {
        return  [
            'patient' => ['patient']
        ];
    }

    /**
     * Parses an OpenEMR Coverage record, returning the equivalent FHIR Coverage Resource
     *
     * @param array $dataRecord The source OpenEMR data record
     * @param boolean $encode Indicates if the returned resource is encoded into a string. Defaults to false.
     * @return FHIRCoverage
     */
    public function parseOpenEMRRecord($dataRecord = array(), $encode = false)
    {
        $coverageResource = new FHIRCoverage();

        $meta = array('versionId' => '1', 'lastUpdated' => gmdate('c'));
        $coverageResource->setMeta($meta);

        $id = new FHIRId();
        $id->setValue($dataRecord['uuid']);
        $coverageResource->setId($id);

        $coverageResource->setStatus("active");

        $coverageResource->setAddress(array(
            'line' => [$dataRecord['street']],
            'city' => $dataRecord['city'],
            'state' => $dataRecord['state'],
            'postalCode' => $dataRecord['postal_code'],
        ));

        if (!empty($dataRecord['name'] && $dataRecord['name'] != "`s Home")) {
            $CoverageResource->setName($dataRecord['name']);
        }

        if (!empty($dataRecord['phone'])) {
            $phone = new FHIRContactPoint();
            $phone->setSystem('phone');
            $phone->setValue($dataRecord['phone']);
            $coverageResource->addTelecom($phone);
        }

        if (!empty($dataRecord['fax'])) {
            $fax = new FHIRContactPoint();
            $fax->setSystem('fax');
            $fax->setValue($dataRecord['fax']);
            $coverageResource->addTelecom($fax);
        }

        if (!empty($dataRecord['website'])) {
            $url = new FHIRContactPoint();
            $url->setSystem('website');
            $url->setValue($dataRecord['website']);
            $coverageResource->addTelecom($url);
        }

        if (!empty($dataRecord['email'])) {
            $email = new FHIRContactPoint();
            $email->setSystem('email');
            $email->setValue($dataRecord['email']);
            $coverageResource->addTelecom($email);
        }

        if ($encode) {
            return json_encode($coverageResource);
        } else {
            return $coverageResource;
        }
    }

    /**
     * Performs a FHIR Coverage Resource lookup by FHIR Resource ID
     *
     * @param $fhirResourceId //The OpenEMR record's FHIR Coverage Resource ID.
     */
    public function getOne($fhirResourceId)
    {
        $processingResult = $this->coverageService->getOne($fhirResourceId);
        if (!$processingResult->hasErrors()) {
            if (count($processingResult->getData()) > 0) {
                $openEmrRecord = $processingResult->getData()[0];
                $fhirRecord = $this->parseOpenEMRRecord($openEmrRecord);
                $processingResult->setData([]);
                $processingResult->addData($fhirRecord);
            }
        }
        return $processingResult;
    }

    /**
     * Searches for OpenEMR records using OpenEMR search parameters
     *
     * @param  array openEMRSearchParameters OpenEMR search fields
     * @return ProcessingResult
     */
    public function searchForOpenEMRRecords($openEMRSearchParameters)
    {
        
        return $this->coverageService->getAllData($openEMRSearchParameters);
    }

    public function parseFhirResource($fhirResource = array())
    {
        // TODO: If Required in Future
    }

    public function insertOpenEMRRecord($openEmrRecord)
    {
        // TODO: If Required in Future
    }

    public function updateOpenEMRRecord($fhirResourceId, $updatedOpenEMRRecord)
    {
        // TODO: If Required in Future
    }
}
