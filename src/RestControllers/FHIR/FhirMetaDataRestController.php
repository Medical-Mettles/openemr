<?php

/**
 * FhirMetaDataRestController
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Jerry Padgett <sjpadgett@gmail.com>
 * @copyright Copyright (c) 2018 Jerry Padgett <sjpadgett@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\RestControllers\FHIR;

use OpenEMR\Services\FHIR\FhirResourcesService;
use OpenEMR\Services\FHIR\FhirPatientService;
use OpenEMR\Services\FHIR\FhirValidationService;
use OpenEMR\RestControllers\RestControllerHelper;
use OpenEMR\FHIR\R4\FHIRResource\FHIRBundle\FHIRBundleEntry;
use OpenEMR\Validators\ProcessingResult;

require_once(__DIR__ . '/../../../_rest_config.php');

/**
 * Supports REST interactions with the FHIR patient resource
 */
class FhirMetaDataRestController
{
    private $fhirPatientService;
    private $fhirService;
    private $fhirValidate;

    public function __construct()
    {
        $this->fhirService = new FhirResourcesService();
        $this->fhirValidate = new FhirValidationService();
    }

    

    /**
     * Queries for a single FHIR patient resource by FHIR id
     * @param $fhirId The FHIR patient resource id (uuid)
     * @returns 200 if the operation completes successfully
     */
    public function getOne($fhirId)
    {
        $processingResult = $this->fhirPatientService->getOne($fhirId, true);
        return RestControllerHelper::handleProcessingResult($processingResult, 200);
    }

    /**
     * Queries for FHIR patient resources using various search parameters.
     * Search parameters include:
     * - address (street, postal code, city, or state)
     * - address-city
     * - address-postalcode
     * - address-state
     * - birthdate
     * - email
     * - family
     * - gender
     * - given (first name or middle name)
     * - name (title, first name, middle name, last name)
     * - phone (home, business, cell)
     * - telecom (email, phone)
     * @return FHIR bundle with query results, if found
     */
    public function getAll($searchParams)
    {
        $searchResponseBody = '{
            "resourceType": "CapabilityStatement",
            "status": "active",
            "date": "2020-09-03T15:03:48+00:00",
            "publisher": "Not provided",
            "kind": "instance",
            "software": {
                "name": "HAPI FHIR Server",
                "version": "5.0.2"
            },
            "implementation": {
                "description": "HAPI FHIR",
                "url": "http://localhost/openemr/apis/fhir"
            },
            "fhirVersion": "4.0.1",
            "format": [
                "application/fhir+xml",
                "application/fhir+json"
            ],
            "rest": [
                {
                    "mode": "server",
                    "resource": [
                        {
                            "type": "Claim",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Claim",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Patient receiving the products or services"
                                }
                            ]
                        },
                        {
                            "type": "ClaimResponse",
                            "profile": "http://hl7.org/fhir/StructureDefinition/ClaimResponse",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "The subject of care"
                                }
                            ]
                        },
                        {
                            "type": "Communication",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Communication",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                }
                            ]
                        },
                        {
                            "type": "CommunicationRequest",
                            "profile": "http://hl7.org/fhir/StructureDefinition/CommunicationRequest",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Focus of message"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Focus of message"
                                }
                            ]
                        },
                        {
                            "type": "Condition",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Condition",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "category",
                                    "type": "token",
                                    "documentation": "The category of the condition"
                                },
                                {
                                    "name": "clinical-status",
                                    "type": "string",
                                    "documentation": "The clinical status of the condition"
                                },
                                {
                                    "name": "code",
                                    "type": "token",
                                    "documentation": "Code for the condition"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Who has the condition?"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "Who has the condition?"
                                },
                                {
                                    "name": "verification-status",
                                    "type": "string",
                                    "documentation": "unconfirmed | provisional | differential | confirmed | refuted | entered-in-error"
                                }
                            ]
                        },
                        {
                            "type": "Coverage",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Coverage",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "beneficiary",
                                    "type": "reference",
                                    "documentation": "Covered party"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Retrieve coverages for a patient"
                                },
                                {
                                    "name": "policy-holder",
                                    "type": "reference",
                                    "documentation": "Reference to the policyholder"
                                },
                                {
                                    "name": "subscriber",
                                    "type": "reference",
                                    "documentation": "Reference to the subscriber"
                                }
                            ]
                        },
                        {
                            "type": "Device",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Device",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Patient information, if the resource is affixed to a person"
                                },
                                {
                                    "name": "type",
                                    "type": "string",
                                    "documentation": "The type of the device"
                                }
                            ]
                        },
                        {
                            "type": "DeviceRequest",
                            "profile": "http://hl7.org/fhir/StructureDefinition/DeviceRequest",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "authored-on",
                                    "type": "date",
                                    "documentation": "When the request transitioned to being actionable"
                                },
                                {
                                    "name": "code",
                                    "type": "string",
                                    "documentation": "Code for what is being requested/ordered"
                                },
                                {
                                    "name": "device",
                                    "type": "string",
                                    "documentation": "Reference to resource that is being requested/ordered"
                                },
                                {
                                    "name": "intent",
                                    "type": "string",
                                    "documentation": "proposal | plan | original-order |reflex-order"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Individual the service is ordered for"
                                },
                                {
                                    "name": "status",
                                    "type": "string",
                                    "documentation": "entered-in-error | draft | active |suspended | completed"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "Individual the service is ordered for"
                                }
                            ]
                        },
                        {
                            "type": "DiagnosticReport",
                            "profile": "http://hl7.org/fhir/StructureDefinition/DiagnosticReport",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "code",
                                    "type": "token",
                                    "documentation": "The code for the report, as opposed to codes for the atomic results, which are the names on the observation resource referred to from the result"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "The subject of the report if a patient"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "The subject of the report"
                                }
                            ]
                        },
                        {
                            "type": "DocumentReference",
                            "profile": "http://hl7.org/fhir/StructureDefinition/DocumentReference",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Who/what is the subject of the document"
                                },
                                {
                                    "name": "type",
                                    "type": "token",
                                    "documentation": "Kind of document (LOINC if possible)"
                                }
                            ]
                        },
                        {
                            "type": "Encounter",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Encounter",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "The patient or group present at the encounter"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "The patient or group present at the encounter"
                                },
                                {
                                    "name": "type",
                                    "type": "token",
                                    "documentation": "Specific type of encounter"
                                }
                            ]
                        },
                        {
                            "type": "ImagingStudy",
                            "profile": "http://hl7.org/fhir/StructureDefinition/ImagingStudy",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Who the study is about"
                                },
                                {
                                    "name": "started",
                                    "type": "date",
                                    "documentation": "When the study was started"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "Who the study is about"
                                }
                            ]
                        },
                        {
                            "type": "Immunization",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Immunization",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "The patient for the vaccination record"
                                },
                                {
                                    "name": "vaccine-code",
                                    "type": "token",
                                    "documentation": "Vaccine Product Administered"
                                }
                            ]
                        },
                        {
                            "type": "Medication",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Medication",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "code",
                                    "type": "token",
                                    "documentation": "Returns medications for a specific code"
                                }
                            ]
                        },
                        {
                            "type": "MedicationAdministration",
                            "profile": "http://hl7.org/fhir/StructureDefinition/MedicationAdministration",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "code",
                                    "type": "token",
                                    "documentation": "Return administrations of this medication code"
                                },
                                {
                                    "name": "effective-time",
                                    "type": "date",
                                    "documentation": "Date administration happened (or did not happen)"
                                },
                                {
                                    "name": "medication",
                                    "type": "token",
                                    "documentation": "Return administrations of this medication resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "The identity of a patient to list administrations  for"
                                },
                                {
                                    "name": "status",
                                    "type": "string",
                                    "documentation": "MedicationAdministration event status (for example one of active/paused/completed/nullified)"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "The identity of the individual or group to list administrations for"
                                }
                            ]
                        },
                        {
                            "type": "Observation",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Observation",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "code",
                                    "type": "token",
                                    "documentation": "The code of the observation type"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "The subject that the observation is about (if patient)"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "The subject that the observation is about"
                                }
                            ]
                        },
                        {
                            "type": "OperationDefinition",
                            "profile": "http://hl7.org/fhir/StructureDefinition/OperationDefinition",
                            "interaction": [
                                {
                                    "code": "read"
                                }
                            ]
                        },
                        {
                            "type": "Organization",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Organization",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "active",
                                    "type": "token",
                                    "documentation": "Is the Organization record active"
                                },
                                {
                                    "name": "address-city",
                                    "type": "string",
                                    "documentation": "A city specified in an address"
                                },
                                {
                                    "name": "address-country",
                                    "type": "string",
                                    "documentation": "A country specified in an address"
                                },
                                {
                                    "name": "address-state",
                                    "type": "string",
                                    "documentation": "A state specified in an address"
                                },
                                {
                                    "name": "identifier",
                                    "type": "token",
                                    "documentation": "Any identifier for the organization (not the accreditation issuers identifier)"
                                },
                                {
                                    "name": "name",
                                    "type": "string",
                                    "documentation": "A portion of the organizations name or alias"
                                }
                            ]
                        },
                        {
                            "type": "Patient",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Patient",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "active",
                                    "type": "token",
                                    "documentation": "Whether the patient record is active"
                                },
                                {
                                    "name": "address-city",
                                    "type": "string",
                                    "documentation": "A city specified in an address"
                                },
                                {
                                    "name": "address-country",
                                    "type": "string",
                                    "documentation": "A country specified in an address"
                                },
                                {
                                    "name": "address-state",
                                    "type": "string",
                                    "documentation": "A state specified in an address"
                                },
                                {
                                    "name": "birthdate",
                                    "type": "date",
                                    "documentation": "The patients date of birth"
                                },
                                {
                                    "name": "deceased",
                                    "type": "string",
                                    "documentation": "This patient has been marked as deceased, or as a death date entered"
                                },
                                {
                                    "name": "gender",
                                    "type": "string",
                                    "documentation": "Gender of the patient"
                                },
                                {
                                    "name": "identifier",
                                    "type": "token",
                                    "documentation": "A patient identifier"
                                },
                                {
                                    "name": "link",
                                    "type": "reference",
                                    "documentation": "All patients linked to the given patient"
                                },
                                {
                                    "name": "name",
                                    "type": "string",
                                    "documentation": "A server defined search that may match any of the string fields in the HumanName, including family, give, prefix, suffix, suffix, and/or text"
                                }
                            ]
                        },
                        {
                            "type": "Practitioner",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Practitioner",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "active",
                                    "type": "token",
                                    "documentation": "Whether the practitioner record is active"
                                },
                                {
                                    "name": "address-city",
                                    "type": "string",
                                    "documentation": "A city specified in an address"
                                },
                                {
                                    "name": "address-country",
                                    "type": "string",
                                    "documentation": "A country specified in an address"
                                },
                                {
                                    "name": "address-state",
                                    "type": "string",
                                    "documentation": "A state specified in an address"
                                },
                                {
                                    "name": "gender",
                                    "type": "string",
                                    "documentation": "Gender of the practitioner"
                                },
                                {
                                    "name": "identifier",
                                    "type": "token",
                                    "documentation": "A practitioners Identifier"
                                },
                                {
                                    "name": "name",
                                    "type": "string",
                                    "documentation": "A server defined search that may match any of the string fields in the HumanName, including family, give, prefix, suffix, suffix, and/or text"
                                }
                            ]
                        },
                        {
                            "type": "Procedure",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Procedure",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "category",
                                    "type": "token",
                                    "documentation": "Classification of the procedure"
                                },
                                {
                                    "name": "code",
                                    "type": "token",
                                    "documentation": "A code to identify a  procedure"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Search by subject - a patient"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "Search by subject"
                                }
                            ]
                        },
                        {
                            "type": "QuestionnaireResponse",
                            "profile": "http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                }
                            ]
                        },
                        {
                            "type": "ServiceRequest",
                            "profile": "http://hl7.org/fhir/StructureDefinition/ServiceRequest",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "code",
                                    "type": "string",
                                    "documentation": "What is being requested/ordered"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Search by subject - a patient"
                                },
                                {
                                    "name": "status",
                                    "type": "string",
                                    "documentation": "draft | active | on-hold | revoked | completed | entered-in-error | unknown"
                                },
                                {
                                    "name": "subject",
                                    "type": "reference",
                                    "documentation": "Search by subject"
                                }
                            ]
                        },
                        {
                            "type": "StructureDefinition",
                            "profile": "http://hl7.org/fhir/StructureDefinition/StructureDefinition",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "search-type"
                                }
                            ]
                        },
                        {
                            "type": "Task",
                            "profile": "http://hl7.org/fhir/StructureDefinition/Task",
                            "interaction": [
                                {
                                    "code": "read"
                                },
                                {
                                    "code": "update"
                                },
                                {
                                    "code": "create"
                                },
                                {
                                    "code": "search-type"
                                }
                            ],
                            "searchParam": [
                                {
                                    "name": "_id",
                                    "type": "string",
                                    "documentation": "The ID of the resource"
                                },
                                {
                                    "name": "patient",
                                    "type": "string",
                                    "documentation": "Search by patient"
                                },
                                {
                                    "name": "status",
                                    "type": "string",
                                    "documentation": "Search by task status"
                                }
                            ]
                        }
                    ]
                    
                }
            ]
        }';
        return json_decode($searchResponseBody);
    }
}
