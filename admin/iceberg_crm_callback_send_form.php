<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
require_once '../themes/remote_params.php';

function iceberg_crm_callback_send_callback_request($data) {
    global $wpdb;
    // Set the endpoint URL for sending the request
    $url = ICEBERG_CRM_CALLBACK_HOST.':'.ICEBERG_CRM_CALLBACK_PORT.'/sendForm';

    // Get the token stored in the database
    $table_name = $wpdb->prefix . "iceberg_crm_callback_tokens";
    $token = $wpdb->get_var("SELECT token FROM $table_name");

    // // Prepare the data to be sent to the server
    $request_data = (array(
        "token" => $token,
        "data" => $data
    ));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
$_POST['identifier'] = parse_url(home_url())['host'];
echo iceberg_crm_callback_send_callback_request($_POST);
?>
