<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HOME</title>
  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    .topic {
      background-color: #ec97ec;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      font-size: 230%;
      text-decoration: none;
      color: #5e1b5e;
      text-align: center;
      height: 500px;
      padding-top: 10px;
      padding-bottom: 10px;
      position: relative;
    }

    .topic img {
      width: 350px;
      height: auto;
      margin-bottom: 10px;
      margin-top: 5px;
    }

   .search-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 20px;
    }

    .search-container input {
      width: 50%;
      max-width: 600px;
      padding: 12px 20px;
      border: none;
      border-radius: 30px 0 0 30px;
      font-size: 16px;
      outline: none;
    }

    .search-container button {
      background-color: white;
      border: none;
      border-left: 1px solid #ccc;
      padding: 12px 20px;
      border-radius: 0 30px 30px 0;
      cursor: pointer;
    }

    .search-container button img {
      width: 20px;
      height: 20px;
    }

    .bottom-nav {
      display: flex;
      align-items: center;
      background-color: #4b004b;
      padding: 20 20px;
      height: 60px;
    }

    .bottom-nav img.logo {
      height: 40px;
    }

    .nav-links {
      display: flex;
      margin-left: auto;
    }

    .nav-btn {
      background-color: #4b004b;
      color: white;
      padding: 23px 30px;
      text-align: center;
      text-decoration: none;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 12px;
      border-right: 2px solid #ffffff;
      transition: background-color 0.3s;
    }

    .nav-btn:last-child {
      border-right: none;
    }

    .nav-btn:hover {
      background-color: #e696ec;
    }

    .nav-btn.active {
      background-color: #e696ec;
      color: #ffffff;
    }

    h1 {
      font-size: 40px;
      color: #4b004b;
      text-align: center;
      margin-top: 30px;
      font-family: 'Times New Roman', serif;
    }


    .upload-container {
      background-color: #f4caff;
      max-width: 500px;
      margin: 30px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .upload-box {
      border: 2px dashed #aaa;
      background-color: #fff;
      padding: 20px;
      text-align: center;
      margin-bottom: 20px;
    }

    input[type="text"], input[type="file"] {
      width: 50%;
      padding: 10px;
      margin: 8px 0 20px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    button[type="submit"],
    button[type="reset"] {
      padding: 10px 25px;
      background-color: #a94eb5;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button[type="reset"] {
      background-color:rgb(153, 137, 153);
    }

    .form-group {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.form-group label {
  flex: 0 0 150px; /* Tetapkan lebar tetap label */
  margin-right: 10px;
  font-weight: bold;
  color: #4b004b;
}

.form-group input {
  flex: 1;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
</style>
</head>
<body>
  <div class="topic">
    <img src="images/whiteLogo.png" alt="logo" class="logo" />

    <div class="search-container">
      <input type="text" placeholder="Search notes by title, tag or keyword..." />
      <button>🔍</button>
    </div>
  </div>

  <div class="bottom-nav">
    <img src="images/whiteLogo.png" alt="Logo" class="logo" />
    <div class="nav-links">
      <a href="#" class="nav-btn active">Home</a>
      <a href="#" class="nav-btn">My Notes</a>
      <a href="#" class="nav-btn">Upload</a>
      <a href="#" class="nav-btn">Connection</a>
      <a href="#" class="nav-btn">Profile</a>
    </div>
  </div>

  <h1 style = "font-family:Times New Roman; font-size:40px;">UPLOAD YOUR NOTES</h1>
  <div class="upload-container">
  <form action="uploadPage.php" method="POST" enctype="multipart/form-data">
        <div class="upload-box">
        <p>Drop files here</p>
        <p><small>Supported: PNG, JPG, PDF</small></p>
        <input type="file" name="file" required/>
        </div>

        <div class="form-group">
            <label for="fileName">File Name : </label>
            <input type="text" placeholder="file name..." required><br>
        </div>  
        <div class="form-group">
            <label for="chapterName">Chapter Name : </label>
            <input type="text" placeholder="chapter name..." required><br>
        </div>
        <div class="form-group">
            <label for="author">Author : </label>
            <input type="text" placeholder="author name..." required><br>
        </div>
        <div class="form-group">
            <label for="courseName">Course Name : </label>
            <input type="text" placeholder="course name..." required><br>
        </div>
        <div class="form-group">
            <label for="University">University :</label>
            <input type="text" placeholder="university name..." required>
        </div>

        <div class="btn-group">
        <button type="reset">Cancel</button>
        <button type="submit">Upload</button>
        </div>
    </div>
  </form>
</body>
</html>
