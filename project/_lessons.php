<?php

require_once '_setup.php';

//WIP Populating dropdown with database is harder than i thought
//ADD LESSON
// STATE 1: first display
$app->get('/create_lesson', function ($request, $response, $args) {
    if (!isset($_SESSION['user'])) { // refuse if user not logged in
        $response = $response->withStatus(403);
        return $this->view->render($response, 'error_access_denied.html.twig');
    }
    return $this->view->render($response, 'create_lesson.html.twig');
});

// creating lesson AJAX
//FIXME is this even being called?
$app->get('/cancreatelesson/[{classname}]', function ($request, $response, $args) {
    $classname = isset($args['classname']) ? $args['classname'] : "";
    $id = DB::queryFirstRow("SELECT FIRST(classid) FROM classes WHERE classname=%s", $classname);
    $_SESSION['classid'] = $id;
});