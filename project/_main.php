<?php

require_once '_setup.php';

    //  INTERNAL ERROR
$app->get('/internalerror', function ($request, $response, $args) {
    return $this->view->render($response, 'error_internal.html.twig');
});

    //  MAINPAGE
$app->get('/', function ($request, $response, $args) {
    return $response->write("This is Index");
    /*  //  FIX ME
    $articleList = DB::query("SELECT a.id, a.authorId, a.creationTS, a.title, a.body, a.photofilepath, u.name "
        . "FROM articles as a, users as u WHERE a.authorId = u.id ORDER BY a.id DESC");
    foreach ($articleList as &$article) {
        // format posted date
        $datetime = strtotime($article['creationTS']);
        $postedDate = date('M d, Y \a\t H:i:s', $datetime );
        $article['postedDate'] = $postedDate;
        // only show the beginning of body if it's long, also remove html tags
        $fullBodyNoTags = strip_tags($article['body']);
        $bodyPreview = substr(strip_tags($fullBodyNoTags), 0, 100); // FIXME
        $bodyPreview .= (strlen($fullBodyNoTags) > strlen($bodyPreview)) ? "..." : "";
        $article['body'] = $bodyPreview;
    }
    return $this->view->render($response, 'index.html.twig', ['list' => $articleList]);
    */
});

    //  SESSION
$app->get('/session', function ($request, $response, $args) {
    echo "<pre>\n";
    print_r($_SESSION);
    return $response->write("");
});