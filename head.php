<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head</title>
    <style>

      .navbar {
        background-color: #660066;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 10px;
        height: 60px;
      }

      .navbar .logo {
        height: 60px;
      }

      .navbar ul {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
      }

      .navbar li {
        margin-left: 10px;
      }

      .navbar a {
        text-decoration: none;
        color: white;
        padding: 14px 16px;
        display: block;
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
      }

      .navbar a:hover {
        background-color: #990099;
      }

      .topic {
        background-color: #ec97ec;
        font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
        font-size: 230%;
        text-decoration: none;
        color: #5e1b5e;
        text-align: center;
        height: 400px;
        padding-top: 5px;
        padding-bottom: 5px;
      }

      .topic img {
        width: 245px;
        height: auto;
        margin-bottom: 5px;
        margin-top: 5px;
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
      <a href="homePage.php" class="nav-btn active">Home</a>
      <a href="mynotesPage.php" class="nav-btn">My Notes</a>
      <a href="uploadPage.php" class="nav-btn">Upload</a>
      <a href="connectionPage.php" class="nav-btn">Connection</a>
      <a href="profilePage.php" class="nav-btn">Profile</a>
      <a href="logout.php" class="nav-btn">Log Out</a>
      
    </div>
  </div>
</body>
</html>