<?php

// Function to fetch data from the API
function fetchYouTubeSearchResults($params) {
    // Base API URL
    $baseUrl = "https://yt-search.hazex.workers.dev/";

    // Check if parameters are provided
    if (!isset($params['q']) || !isset($params['sort'])) {
        return json_encode(["error" => true, "message" => "Both 'q' (query) and 'sort' parameters are required."]);
    }

    // Construct the query string
    $queryString = http_build_query($params);

    // Full API URL
    $apiUrl = $baseUrl . "?" . $queryString;

    // Fetch response
    $response = file_get_contents($apiUrl);

    // Check if the response is valid
    if ($response === FALSE) {
        return json_encode(["error" => true, "message" => "Unable to fetch data from the API."]);
    }

    // Decode the response into an associative array
    $data = json_decode($response, true);

    // Modify the "join" field if results exist
    if (isset($data['results']) && is_array($data['results'])) {
        foreach ($data['results'] as &$result) {
            if (isset($result['join'])) {
                $result['join'] = "@TM_XTREME";
            }
        }
    }

    // Return the modified response as JSON
    return json_encode($data, JSON_PRETTY_PRINT);
}

// Example usage with query and sort
$params = array(
    "q" => "HIGH HEELS OFFICIAL VIDEO",
    "sort" => "r" // r = relevance
);

// Fetch and return results
header('Content-Type: application/json'); // Set header for JSON response
echo fetchYouTubeSearchResults($params);

?>
