<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EDIT NOTES</title>
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

    /* === Bottom Navigation Bar === */
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

    h1{
        font-size: 60px;
        text-align: center;
        color: #4b004b;
        font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        background-color: #f8f4fc;
        color: #333;
        }

        .site-header {
        background-color: #dba8f2;
        padding: 20px;
        text-align: center;
        }

        .logo-area {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        }

        .logo {
        height: 50px;
        }

        .search-bar {
        margin-top: 10px;
        padding: 10px;
        width: 60%;
        border: none;
        border-radius: 20px;
        }

        .navbar {
        display: flex;
        justify-content: center;
        background-color: #790EAD;
        }

        .navbar a {
        color: white;
        padding: 14px 20px;
        display: inline-block;
        text-decoration: none;
        transition: background-color 0.3s;
        }

        .navbar a:hover {
        background-color: #5d0b8d;
        }

        .page-title {
        text-align: center;
        font-size: 2em;
        margin: 30px 0;
        }

        .back-link {
        display: block;
        margin: 20px;
        text-decoration: none;
        color: #444;
        }

        .note-card {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px #ccc;
        }

        .note-header {
        position: relative;
        }

        .note-header h3 {
        margin-top: 0;
        }

        .author {
        color: #555;
        font-size: 0.9em;
        }

        .btn {
        margin: 5px;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }

        .report {
        background-color: #ff5e5e;
        color: white;
        }

        .remove {
        background-color: #944be3;
        color: white;
        }

        .note-preview {
        width: 100%;
        margin-top: 20px;
        border-radius: 5px;
        }

        .update-time {
        font-size: 0.8em;
        color: gray;
        text-align: right;
        }

        .confirmation {
        text-align: center;
        margin-top: 30px;
        }

        .confirmation .yes {
        background-color: #944be3;
        color: white;
        }

        .confirmation .no {
        background-color: #ccc;
        color: black;
        }

  </style>
</head>
<body>

  <div class="topic">
    <img src="images/whiteLogo.png" alt="logo" class="logo" />

    <!-- Search Bar -->
    <div class="search-container">
      <input type="text" placeholder="Search notes by title, tag or keyword..." />
      <button>🔍</button>
    </div>
  </div>

  <!-- Bottom Navigation Bar -->
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

  <h1>Downloaded Notes</h1>

  <section class = "note-card">
    <div class = "note-header">
        <h3> Event-Driven Programming: User Interface Design</h3>
        <p class="author">Author:</p>
        <button class="btn report">Report</button>
        <button class="btn remove">Remove</button>
      </div>
      
      <p class="update-time">Last update: Tue, 27/5/2025</p>
    </section>

    <div class="confirmation">
      <p>Are you sure to remove notes?</p>
      <button class="btn yes">Yes</button>
      <button class="btn no">No</button>
    </div>

</body>
</html>
