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

    function borderImage($image_path, $color, $width, $height)
    {
        echo 'inside borderImage function<br/>';
        $imagick = new \Imagick(realpath($image_path));
        $imagick->borderImage($color, $width, $height);
        header("Content-Type: image/jpeg");
        echo $imagick->getImageBlob();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["apply_border"])) {
            echo "APPLY BORDER!<br/>";
            $image_url_post = $_POST['image_url'];
            $image_path = "uploads/" . $image_url_post; // Adjust the path as per your file naming convention
            borderImage($image_path, "black", 50, 20);
            // exit; // Stop further execution after applying the filter
        } elseif (isset($_POST["apply_black_white"])) {
            echo "APPLY BLACK & WHITE!<br/>";
        //     // Add code for the black-and-white filter if needed
        //     // You can use another Imagick function similar to the borderImage
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
</head>
<body>
    <a href="index.php">Index</a>
    <h1>Edit</h1>
    <?php
        $conn = pg_connect("host=$host dbname=$dbname user=$username password=$password");
        $id = $_GET['id'];
        $sql = "SELECT * FROM album WHERE id = $id";
        $result = pg_query($conn, $sql);
        
        if(pg_num_rows($result) > 0) {
            while($row = pg_fetch_assoc($result)) {
                echo "<div>";
                echo "<img src='uploads/".$row['image_url']."' width='300px' height='200px'>";
                echo "<p>".$row['privacy']."</p>";
                echo "<p>".$row['created_at']."</p>";
                echo "</div>";

                $image_url = $row['image_url'];
                echo "<p>Image URL: $image_url</p>";
                // Add filter form
                echo "<form method='post' action='view.php'>";
                echo "<input type='hidden' name='image_url' value='$image_url'>";
                echo "<button type='submit' name='apply_border'>Apply Border</button>";
                echo "<button type='submit' name='apply_black_white'>Apply Black & White</button>";
                echo "</form>";

                // Add discard and finish buttons
                echo "<form method='post' action='view.php'>";
                echo "<input type='hidden' name='photo_id' value='$id'>";
                echo "<button type='submit' name='discard'>Discard</button>";
                echo "<button type='submit' name='finish'>Finish</button>";
                echo "</form>";
            }
        }
    ?>

</body>
</html>