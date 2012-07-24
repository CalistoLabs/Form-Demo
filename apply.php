<?php

if (isset($_POST['Submit'])) {

    $errors = null;

    if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
        $_POST['first_name'] = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
        $_POST['last_name'] = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    } else {
        $errors .= 'Please enter your name.<br/>';
    }

    if ($_POST['email'] != "") {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors .= "$email is <strong>NOT</strong> a valid email address.<br/><br/>";
        }
    } else {
        $errors .= 'Please enter your email address.<br/>';
    }

    if (!$errors) {
        $mail_to = 'jesal@calistolabs.com';
        $subject = 'New Career Form Submission';
        $message  = 'From: ' . $_POST['name'] . "\n";
        $message .= 'Email: ' . $_POST['email'] . "\n";
        mail($to, $subject, $message);

        echo "Thank you for your email!<br/><br/>";
    } else {
        echo '<div style="color: red">' . $errors . '<br/></div>';
    }
}

?>
