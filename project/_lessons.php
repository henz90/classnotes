<?php

require_once '_setup.php';

//WIP Populating dropdown with database is harder than i thought
//ADD LESSON
// STATE 1: first display
$app->get('/create_lesson', function ($request, $response, $args) {
    if (!isset($_SESSION['user'])) { // refuse if user not logged in
        $response = $response->withStatus(403);
        return $this->view->render($response, 'error_access_denied.html.twig');
    }
    return $this->view->render($response, 'create_lesson.html.twig');
});

// AJAX use, to populate year dropdown
/*
$app->get('/islessonyear', function ($request, $response, $args) {
    $record = DB::query("SELECT distinct year FROM classes order by year desc");
    $returnvalue = "";
    while ($row = mysqli_fetch_array($record)) {
        $returnvalue = $returnvalue . "<option value=\"$row['year'];></option>"
    }
}); 
*/