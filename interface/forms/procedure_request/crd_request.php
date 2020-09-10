<?php
require_once(__DIR__ . "/../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once($GLOBALS['srcdir'] . '/csv_like_join.php');
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');

use OpenEMR\RestControllers\AuthRestController;
use OpenEMR\Services\PatientService;
use OpenEMR\Services\BaseService;

error_log("in djdjd");
foreach($_POST as $key => $value) {
    error_log("The key is ".$key);
    error_log("The value is ".$value);
}
$response = new stdClass();
if($_POST["formDir"] == "procedure_order"){
    $encounterId = $_POST['encounterId'];
    $formId = $_POST['encounterId'];

    $patientService = new PatientService();
    $patient_uuid = $patientService->getUUID($_SESSION['pid']);
    
    $baseService = new BaseService("form_encounter");
    $encounter_uuid = $baseService->getUuidById($encounterId, "form_encounter","id");
   
    error_log("<br>UUid----".$patient_uuid."---authuserid--".$_SESSION['authUserID']."---encounterid--".$encounter_uuid);
    $fhir_session = (new AuthRestController())->generate_session_token(0, $_SESSION['authUserID'], "Default", $_SESSION['authUser'], "api", $_SESSION['authUserID'],'fhir');
    error_log(print_r( $fhir_session, true ));
    
    $servicerequest = generateServiceRequest($formId, $encounter_uuid, $patient_uuid);

    $crdRequest = new stdClass();
    $crdRequest = (object) array(
        "hook"=> "order-sign",
        "hookInstance"=>"d1577c69-dfbe-44ad-ba6d-3e05e953b2ea",
        "fhirServer"=> $GLOBALS['fhir_url'],
        "fhirAuthorization"=> (object) array(
          "access_token"=> $fhir_session['access_token'],
          "token_type"=> "Bearer",
          "expires_in"=> 300,
          "scope"=> "patient/Patient.read patient/Coverage.read patient/Encounter.read patient/Condition.read",
          "subject"=> "cds-service"
        ),
        "context"=> (object) array(
          "patientId"=> $patient_uuid,
          "encounterId"=> $encounter_uuid,
          "userId"=> "Practitioner/practitioner-1",//TODO
          "draftOrders"=> (object) array(
            "resourceType"=> "Bundle",
            "entry"=> array( (object) array(
                "resource"=> $servicerequest
                )
            )
        )
        )
    );
    $cardResponse = postCRDRequest($crdRequest);

    if($cardResponse){
        $response->appContext = "template=questionnaire-unitedhealthcare-stressechocardiography&request=276&priorauth=true&filepath=_";
        //Update prior auth context
        $update_query = "UPDATE procedure_order JOIN forms ON forms.form_id = procedure_order.procedure_order_id 
        SET prior_auth= ?,prior_auth_appcontext = ? 
        WHERE procedure_order.procedure_order_id = ?";
        error_log($update_query);
        sqlStatement($update_query, array(1, $response->appContext, $_POST['formId']));
        
        $response->responseHtml = "<form method='post' name='my_form' action='".$GLOBALS['rootdir']."/forms/procedure_request/open_smart_app.php'> 
        <input type='hidden' name='app_context' value='".$response->appContext."'/> Prior Authorization is needed. 
        <input type='submit' class='btn btn-primary' value='Click here' />to start Prior Authorization process.</form>";
    }
    
} 
$response->status = "success";
echo json_encode($response);
exit;

function postCRDRequest($crdRequest){
    $curl = curl_init($GLOBALS['order_sign_url']);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $payload = json_encode($crdRequest);
    error_log(print_r($crdRequest, true ));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // do the request. If FALSE, then an exception occurred
    if (false === ($result = curl_exec($curl))) {
        throw (new Exception(
            "Curl failed with error " . curl_error($curl)
        ));
    }
    error_log( "<br>CRD Response:<br>");
    error_log(print_r($result));
    $cardResponse = json_decode($result, true);
    error_log(print_r($cardResponse));
    // get result code
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    error_log($responseCode);
    return $cardResponse;
}
function generateServiceRequest($formId, $encounter_uuid, $patient_uuid){
    //TODO : coverage and requester are to be added
    $servicerequest = new stdClass();
    $servicerequest->resourceType = "Servicerequest";
    $servicerequest->id = rand();
    $identifier = (object) array(
        "system" => "http://identifiers.mettles.com/prior_authorization",
        "value"=> rand()
    );
    $servicerequest->identifier = $identifier;
    $servicerequest->status = "draft";
    $servicerequest->intent = "order";
    $servicerequest->subject = (object) array(
      "reference"=> "Patient/".$patient_uuid
    );
    $servicerequest->authoredOn = "2020-09-09T07:07:21Z";
    $servicerequest->encounter = (object) array(
        "reference"=> "Encounter/".$encounter_uuid
    );
    return $servicerequest;
}
