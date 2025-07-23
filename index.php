<?php

function fetchDataWithCurl($url)
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        curl_close($ch);
        return [];
    }

    curl_close($ch);
    return json_decode($response, true);
}

$postsUrl = "https://jsonplaceholder.typicode.com/posts";
$commentsUrl = "https://jsonplaceholder.typicode.com/comments";

$posts = fetchDataWithCurl($postsUrl);
$comments = fetchDataWithCurl($commentsUrl);

$postCommentsMap = [];

foreach ($comments as $comment) {
    $postId = $comment['postId'];
    $postCommentsMap[$postId][] = $comment;
}

$postsWithComments = array_filter($posts, function ($post) use ($postCommentsMap) {
    return isset($postCommentsMap[$post['id']]);
});

header('Content-Type: application/json');
echo json_encode(array_values($postsWithComments), JSON_PRETTY_PRINT);
