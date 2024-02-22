<?php 
    include "db_connect.php";

    // $conn = pg_connect("host=$host dbname=$dbname user=$username password=$password");
    // $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;user=$username;password=$password");
    // if the editable attribute is 0, then redirect to index.php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM album WHERE id = $id";
        $result = pg_query($conn, $sql);
        if($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            if ($row['editable'] == 0) {
                header("Location: index.php");
                exit();
            }
        } else {
            header("Location: index.php");
            exit();
        }
    }
    session_start();

    function borderImage($image_path){
        $image = new Imagick();

        // Read an existing image file
        $image->readImage('uploads/'.$image_path);
        $borderColor = new ImagickPixel('#000000'); // Black color
        $image->borderImage($borderColor, 40, 20);

        $image->writeImage('uploads/'.'Border-'.$image_path);

        $image->destroy();
        return 'uploads/Border-'.$image_path;
    }

    function BlurImage($image_path) {
        $image = new Imagick();
        $image->readImage('uploads/'.$image_path);
        $image->blurImage(5, 3); 

        $image->writeImage('uploads/'.'Blur-'.$image_path);

        $image->destroy();
        return 'uploads/Blur-'.$image_path;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["apply_border"])) {

            $id = $_POST['id'];
            $image_url_post = $_POST['image_url'];
            $image_path = $image_url_post; // Adjust the path as per your file naming convention
            $imgSrc = borderImage($image_path);
            header("Location: view.php?id=$id&filter=Border");
            exit();
        } elseif (isset($_POST["apply_black_white"])) {
            $id = $_POST['id'];
            $image_url_post = $_POST['image_url'];
            $image_path = $image_url_post; // Adjust the path as per your file naming convention
            $imgSrc = BlurImage($image_path);
            header("Location: view.php?id=$id&filter=Blur");
        }

        if (isset($_POST["discard"])  && isset($_POST['photo_id'])) {
            $id = $_POST['photo_id'];
            $getImageUrlSql = "SELECT image_url FROM album WHERE id = $id";
            $getImageUrlResult = pg_query($conn, $getImageUrlSql);
            if($getImageUrlResult && pg_num_rows($getImageUrlResult) > 0) {
                $row = pg_fetch_assoc($getImageUrlResult);
                $image_url = $row['image_url'];
                $image_path = "uploads/" . $image_url; // Adjust the path as per your file naming convention
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $deleteSql = "DELETE FROM album WHERE id = $id";
                if (pg_query($conn, $deleteSql)) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . pg_error($conn);
                }
            } else {
                echo "Error: " . $sql . "<br>" . pg_error($conn);
            }
        } elseif (isset($_POST["finish"]) && isset($_POST['photo_id'])) {
            $id = $_POST['photo_id'];
            // // Assuming $id is the ID of the photo being edited
            $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
            $image = new Imagick();
            $image_url = $_POST['image_url']; // Adjust the path as per your file naming convention
            $image->readImage('uploads/'.$image_url);
            switch($filter) {
                case 'Border':
                    $borderColor = new ImagickPixel('#000000'); // Black color
                    $image->borderImage($borderColor, 40, 20);
                    break;
                case 'Blur':
                    $image->blurImage(5, 3); 
                    break;
                default:
                    break;
            }
            $image->writeImage('uploads/'.$image_url);
            $deleteImageSql = "UPDATE album SET editable = 0 WHERE id = $id";
            if (pg_query($conn, $deleteImageSql)) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . pg_error($conn);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="view.css">
</head>
<body>
    <a href="index.php">Index</a>
    <h1>Edit</h1>
    <?php
        include "db_connect.php";

        $id = $_GET['id'];
        $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
        $sql = "SELECT * FROM album WHERE id = $id";
        $result = pg_query($conn, $sql);
        
        if(pg_num_rows($result) > 0) {
            while($row = pg_fetch_assoc($result)) {
                echo "<div class=\"view_page\">";
                    echo "<div class=\"view_filtering\">";
                        echo "<div>";
                            if (isset($filter)) {
                                echo "<img src='uploads/".$filter.'-'.$row['image_url']."' width='300px' height='200px'>";
                            } else {
                                echo "<img src='uploads/".$row['image_url']."' width='300px' height='200px'>" ;
                            }
                            echo "<p>".$row['privacy']."</p>";
                            echo "<p>".$row['created_at']."</p>";
                        echo "</div>";

                        $image_url = $row['image_url'];
                        // Add filter form
                        echo "<h3>Apply Filter: </h3>";
                        echo "<form method='post' action='view.php?id=$id'>";
                            echo "<input type='hidden' name='id' value='$id'>";
                            echo "<input type='hidden' name='image_url' value='$image_url'>";
                            echo "<button type='submit' name='apply_border'>Apply Border</button>";
                            echo "<button type='submit' name='apply_black_white'>Blur</button>";
                        echo "</form>";
                    echo "</div>";
                    // Add discard and finish buttons
                    echo "<form method='post' action='view.php' class=\"view_actions\">";
                        echo "<input type='hidden' name='photo_id' value='$id'>";
                        echo "<input type='hidden' name='image_url' value='$image_url'>";
                        echo "<button type='submit' name='discard'>Discard</button>";
                        echo "<button type='submit' name='finish'>Finish</button>";
                    echo "</form>";
                echo "</div>";
            }
        }
    ?>

</body>
</html>