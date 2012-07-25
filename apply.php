<?php

if (isset($_POST['Submit'])) {

    $allowed_filetypes = array('.doc','.docx','.pdf','.avi','.asf','.flv','.fla','.swf','.wma','.wmv','.mp3','.jpg','.jpeg','.gif','.bmp','.png');
    $max_filesize = 10485760;
    $upload_path = './uploads/';
    $errors = null;
    $uploaded_files = array();

    //Upload
    if(isset($_FILES)) {
        $_FILES = multiple($_FILES);
        foreach ($_FILES as $fileType => $files) {
            if(isAssoc($files)) {
                upload($files, $fileType, 0, $allowed_filetypes, $max_filesize, $upload_path, $errors, $uploaded_files);
            } else {
                foreach ($files as $fileIndex => $file) {
                    upload($file, $fileType, $fileIndex, $allowed_filetypes, $max_filesize, $upload_path, $errors, $uploaded_files);
                }
            }
        }
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

    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);
    $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    $visa = filter_var($_POST['visa'], FILTER_SANITIZE_STRING);
    $comments = filter_var($_POST['comments'], FILTER_SANITIZE_STRING);
    $verification_details = filter_var($_POST['verification_details'], FILTER_SANITIZE_STRING);

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

function compile_message($name, $email, $location, $role, $country, $visa, $uploaded_files, $comments, $verification_details) {
    $template = file_get_contents('email_template.html');
}

function upload($file, $fileType, $fileIndex, $allowed_filetypes, $max_filesize, $upload_path, &$errors, &$uploaded_files) {
    $filename = $file['name'];
    $ext = strrchr($filename,'.');

    // Check if the filetype is allowed, if not DIE and inform the user.
    if(!in_array($ext,$allowed_filetypes))
        $errors .= 'The file you attempted to upload is not allowed.';

    // Now check the filesize, if it is too large then DIE and inform the user.
    if(filesize($file['tmp_name']) > $max_filesize)
        $errors .= 'The file you attempted to upload is too large.';

    // Check if we can upload to the specified path, if not DIE and inform the user.
    if(!is_writable($upload_path))
        $errors .= 'You cannot upload to the specified directory, please CHMOD it to 777.';

    if(!$errors) {
        // Upload the file to your specified path.
        if(move_uploaded_file($file['tmp_name'],$upload_path . $filename))
            $uploaded_files[$fileType][$fileIndex] = $upload_path . $filename;
        else
            $errors .= 'There was an error during the file upload.  Please try again.';
    }
}

function multiple(array $_files, $top = TRUE) {
    $files = array();
    foreach($_files as $name=>$file){
        if($top) $sub_name = $file['name'];
        else    $sub_name = $name;

        if(is_array($sub_name)){
            foreach(array_keys($sub_name) as $key){
                $files[$name][$key] = array(
                    'name'     => $file['name'][$key],
                    'type'     => $file['type'][$key],
                    'tmp_name' => $file['tmp_name'][$key],
                    'error'    => $file['error'][$key],
                    'size'     => $file['size'][$key],
                );
                $files[$name] = multiple($files[$name], FALSE);
            }
        }else{
            $files[$name] = $file;
        }
    }
    return $files;
}

function isAssoc($arr) {
    return array_keys($arr) !== range(0, count($arr) - 1);
}

?>
