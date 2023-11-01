<?php
include_once("libs/database.class.php");

$uploadDirectory = 'uploads/'; // Directory to store uploaded files
$uploadimage = 'images/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieName = $_POST['movieName'];
    $movieImage = $_FILES['movieImage'];
    $videoFile = $_FILES['videoFile'];
    $demo = "need to implement";
    $conn = Database::getConnection();

    // Check for errors during file upload
    if ($movieImage['error'] !== UPLOAD_ERR_OK || $videoFile['error'] !== UPLOAD_ERR_OK) {
        echo "Error during file upload.";
        exit;
    }

    // Create the upload directory if it doesn't exist
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    // Generate unique filenames for the image and video
    $imageFileName = uniqid('image_') . '.' . pathinfo($movieImage['name'], PATHINFO_EXTENSION);
    $videoFileName = uniqid('video_') . '.' . pathinfo($videoFile['name'], PATHINFO_EXTENSION);

    $imageDestination = $uploadimage . $imageFileName;
    $videoDestination = $uploadDirectory . $videoFileName;

    // Move the uploaded files to their respective destinations
    if (move_uploaded_file($movieImage['tmp_name'], $imageDestination) &&
        move_uploaded_file($videoFile['tmp_name'], $videoDestination)) {

        $sql = "INSERT INTO `movies` (`movietitle`,`imageurl`,`vdeourl`,`dis`)
                                    VALUES('$movieName','$imageDestination','$videoDestination','$demo')";
        $result = $conn->query($sql);
        if ($result) {
            echo "Movie uploaded successfully!<br>";
            echo "Movie Name: $movieName<br>";
            echo "Movie Image: <img src='$imageDestination' width='200' height='150'><br>";
            echo "Video: <a href='$videoDestination' target='_blank'>View Video</a>";
    } else {
        echo "Failed to upload the movie.";
    }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Video Upload</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Movie Upload</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data" id="upload-form" class="form-animation">
        <input type="text" name="movieName" placeholder="Movie Name" class="text-input">
        <input type="file" name="movieImage" accept="image/*" class="file-input">
        <label for="movieImage" class="file-label image-label">Choose an Image</label>
        <input type="file" name="videoFile" accept="video/*" class="file-input">
        <label for="videoFile" class="file-label video-label">Choose a Video</label>
        <div id="file-name-display" class="file-name"></div>
        <button type="submit" class="upload-button">Upload</button>
    </form>
    <div class="upload-status" id="upload-status"></div>
</body>
<script src="script.js"></script>
</html>



<style>
body {
    text-align: center;
    background-color: #f0f0f0;
    font-family: Arial, sans-serif;
    animation: fadeIn 1s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

h1 {
    font-size: 24px;
    margin-top: 20px;
}

.form-animation {
    animation: slideUp 1s ease, fadeIn 1s ease;
    animation-fill-mode: both;
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
    }
    to {
        transform: translateY(0);
    }
}

form {
    width: 80%;
    max-width: 400px;
    margin: 20px auto;
    text-align: center;
    opacity: 0;
}

.text-input, .file-input {
    display: block;
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: transform 0.2s;
}

.text-input:focus, .file-input:focus {
    transform: scale(1.03);
}

.file-label {
    background-color: #3498db;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.image-label, .video-label {
    display: block;
    margin: 10px 0;
}

.file-input:hover + .file-label {
    background-color: #2980b9;
    transform: scale(1.05);
}

.upload-button {
    background-color: #27ae60;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.upload-button:hover {
    background-color: #219752;
    transform: scale(1.05);
}

.file-name {
    margin-top: 10px;
    opacity: 0;
}

.form-animation .file-name {
    animation: fadeIn 1s 0.5s ease;
    animation-fill-mode: both;
}

.upload-status {
    margin-top: 20px;
    font-weight: bold;
    color: #333;
    opacity: 0;
}

.form-animation .upload-status {
    animation: fadeIn 1s 1s ease;
    animation-fill-mode: both;
}

</style>


<script>
 const validCredentials = {
            username: "shanawas",
            password: "1212"
        };

        const login = () => {
            const enteredUsername = prompt("Enter your username:");
            const enteredPassword = prompt("Enter your password:");

            if (enteredUsername === validCredentials.username && enteredPassword === validCredentials.password) {
                // Authentication successful, show a success message in an alert
                
            } else {
                // Authentication failed, show an error message in an alert
                alert("Login failed. Please check your username and password and try again.");
                window.location.assign("/index.php");

            }
        };

document.addEventListener("DOMContentLoaded", function () {
    const uploadForm = document.getElementById("upload-form");
    const uploadStatus = document.getElementById("upload-status");
    const fileInput = document.getElementById("file-input");
    const fileNameDisplay = document.getElementById("file-name-display");

    fileInput.addEventListener("change", function () {
        const selectedFile = fileInput.files[0];
        if (selectedFile) {
            fileNameDisplay.textContent = "Selected file: " + selectedFile.name;
        } else {
            fileNameDisplay.textContent = "";
        }
    });

    uploadForm.addEventListener("submit", function (e) {
        e.preventDefault();
        uploadStatus.textContent = "Uploading...";

        const formData = new FormData(uploadForm);

        fetch("upload.php", {
            method: "POST",
            body: formData,
        })
        .then((response) => response.text())
        .then((message) => {
            uploadStatus.textContent = message;
            uploadForm.reset();
            alert("video uploaded successfull");
            window.location.assign("index.php");

        });
    });
});

login();
</script>

