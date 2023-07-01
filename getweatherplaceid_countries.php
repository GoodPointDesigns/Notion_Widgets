<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="country_output.csv"');

$countryData = file_get_contents('https://restcountries.com/v3.1/all');
$countries = json_decode($countryData, true);

$csvContent = "Country,Output\n";

foreach ($countries as $country) {
    $countryName = $country['name']['common'];
    $countryAPIUrl = 'https://forecast7.com/api/autocomplete/' . urlencode($countryName);

    $response = file_get_contents($countryAPIUrl);
    $responseJson = json_decode($response, true);

    if (!empty($responseJson) && isset($responseJson[0]['place_id'])) {
        $placeId = $responseJson[0]['place_id'];
        $getURL = 'https://forecast7.com/api/getUrl/' . urlencode($placeId);

        $output = file_get_contents($getURL);
        $csvContent .= "$countryName,$output\n";
    } else {
        $csvContent .= "$countryName,N/A\n";
    }
}

$file = fopen('php://output', 'w');
fwrite($file, $csvContent);
fclose($file);
?>
