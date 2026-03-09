<?php

function tokenForUser() {

    $clientID = "TNi2hY6txPCXnDAA";
    $secretID = "xYgoYGl4KVYvvPpGJ8Fb4Ljucjvv8KzTfmyRBLrj52A=";

    $authCredentials = base64_encode($clientID . ":" . $secretID);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://auth.tidal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $authCredentials", "Content-Type: application/x-www-form-urlencoded"));
 
    $response = curl_exec($ch);
    curl_close($ch);

    $decodedResponse = json_decode($response, true);
    print_r($decodedResponse);

    return $decodedResponse['access_token'];

}
function searchArtist($artistName, $countryCode = "US") {

    $tokenTidal = tokenForUser();

   // $url = "https://openapi.tidal.com/v2/searchResults/" . urlencode($artistName) . "?countryCode" . $countryCode . "&explicitFilter=INCLUDES&include=albums";

    $url = "https://openapi.tidal.com/v2/searchResults/".urlencode($artistName)."?&countryCode=US&include=albums&include=tracks";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $tokenTidal", "Accept: application/vnd.api+json", "Content-Type: application/vnd.api+json"));

    $response = curl_exec($ch);
	
curl_close($ch);

    if ($response === false) {
        error_log('cURL Error: ' . curl_error($ch));
        exit('Sorry! An error occurred.');

    }

    return $response;
}

?>
