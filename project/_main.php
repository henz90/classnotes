<?php

require_once '_setup.php';

    //  INTERNAL ERROR
$app->get('/internalerror', function ($request, $response, $args) {
    return $this->view->render($response, 'error_internal.html.twig');
});

    //  MAINPAGE
$app->get('/', function ($request, $response, $args) {
    //return $response->write("This is Index");
    $classesList = DB::query("SELECT c.classid, c.name, c.semester, c.year, c.userid, c.level"
        . "FROM classes as c, users as u WHERE c.userid = u.userid ORDER BY c.classid DESC");
    foreach ($classesList as &$article) {
        // format posted date
        $datetime = strtotime($article['creationTS']);
        $postedDate = date('M d, Y \a\t H:i:s', $datetime );
        $article['postedDate'] = $postedDate;
    }
    return $this->view->render($response, 'index.html.twig', ['list' => $classesList]);
});

    //  SESSION
$app->get('/session', function ($request, $response, $args) {
    echo "<pre>\n";
    print_r($_SESSION);
    return $response->write("");
});