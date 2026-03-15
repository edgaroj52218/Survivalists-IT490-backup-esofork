<?php

require_once ('dev_api/dev_tidalAPI.php');

// test for data retrieval from API
$input = readline("Enter an artist, album, or track to search: ");

$result = search($input);

print_r($result);

?>
