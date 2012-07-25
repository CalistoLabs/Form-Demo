<?php

if (isset($_POST['Submit'])) {

    $allowed_filetypes = array('.doc','.docx','.pdf');
    $max_filesize = 10485760;
    $upload_path = './uploads/resume/';
    $errors = null;

    if(isset($_FILES['resume'])) {
    $filename = $_FILES['resume']['name'];
    $ext = strrchr($filename,'.');

    // Check if the filetype is allowed, if not DIE and inform the user.
    if(!in_array($ext,$allowed_filetypes))
        $errors .= 'The file you attempted to upload is not allowed.';

    // Now check the filesize, if it is too large then DIE and inform the user.
    if(filesize($_FILES['resume']['tmp_name']) > $max_filesize)
        $errors .= 'The file you attempted to upload is too large.';

    // Check if we can upload to the specified path, if not DIE and inform the user.
    if(!is_writable($upload_path))
        $errors .= 'You cannot upload to the specified directory, please CHMOD it to 777.';

    // Upload the file to your specified path.
    if(move_uploaded_file($_FILES['resume']['tmp_name'],$upload_path . $filename))
        $resume = $upload_path . $filename;
    else
        $errors .= 'There was an error during the file upload.  Please try again.';
    }

    if (!empty($_POST['name'])) {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    } else {
        $errors .= 'Please enter your name.<br/>';
    }

    if (!empty($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors .= "$email is <strong>NOT</strong> a valid email address.<br/><br/>";
        }
    } else {
        $errors .= 'Please enter your email address.<br/>';
    }

    if (!$errors) {
        $to = 'jesal@calistolabs.com';
        $subject = 'New Career Form Submission';
        $message  = 'From: ' . $name . "\n";
        $message .= 'Email: ' . $email . "\n";
        mail($to, $subject, $message);

        echo "Thank you for your email!<br/><br/>";
    } else {
        echo '<div style="color: red">' . $errors . '<br/></div>';
    }
}

?>
