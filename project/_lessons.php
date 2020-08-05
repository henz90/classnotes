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
    if (strlen($body) < 2 || strlen($body) > 10000) {
        array_push($errorList, "Body must be 2-10000 characters long");
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

        $fullBodyNoTags = strip_tags($lesson['body']);
        $bodyPreview = substr(strip_tags($fullBodyNoTags), 0, 100);
        $bodyPreview .= (strlen($fullBodyNoTags) > strlen($bodyPreview)) ? "..." : "";
        $lesson['body'] = $bodyPreview;
        return $this->view->render($response, 'addlesson_success.html.twig', ['l' => $lesson]);
    }
});

$app->get('/lesson/{lessonid:[0-9]+}', function ($request, $response, $args) {

    if (!isset($_SESSION['user'])) { // refuse if user not logged in
        $response = $response->withStatus(403);
        return $this->view->render($response, 'error_access_denied.html.twig');
    }

    $lessonid = isset($args['lessonid']) ? $args['lessonid'] : "";

    if (!$lessonid) { 
        //FIXME currently using article_not_found. Fix it later.
        $response = $response->withStatus(404);
        return $this->view->render($response, 'article_not_found.html.twig'); 
    }

    $lesson = DB::queryFirstRow("SELECT lessonid, title, body, date FROM lessons WHERE lessonid=%d", $lessonid);
    //user id is from class and not lesson. If lesson create can be different from class then 
    $class = DB::queryFirstRow("SELECT c.classid, c.classname, c.userid, a.username FROM classes as c, lessons as l, users as a WHERE a.userid = c.userid AND "
    . "c.classid = l.classid AND lessonid =%d", $lessonid);

    return $this->view->render($response, 'lesson.html.twig', ['l' => $lesson, 'c' => $class]);
});