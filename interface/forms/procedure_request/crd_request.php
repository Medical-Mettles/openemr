<?php
require_once(__DIR__ . "/../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once($GLOBALS['srcdir'] . '/csv_like_join.php');
require_once($GLOBALS['fileroot'] . '/custom/code_types.inc.php');

use OpenEMR\RestControllers\AuthRestController;
use OpenEMR\Services\PatientService;

error_log("in djdjd");
foreach($_POST as $key => $value) {
    error_log("The key is ".$key);
    error_log("The value is ".$value);
}
$response = new stdClass();
if($_POST["formDir"] == "procedure_order"){
    $encounterId = $_POST['encounterId'];
    $patientService = new PatientService();
    $patient_uuid = $patientService->getUUID($_SESSION['pid']);
    error_log("<br>UUid----".$patient_uuid."---authuserid--".$_SESSION['authUserID']);
    // $fhir_session = (new AuthRestController())->generate_session_token(0, $_SESSION['authUserID'], "Default", $_SESSION['authUser'], "api", $_SESSION['authUserID'],'fhir');
    // error_log(print_r( $fhir_session, true ));
    $response->appUrl = 'https://cf.mettles.com/index?iss=http://localhost/openemr/apis/fhir&patientId='.$patient_uuid;
    $response->responseHtml = "Prior Authorization is needed. <input type='button' class='btn btn-primary' value='Click here' onClick='openSmartApp('".strval($response->appUrl)."')'/> to start Prior Authorization process. ";
} 

$response->status = "success";
echo json_encode($response);
exit;
?>