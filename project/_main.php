<?php

require_once '_setup.php';

    //  MAINPAGE
$app->get('/', function ($request, $response, $args) {
        //return $response->write("This is Index");
        $classesList = DB::query("SELECT c.classid, c.name, c.semester, c.year, c.userid, c.level, c.description"
            . "FROM classes as c, users as u WHERE c.userid = u.userid ORDER BY c.classid DESC");
        return $this->view->render($response, 'index.html.twig', ['list' => $classesList]);
});

    //  INTERNAL ERROR
$app->get('/internalerror', function ($request, $response, $args) {
    return $this->view->render($response, 'error_internal.html.twig');
});

    //  SESSION
$app->get('/session', function ($request, $response, $args) {
    echo "<pre>\n";
    print_r($_SESSION);
    return $response->write("");
});