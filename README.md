<h1>Photo Album Website Documentation</h1>
<em>Welcome</em> to the documentation for the Photo Album website hosted at https://csci4140hw1-1155160200-mh04.onrender.com/index.php.
<br/>
<h3>Overview</h3>
The website consists of several PHP files and a CSS file, each serving a specific purpose. Here's a brief description of each:
<br/>
<li>index.php: This is the homepage displaying all photos in the album, including both public and private photos for logged-in users. The bottom section allows users to upload a photo (up to 5MB) and choose privacy settings. After upload, users can preview and edit the photo in view.php.</li>
<br/>
<li>upload.php: Handles the upload process, including file info retrieval, type and size checks. It uploads the photo to the PostgreSQL database with attributes like image URL, owner, creation date, privacy, and editability. If issues occur (e.g., file too large), the page redirects users to the index page with an appropriate error message.</li>
<br/>
<li>view.php: Provides image preview, filters, and action buttons for photo modification or deletion. Clicking a filter button applies the chosen filter to the image, with filter options passed through the URL query. The discard action deletes the image from the server and database, while the finish button persists the image in the database, setting the editable field to 0.</li>
<br/>
<li>db_connect.php: Manages the database connection for various pages.</li>
<br/>
<li>login.php: Allows users to log in with a username and password. Incorrect credentials redirect users to the index page with an error message. Upon successful login, users are redirected to the index page with their username displayed at the top left. Users can view public images and their private images. A cookie named "user_session" with a one-day expiry is assigned to the user.</li>
<br/>
<li>logout.php: Logs users out by setting the cookie expiry date one day earlier.</li>
<br/>
<li>uploads/ directory: Stores all images uploaded by users.</li>
<br/>
<li>text.php: This file is for testing database connections and queries.</li>
<br/>

Bonus checkpoint:
1. Input Validation
    1.1. file upload checking, such as (upload.php)
    - You can't upload files of this type!
    - Your file is too big!
    - No file chosen for upload, please select a file!
    1.2. input field validation checking, such as (login.php)
    - Use of htmlspecialchars, used to sanitize the password and username input before further processing, and it helps to prevent HTML and script injection
    - also added 'required' attribute in input field to remind user input the field
2. File Type Checking
    2.1.    <code>
            $img_ex = pathinfo($file_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png'];
            if(in_array($img_ex_lc, $allowed_exs)) {..} </code>
            and more (not space for copy and paste all) in upload.php
    2.2. <Code>accept=".jpg, .jpeg, .gif, .png" and required</Code> attribute in the file input field for double-confirm
3. System Initialization
    init.php
    - Go ahead button, go back to /index.php
    - Go Forward button, delete all data in table and images under uploads/ folder


Thank you for taking the time to read this documentation. <3