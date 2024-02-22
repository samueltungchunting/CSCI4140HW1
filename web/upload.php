<?php
if(!isset($_COOKIE['user_session'])) {
    header('Location: index.php');
    exit();
}

// handle file upload
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && isset($_FILES['my_image'])) {
    include "db_connect.php";

    // $conn = pg_connect("host=$host dbname=$dbname user=$username password=$password");

    // echo "<pre>";
    // print_r($_FILES['my_image']);
    // echo "<pre>";

    $file = $_FILES['my_image'];

    $privacy = $_POST['privacy'];
    $file_name = $file['name'];
    // $file_tmp_name = "{$file['tmp_name']}.tmp";
    $file_tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    $file_type = $file['type'];

    if(empty($file_name)) {
        $em = "No file chosen for upload, please select a file!";
        header("Location: index.php?error=$em");
        exit();
    }

    if($file_error == 0) {
        // if image size larger then 4MB then return error
        if($file_size <= 4194304*2) {
            $img_ex = pathinfo($file_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png", "gif");
            $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png'];

            // echo ($img_ex) . ' <-- img_ex' . '<br>';
            if(in_array($img_ex_lc, $allowed_exs)) 
            // if (in_array($img_ex, $allowedExtensions) && in_array($file_type, $allowedMimeTypes))
            {
                $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                $img_upload_path = 'uploads/' . $new_img_name;
                move_uploaded_file($file_tmp_name, $img_upload_path);

                //Insert into database
                $sql = "INSERT INTO album(image_url, owner, privacy, created_at, editable) VALUES ('$new_img_name', '{$_COOKIE['user_session']}', '$privacy', NOW(), 1)";
                $result = pg_query($conn, $sql) or die('Query failed: ' . pg_last_error());

                if($result) {
                    $lastInsertedId = pg_fetch_result(pg_query($conn, "SELECT lastval()"), 0);
                    header("Location: view.php?id=$lastInsertedId");
                } else {
                    echo "Error: " . $sql . "<br>" . pg_last_error($conn);
                }
            } else {
                $em = "You can't upload files of this type!";
                header("Location: index.php?error=$em");
                exit();
            }
        } else {
            $em = "Your file is too big!";
            header("Location: index.php?error=$em");
            exit();
        }
    } else {
        $em = "There was an error uploading your file!";
        header("Location: index.php?error=$em");
        exit();
    }
} else {
    echo "No file uploaded.....";
    header('Location: index.php');
}
