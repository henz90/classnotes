<?php 

require_once '_setup.php';

    //  ADD CLASS
// STATE 1: first display
$app->get('/create_class', function ($request, $response, $args) {
    if (!isset($_SESSION['user'])) { // refuse if user not logged in
        $response = $response->withStatus(403);
        return $this->view->render($response, 'error_access_denied.html.twig');
    }
    return $this->view->render($response, 'create_class.html.twig');
});

// STATE 2&3: receiving submission
$app->post('/create_class', function ($request, $response, $args) {
    if (!isset($_SESSION['user'])) { // refuse if user not logged in
        $response = $response->withStatus(403);
        return $this->view->render($response, 'error_access_denied.html.twig');
    }
    $name = $request->getParam('name');
    $body = $request->getParam('body');
    //  Sanitize the Body:
    $body = strip_tags($body, "<p><ul><li><em><strong><i><b><ol><h3><h4><h5><span>");
    $errorList = array();
    if (strlen($name) < 2 || strlen($name) > 100) {
        array_push($errorList, "Title must be 2-100 characters long");
        // keep the title even if invalid
    }
    if (strlen($body) < 2 || strlen($body) > 1000) {
        array_push($errorList, "Body must be 2-1000 characters long");
        // keep the body even if invalid
    }
    if ($errorList) {
        return $this->view->render($response, 'create_class.html.twig',
                [ 'errorList' => $errorList, 'c' => ['name' => $name, 'body' => $body ]  ]);
    } else {
        $authorId = $_SESSION['user']['userid'];
        DB::insert('classes', ['userid' => $authorId, 'name' => $name, 'body' => $body, 'level' => 0]);
        $articleId = DB::insertId();
        return $this->view->render($response, 'addarticle_success.html.twig', ['id' => $articleId]);
    }
});