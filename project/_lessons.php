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

// consult PHP slim documentation
// Handling the redirect
$app->get('/create_lesson/[{classid}]', function ($request, $response, $args) {
    $classid = isset($args['classid']) ? $args['classid'] : "";
    $id = DB::queryFirstRow("SELECT classid FROM classes WHERE classid=%s", $classid);
    return $this->view->render($response, 'create_lesson.html.twig', ['c' => $id]);
});

$app->post('/create_lesson', function ($request, $response, $args) {
    if (!isset($_SESSION['user'])) { // refuse if user not logged in
        $response = $response->withStatus(403);
        return $this->view->render($response, 'error_access_denied.html.twig');
    }
    $classid = $request->getParam('classinputid');
    $title = $request->getParam('title');
    $body = $request->getParam('body');
    //  Sanitize the Body:
    $body = strip_tags($body, "<p><ul><li><em><strong><i><b><ol><h3><h4><h5><span>");
    $errorList = array();
    if (preg_match('/^[a-zA-Z0-9\ \\._\'"-]{2,100}$/', $title) != 1) { // Reg check on classname
        array_push($errorList, "Title must be 2-100 characters long and consist of letters, digits, "
            . "spaces, dots, underscores, apostrophies, or minus sign.");
        // keep the title even if invalid
    }
    if (strlen($body) < 2 || strlen($body) > 1000) {
        array_push($errorList, "Body must be 2-1000 characters long");
        // keep the body even if invalid
    }
    
    if ($errorList) {
        return $this->view->render($response, 'create_lesson.html.twig',
                [ 'errorList' => $errorList, 'c' => ['title' => $title, 'body' => $body ]  ]);
    } else {
        $authorId = $_SESSION['user']['userid'];
        DB::insert('lessons', ['title' => $title, 'body' => $body, 'classid' => $classid, 'userid' => $authorId, 'level' => 0]);
        $lessonId = DB::insertId();
        $lesson = DB::queryFirstRow("SELECT l.lessonid, l.title, l.body "
        . "FROM lessons as l WHERE l.lessonid = %d", $lessonId);
        return $this->view->render($response, 'addlesson_success.html.twig', ['l' => $lesson]);
    }
});