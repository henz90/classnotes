<?php

require_once '_setup.php';

//  Add Profile

    //  REGISTER
// STATE 1: first display
$app->get('/register', function ($request, $response, $args) {
    return $this->view->render($response, 'register.html.twig');
});

// STATE 2&3: receiving submission
$app->post('/register', function ($request, $response, $args) {
    $name = $request->getParam('username');
    $email = $request->getParam('email');
    $pass1 = $request->getParam('pass1');
    $pass2 = $request->getParam('pass2');
    //
    $errorList = array();
    if (preg_match('/^[a-zA-Z0-9\ \\._\'"-]{4,50}$/', $name) != 1) { // no match
        array_push($errorList, "Username must be 4-50 characters long and consist of letters, digits, "
            . "spaces, dots, underscores, apostrophies, or minus sign.");
        $name = "";
    } else {
        // is username already in use?
        $record = DB::queryFirstRow("SELECT * FROM users WHERE username=%s", $name);
        if ($record) {
            array_push($errorList, "This username is already registered");
            $name = "";
        }
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE) {
        array_push($errorList, "Email does not look valid");
        $email = "";
    } else {
        // is email already in use?
        $record = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $email);
        if ($record) {
            array_push($errorList, "This email is already registered");
            $email = "";
        }
    }
    if ($pass1 != $pass2) {
        array_push($errorList, "Passwords do not match");
    } else {
        if ((strlen($pass1) < 6) || (strlen($pass1) > 16)
                || (preg_match("/[A-Z]/", $pass1) == FALSE )
                || (preg_match("/[a-z]/", $pass1) == FALSE )
                || (preg_match("/[0-9]/", $pass1) == FALSE )) {
            array_push($errorList, "Password must be 6-16 characters long, "
                . "with at least one uppercase, one lowercase, and one digit in it");
        }
    }
    //
    if ($errorList) {
        return $this->view->render($response, 'register.html.twig',
                [ 'errorList' => $errorList, 'v' => ['username' => $name, 'email' => $email ]  ]);
    } else {
        DB::insert('users', ['username' => $name, 'email' => $email, 'password' => $pass1, 'level' => 0]);
        return $this->view->render($response, 'register_success.html.twig');
    }
});

// used via AJAX
$app->get('/isemailtaken/[{email}]', function ($request, $response, $args) {
    $email = isset($args['email']) ? $args['email'] : "";
    $record = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $email);
    if ($record) {
        return $response->write("Email already in use");
    } else {
        return $response->write("");
    }
});

// username AJAX
$app->get('/isusernametaken/[{username}]', function ($request, $response, $args) {
    $username = isset($args['username']) ? $args['username'] : "";
    $record = DB::queryFirstRow("SELECT * FROM users WHERE username=%s", $username);
    if ($record) {
        return $response->write("Username already in use");
    } else {
        return $response->write("");
    }
});

// debug function to output to console to help with testing
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

  //  LOGIN
// STATE 1: first display 

$app->get('/login', function ($request, $response, $args) {
    return $this->view->render($response, 'login.html.twig', ['userSession' => null]);
});

// STATE 2&3: receiving submission
$app->post('/login', function ($request, $response, $args)  use ($log){
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    //
    $record = DB::queryFirstRow("SELECT * FROM users WHERE username=%s", $username);
    $loginSuccess = false;
    if ($record) {
        if ($record['password'] == $password) {
            $loginSuccess = true;
        }        
    }
    //
    if (!$loginSuccess) {
        $log ->info(sprintf("Login failed for username %s from %s", $username, $_SERVER['REMOTE_ADDR']));
        return $this->view->render($response, 'login.html.twig', [ 'error' => true ]);
    } else {
        unset($record['password']); // for security reasons remove password from session
        $_SESSION['user'] = $record; // remember user logged in
        $log ->debug(sprintf("Login successful for username %s, uid=%d, from %s", $username, $record['userid'], $_SERVER['REMOTE_ADDR']));
        return $this->view->render($response, 'index.html.twig', ['userSession' => $_SESSION['user']]);
    }
});

    //  LOGOUT
// STATE 1: first display
$app->get('/logout', function ($request, $response, $args) use ($log){
    $log ->debug(sprintf("Logout for uid=%d from %s", @$_SESSION['user']['id'], $_SERVER['REMOTE_ADDR']));
    unset($_SESSION['user']);
    return $this->view->render($response, 'logout.html.twig', ['userSession' => null]);
});


    //  PROFILE
$app->map(['GET', 'POST'],'/profile/{id:[0-9]+}', function ($request, $response, $args) {
    // Fetch user, class, lessons, and comments
    $profile = DB::queryFirstRow("SELECT u.userid, u.username, u.email, u.bio, u.level, u.password FROM users as u WHERE u.userid = %d", $args['id']);
    $classList = DB::query("SELECT cl.classid, cl.classname, cl.userid, cl.level, cl.body FROM classes as cl WHERE cl.userid = %d ORDER BY cl.level LIMIT 5", $args['id']);
    $lessonList = DB::query("SELECT l.lessonid, l.title, l.body, l.classid, l.userid, l.filepathid, l.date, l.level FROM lessons as l WHERE l.userid = %d ORDER BY l.level LIMIT 5", $args['id']);
    $commentList = DB::query("SELECT co.commentid, co.articleid, co.userid, co.date, co.body FROM comments as co WHERE co.userid = %d, ORDER BY co.level LIMIT 5", $args['id']);
    foreach ($classList as &$article) {
        $fullBodyNoTags = strip_tags($article['body']);
        $bodyPreview = substr(strip_tags($fullBodyNoTags), 0, 10);
        $bodyPreview .= (strlen($fullBodyNoTags) > strlen($bodyPreview)) ? "..." : "";
        $article['body'] = $bodyPreview;
    }
    foreach ($commentList as &$article) {
        $fullBodyNoTags = strip_tags($article['body']);
        $bodyPreview = substr(strip_tags($fullBodyNoTags), 0, 10);
        $bodyPreview .= (strlen($fullBodyNoTags) > strlen($bodyPreview)) ? "..." : "";
        $article['body'] = $bodyPreview;
    }
    if (!$profile) {
        $response = $response->withStatus(404);
        return $this->view->render($response, 'profile_not_found.html.twig');
    }
        //  STATE 2&3: receiving submission
    if ($profile['userid'] == $_SESSION['user']['id']) {
        $body = $request->getParam('body');
        $body = strip_tags($body, "<p><ul><li><em><strong><i><b><ol><h3><h4><h5><span>");
        if ($profile['bio'] == NULL || $profile['bio'] != $body) {  //  FIXME: Needs work, doesn't update properly
            $errorList = array();
            if (strlen($body) < 2 || strlen($body) > 1000) {
                array_push($errorList, "Bio must be 2-1000 characters long");
                // keep the body even if invalid
            }
            if ($errorList) {
                return $this->view->render($response, 'profile.html.twig',
                        [ 'errorList' => $errorList, 'id' => $args['id']]);
            } else {
                DB::insertUpdate('users', ['userid' => $args['id'], 'bio' => $body]);
                return $this->view->render($response, 'profile.html.twig',
                        [ 'errorList' => $errorList, 'u' => $profile,'classes' => $classList, 'lessons' => $lessonList, 'comments' => $commentList]);
            }
        }
    }
    //  CHANGE PASSWORD
    $pass = $request->getParam('pass');
    $pass1 = $request->getParam('pass1');
    $pass2 = $request->getParam('pass2');
    if ($pass == $profile['password']) {
        if ($pass1 != $pass2) {
            array_push($errorList, "Passwords do not match");
        } else {
            if ((strlen($pass1) < 6) || (strlen($pass1) > 16)
                    || (preg_match("/[A-Z]/", $pass1) == FALSE )
                    || (preg_match("/[a-z]/", $pass1) == FALSE )
                    || (preg_match("/[0-9]/", $pass1) == FALSE )) {
                array_push($errorList, "Password must be 6-16 characters long, "
                    . "with at least one uppercase, one lowercase, and one digit in it");
            }
            else {
                DB::insertUpdate('users', ['userid' => $args['id'], 'password' => $pass1]);
            }
        }
    } else {
        array_push($errorList, "Current Password is incorrect");
    }
    if ($errorList) {
        return $this->view->render($response, 'profile.html.twig',
                [ 'errorList' => $errorList, 'u' => $profile,'classes' => $classList, 'lessons' => $lessonList, 'comments' => $commentList]);
    } else {
        return $this->view->render($response, 'profile.html.twig', ['u' => $profile, 'classes' => $classList, 'lessons' => $lessonList, 'comments' => $commentList]);
    }
    //  Return
    return $this->view->render($response, 'profile.html.twig', ['u' => $profile, 'classes' => $classList, 'lessons' => $lessonList, 'comments' => $commentList]);
});  