<?php

if (isset($_POST['submit'])) {

    if ($_POST['name'] != "") {
        $_POST['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        if ($_POST['name'] == "") {
            $errors .= 'Please enter a valid name.<br/><br/>';
        }
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

    if ($_POST['homepage'] != "") {
        $homepage = filter_var($_POST['homepage'], FILTER_SANITIZE_URL);
        if (!filter_var($homepage, FILTER_VALIDATE_URL)) {
            $errors .= "$homepage is <strong>NOT</strong> a valid URL.<br/><br/>";
        }
    } else {
        $errors .= 'Please enter your home page.<br/>';
    }

    if ($_POST['message'] != "") {
        $_POST['message'] = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        if ($_POST['message'] == "") {
            $errors .= 'Please enter a message to send.<br/>';
        }
    } else {
        $errors .= 'Please enter a message to send.<br/>';
    }

    if (!$errors) {
        $mail_to = 'jesal@calistolabs.com';
        $subject = 'New Career Form Submission';
        $message  = 'From: ' . $_POST['name'] . "\n";
        $message .= 'Email: ' . $_POST['email'] . "\n";
        $message .= 'Homepage: ' . $_POST['homepage'] . "\n";
        $message .= "Message:\n" . $_POST['message'] . "\n\n";
        mail($to, $subject, $message);

        echo "Thank you for your email!<br/><br/>";
    } else {
        echo '<div style="color: red">' . $errors . '<br/></div>';
    }
}
?>
