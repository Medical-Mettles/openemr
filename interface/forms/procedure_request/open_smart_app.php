<?php
require_once(__DIR__ . "/../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");

use OpenEMR\RestControllers\AuthRestController;
use OpenEMR\Services\PatientService;

error_log("in Open Smart app---");
foreach($_POST as $key => $value) {
    error_log("The key is ".$key);
    error_log("The value is ".$value);
}
$smart_url = "https://od.mettles.com/index?iss=http://localhost/openemr/apis/fhir";
$patientService = new PatientService();
$patient_fhirid = $patientService->getUUID($_SESSION['pid']);
// $fhir_session = (new AuthRestController())->generate_session_token(0, $_SESSION['authUserID'], "Default", $_SESSION['authUser'], "api", $_SESSION['authUserID'],'fhir');
// echo "<br>Access token----".$fhir_session['access_token']."--------";
//$smart_url = $smart_url."&bearer_token=".$fhir_session['access_token'];
$smart_url = $smart_url."&bearer_token=1234567891";
if($patient_fhirid){
    $smart_url = $smart_url."&patientId=".$patient_fhirid;
}
if($_POST['app_context']){
    $smart_url = $smart_url."&".$_POST['app_context'];
}
formJump($smart_url);
?>