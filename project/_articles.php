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
    $name = $request->getParam('classname');
    $body = $request->getParam('body');
    $semester = $request->getParam('semester');
    $year = $request->getParam('year');
    //  Sanitize the Body:
    $body = strip_tags($body, "<p><ul><li><em><strong><i><b><ol><h3><h4><h5><span>");
    $errorList = array();
    if (preg_match('/^[a-zA-Z0-9\ \\,\\?\\!\\._\'"-]{2,100}$/', $name) != 1) { // Reg check on classname
        array_push($errorList, "Class name must be 2-100 characters long and consist of letters, digits, "
            . "spaces, dots, question marks, exclamation points, commas, underscores, apostrophies, or minus sign.");
        // keep the title even if invalid
    }
    if (strlen($body) < 2 || strlen($body) > 1000) {
        array_push($errorList, "Body must be 2-1000 characters long");
        // keep the body even if invalid
    }
    if($year < 1989 ) {
        array_push($errorList, "Year must be a positive number before 1990");
    }
    if ($errorList) {
        return $this->view->render($response, 'create_class.html.twig',
                [ 'errorList' => $errorList, 'c' => ['classname' => $name, 'body' => $body ]  ]);
    } else {
        //  FIXME: Something isn't working here, redirects to create_class instead...
        $authorId = $_SESSION['user']['userid'];
        DB::insert('classes', ['classname' => $name, 'semester' => $semester, 'year' => $year, 'userid' => $authorId, 'level' => 0, 'body' => $body]);
        $articleId = DB::insertId();
        return $this->view->render($response, 'addclass_success.html.twig', ['class' => $articleId]);
    }
});

    //  VIEW CLASS
$app->map(['GET', 'POST'],'/class/{id:[0-9]+}', function ($request, $response, $args) {
    // step 1: fetch article and author info
    $article = DB::queryFirstRow("SELECT cl.classid, cl.classname, cl.semester, cl.year, cl.userid, cl.level, cl.body, u.username "
            . "FROM classes as cl, users as u WHERE cl.userid = u.userid AND cl.classid = %d", $args['id']);
    
    if (!$article) { // TODO: use Slim's default 404 page instead of our custom one
        $response = $response->withStatus(404);
        return $this->view->render($response, 'article_not_found.html.twig'); 
    }
    // step 2: handle comment submission if there is one
    if ($request->getMethod() == "POST" ) {
        // is user authenticated?
        if (!isset($_SESSION['user'])) { // refuse if user not logged in
            $response = $response->withStatus(403);
            return $this->view->render($response, 'error_access_denied.html.twig');
        }
        $authorId = $_SESSION['user']['userid'];
        $body = $request->getParam('body');
        // TODO: we could check other things, like banned words
        if (strlen($body) > 0) {
            DB::insert('comments', [
                'articleid' => $args['id'],
                'userid' => $authorId,
                'body' => $body,
                'level' => 0
            ]);
        }
    }
    // step 3: fetch article comments
    $commentsList = DB::query("SELECT co.commentid, u.username, co.date, co.body FROM comments as co, users as u WHERE co.userid = u.userid AND co.articleid = %d ORDER BY co.commentid", $args['id']);
    foreach ($commentsList as &$comment) {
        $datetime = strtotime($comment['date']);
        $postedDate = date('M d, Y \a\t H:i:s', $datetime );    //  FIXME: Time shows as 00:00:00
        $comment['date'] = $postedDate;
    }
    // add filepathid to query once it's implemented
    // add level to query once it's implemented
    $lessonList = DB::query("SELECT l.lessonid, l.title, l.body, l.userid FROM lessons as l WHERE l.classid = %d", $args['id']);
    //
    return $this->view->render($response, 'class.html.twig', ['a' => $article, 'commentsList' => $commentsList, 'lessonList' => $lessonList]);
});

    // EDIT CLASS
$app->map(['GET', 'POST'],'/edit_class/{id:[0-9]+}', function ($request, $response, $args) {
    // step 1: fetch article and author info
    $article = DB::queryFirstRow("SELECT cl.classid, cl.classname, cl.semester, cl.year, cl.userid, cl.level, cl.body, u.username "
            . "FROM classes as cl, users as u WHERE cl.userid = u.userid AND cl.classid = %d", $args['id']);
    
    if($article['userid'] != $_SESSION['user']['userid']){
        return $this->view->render($response, 'article_not_found.html.twig');
    }

    if (!$article) { // TODO: use Slim's default 404 page instead of our custom one
        $response = $response->withStatus(404);
        return $this->view->render($response, 'article_not_found.html.twig');
    }
    
    // step 2: fetch article comments
    $commentsList = DB::query("SELECT co.commentid, u.username, co.date, co.body FROM comments as co, users as u WHERE co.userid = u.userid AND co.articleid = %d ORDER BY co.commentid", $args['id']);
    foreach ($commentsList as &$comment) {
        $datetime = strtotime($comment['date']);
        $postedDate = date('M d, Y \a\t H:i:s', $datetime );
        $comment['postedDate'] = $postedDate;
    }
    // step 3: handle comment submission if there is one
    if ($request->getMethod() == "POST" ) {
        // is user authenticated?
        if (!isset($_SESSION['user'])) { // refuse if user not logged in
            $response = $response->withStatus(403);
            return $this->view->render($response, 'error_access_denied.html.twig');
        }
        $body = $request->getParam('body');
        // TODO: we could check other things, like banned words
        if (strlen($body) > 0) {
            DB::update('classes',
                ['body' => $body],
                "classid=%d", $args['id']
            );
            //  FIXME:  Updates but doesn't return page with edited information
            return $this->view->render($response, 'class.html.twig', ['a' => $article, 'commentsList' => $commentsList]);
        }
    }
    return $this->view->render($response, 'edit_class.html.twig', ['a' => $article, 'commentsList' => $commentsList]);
});

    //  DELETE CLASS
$app->map(['GET', 'POST'],'/delete_class/{id:[0-9]+}', function ($request, $response, $args) {
    // step 1: fetch article and author info
    $article = DB::queryFirstRow("SELECT cl.classid, cl.classname, cl.semester, cl.year, cl.userid, cl.level, cl.body, u.username "
            . "FROM classes as cl, users as u WHERE cl.userid = u.userid AND cl.classid = %d", $args['id']);

    if (!$article) { // TODO: use Slim's default 404 page instead of our custom one
        $response = $response->withStatus(404);
        return $this->view->render($response, 'article_not_found.html.twig'); 
    }
    if ($request->getMethod() == "POST" ) {
        DB::delete('classes',
                "classid=%d", $args['id']
            );
            return $this->view->render($response, 'class_deleted.html.twig');
    }
    //
    return $this->view->render($response, 'delete_class.html.twig', ['a' => $article]);
});