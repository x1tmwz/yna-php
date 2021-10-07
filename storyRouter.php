<?php
include_once './storyModel.php';
$action = $_GET["action"];
$method = $_SERVER['REQUEST_METHOD'];
$jsonData = file_get_contents('php://input');
$phpArray = json_decode($jsonData, true);


$result = createTable();
if ($result == 'error') {
    return sendRespone(500, 'Something went wrong i create table', null);
}


if ($action == "recent") {
    echo getRecentStories();
} elseif ($action == "all") {
    echo getAllStories();
} elseif ($action == "getId") {
    echo getStory($phpArray["id"]);
} elseif ($action == "create") {
    echo createNewStory($phpArray['title'], $phpArray[$author], $phpArray['content']);
} elseif ($action == "update") {
    echo update($phpArray['title'], $phpArray[$author], $phpArray['content'], $phpArray['id']);
} elseif ($action == "delete") {
    echo deleteStory($phpArray['id']);
}


function getRecentStories()
{
    $stories = selectRecentStories();
    if ($stories == 'error') {
        return sendRespone(500, 'Something whet wrong', null);
    }
    return sendRespone(200, '', $stories);
}
function getAllStories()
{
    $stories = selectRecentStories();
    if ($stories == 'error') {
        return sendRespone(500, 'Something whet wrong', null);
    }
    return sendRespone(200, '', $stories);
}
function getStory($id)
{
    if (!validateStoryId($id)) {
        return sendRespone(400, 'you need to provide story id', null);
    }
    $story = selectStoryById($id);
    if ($story == 'error') {
        return sendRespone(500, 'Something whet wrong', null);
    }
    return sendRespone(200, '', $story);
}
function createNewStory($title, $author, $content)
{
    if (!validateNewStoryData($title, $author, $content)) {
        return sendRespone(400, 'you need to provide title author and content', null);
    }
    $result = insert($title, $author, $content);
    if ($result == 'error') {
        return sendRespone(500, 'Something whet wrong', null);
    }
    return sendRespone(200, '', null);
}
function updateStory($title, $author, $content, $id)
{
    if (!validateStoryId($id)) {
        return sendRespone(400, 'you need to provide story id', null);
    }
    if (!validateNewStoryData($title, $author, $content)) {
        return sendRespone(400, 'you need to provide title author and content', null);
    }
    $result = update($title, $author, $content, $id);
    if ($result == 'error') {
        return sendRespone(500, 'Something whet wrong', null);
    }
    return sendRespone(200, '', null);
}
function deleteStory($id)
{
    if (!validateStoryId($id)) {
        return sendRespone(400, 'you need to provide story id', null);
    }
    $result = delete($id);
    if ($result == 'error') {
        return sendRespone(500, 'Something whet wrong', null);
    }
    return sendRespone(200, '', null);
}



function validateNewStoryData($title, $author, $content)
{
    if (!isset($title) || !isset($author) || !isset($content)) {
        return false;
    }
    return true;
}
function validateStoryId($id)
{
    if (!isset($id)) {
        return false;
    }
    return true;
}
function sendRespone($status, $message, $data)
{
    return json_encode(array(
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ));
}
