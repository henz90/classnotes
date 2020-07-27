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
    if (preg_match('/^[a-zA-Z0-9\ \\._\'"-]{2,100}$/', $name) != 1) { // Reg check on classname
        array_push($errorList, "Title must be 2-100 characters long and consist of letters, digits, "
            . "spaces, dots, underscores, apostrophies, or minus sign.");
        // keep the title even if invalid
    }
    if (strlen($body) < 2 || strlen($body) > 1000) {
        array_push($errorList, "Body must be 2-1000 characters long");
        // keep the body even if invalid
    }
    if ($errorList) {
        return $this->view->render($response, 'create_class.html.twig',
                [ 'errorList' => $errorList, 'c' => ['classname' => $name, 'body' => $body ]  ]);
    } else {
        $authorId = $_SESSION['user']['userid'];
        DB::insert('classes', ['classname' => $name, 'semester' => $semester, 'year' => $year, 'userid' => $authorId, 'level' => 0, 'body' => $body]);
        $articleId = DB::insertId();
        return $this->view->render($response, 'addclass_success.html.twig', ['id' => $articleId]);
    }
});

    //  VIEW CLASS //   FIXME: Needs work!
$app->map(['GET', 'POST'],'/article/{id:[0-9]+}', function ($request, $response, $args) {
    // step 1: fetch article and author info
    $article = DB::queryFirstRow("SELECT a.classid, a.classname, a.semester, a.year, a.userid, a.level, a.body, u.name "
            . "FROM classes as a, users as u WHERE a.userid = u.userid AND a.id = %d", $args['id']);
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
        $authorId = $_SESSION['user']['id'];
        $body = $request->getParam('body');
        // TODO: we could check other things, like banned words
        if (strlen($body) > 0) {
            DB::insert('comments', [
                //FIXME: Need to implement comments into here
            ]);
        }
    }
    // step 3: fetch article comments
        // FIXME: Correct the inputs
    $commentsList = DB::query("SELECT c.id, u.name as authorName, c.creationTime, c.body FROM comments c, users u WHERE c.authorId=u.id ORDER BY c.id");    
    foreach ($commentsList as &$comment) {
        $datetime = strtotime($comment['creationTime']);
        $postedDate = date('M d, Y \a\t H:i:s', $datetime );
        $comment['postedDate'] = $postedDate;
    }
    //
    return $this->view->render($response, 'class.html.twig', ['a' => $article, 'commentsList' => $commentsList]);
});
