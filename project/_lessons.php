<?php

require_once '_setup.php';

//ADD LESSON
// STATE 1: first display
$app->get('/create_lesson', function ($request, $response, $args) {
    if (!isset($_SESSION['user'])) { // refuse if user not logged in
        $response = $response->withStatus(403);
        return $this->view->render($response, 'error_access_denied.html.twig');
    }
    return $this->view->render($response, 'create_lesson.html.twig');
});

//WIP
// creating lesson AJAX
$app->get('/cancreatelesson/[{classname}]', function ($request, $response, $args) {
    $classname = isset($args['classname']) ? $args['classname'] : "";
    $id = DB::queryFirstRow("SELECT classid FROM classes WHERE classname=%s", $classname);
    return $this->view->render($response, 'create_lesson.html.twig', ['c' => $id]);
});