<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <style>
        body {
            background-color: rgb(255, 255, 255);
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
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .bottom-nav {
            display: flex;
            justify-content: center;
            background-color: #4b004b;
        }

        .nav-btn {
            background-color: #660066;
            color: white;
            padding: 15px 20px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            border-right: 1px solid #fff;
            transition: background-color 0.3s;
        }

        .nav-btn:last-child {
            border-right: none;
        }

        .nav-btn:hover {
            background-color: #990099;
        }

        .nav-btn.active {
            background-color: #e696ec;
            color: #4b004b;
        }
    </style>
</head>
<body>
    <div class="topic">
        <img src="images/whiteLogo.png" alt="logo" class="logo">
    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="#" class="nav-btn active">Home</a>
        <a href="#" class="nav-btn">My Notes</a>
        <a href="#" class="nav-btn">Upload</a>
        <a href="#" class="nav-btn">Connection</a>
        <a href="#" class="nav-btn">Profile</a>
    </div>
</body>
</html>
