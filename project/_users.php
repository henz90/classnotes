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
        return $this->view->render($response, 'login_success.html.twig', ['userSession' => $_SESSION['user']]); //  FIXME: Move to profile page once completed
    }
});

    //  LOGOUT
// STATE 1: first display
$app->get('/logout', function ($request, $response, $args) use ($log){
    $log ->debug(sprintf("Logout for uid=%d from %s", @$_SESSION['user']['id'], $_SERVER['REMOTE_ADDR']));
    unset($_SESSION['user']);
    return $this->view->render($response, 'logout.html.twig', ['userSession' => null]);
});
