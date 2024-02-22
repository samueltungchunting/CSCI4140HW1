<?php
    include "db_connect.php";

    $photos = [
        ['id' => 1, 'owner' => "Student", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'private'],
        ['id' => 2, 'owner' => "Student", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'public'],
        ['id' => 3, 'owner' => "admin", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'private'],
        ['id' => 4, 'owner' => "Student", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'private'],
        ['id' => 5, 'owner' => "Student", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'private'],
        ['id' => 6, 'owner' => "Student", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'private'],
        ['id' => 7, 'owner' => "Student", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'private'],
        ['id' => 8, 'owner' => "Student", 'image_url' => 'IMG-65d682a501b069.22508447.png', 'privacy' => 'private'],
    ];

    $sql = "SELECT * FROM album ORDER BY id DESC";
    $result = pg_query($conn, $sql);
    if(pg_num_rows($result) > 0) {
        while($row = pg_fetch_assoc($result)) {
            $photos[] = $row;
        }
    }
    $visiblePhotos = array_filter($photos, function($photo) {
        return $photo['privacy'] === 'public' || $photo['owner'] === $_COOKIE['user_session'];
    });
    $visiblePhotos = array_values($visiblePhotos);

    $photosPerPage = 8;
    $totalPhotos = count($visiblePhotos);
    $totalPages = ceil($totalPhotos / $photosPerPage);
    
    $currentpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $currentpage = max(1, min($currentpage, $totalPages));

    $startIndex = ($currentpage - 1) * $photosPerPage;
    
    $displayPhotos = array_slice($visiblePhotos, $startIndex, $photosPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css" />
    <title>Document</title>
</head>
<body class="index_page">
    <div class="index_container">
        <div class="index_container_userInfo">
            <p class="">Welcome, <?php echo (isset($_COOKIE['user_session'])) ? $_COOKIE['user_session'] : 'Guest'?>!!</p>
            <?php echo (isset($_COOKIE['user_session'])) ? "<a href='logout.php'>Logout</a>" : "<a href='login.php'>Login</a>" ?>
        </div>
        <h2 class="">Album</h2>
        <div class="index_gallery">
            <?php
                if (isset($_COOKIE['user_session'])) {
                    foreach ($displayPhotos as $photo) {
                        // Check if the photo is public or uploaded by the current user
                        if ($photo['privacy'] == 'public' || ($photo['privacy'] == 'private' && $photo['owner'] == $_COOKIE['user_session'])) {
                            echo "
                                <div>
                                    <img src='uploads/{$photo['image_url']}' alt='Photo'>
                                    <p>{$photo['owner']}</p>
                                </div>
                                ";
                        }
                    }
                } else {
                    foreach ($displayPhotos as $photo) {
                        if ($photo['privacy'] == 'public') {
                            echo "
                                <div>
                                    <img src='uploads/{$photo['image_url']}' alt='Photo'>
                                    <p>{$photo['owner']}</p>
                                </div>
                                ";
                        }
                    }
                }
            ?>
        </div>
        <div class="index_galleryPagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="?page=<?php echo $i; ?>" <?php echo ($i == $currentpage) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <div class="index_fileUpload">
            <p>Upload Photos:</p>
            <form action="upload.php" method="post" enctype="multipart/form-data" class="index_fileUpload_form">
                <input type="file" name="my_image" required>
                <input type="submit" name="submit" value="Upload" class="fileUpload_btn">
                <!-- also with a toggle that have either private or public as option -->
                <!-- https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_switch -->
                <select name="privacy" id="privacy">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select> 
            </form>
            <?php
                if (isset($_GET['error'])) {
                    echo "<p>{$_GET['error']}</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>