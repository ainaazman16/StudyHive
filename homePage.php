<?php
session_start();
if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home - StudyHive</title>
  <link rel="stylesheet" href="style.css">

  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    h1 {
      font-size: 60px;
      text-align: center;
      color: #4b004b;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      margin-top: 30px;
    }

    .cards-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
      padding: 30px;
      max-width: 1300px;
      margin: 0 auto;
    }

    .card {
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: 0.3s;
      border-radius: 5px;
      background-color: #fff;
      height: 250px;
      width: 100%; /* normal cards fill the grid cell */
    }

    .card:hover {
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .card .container {
      flex-grow: 1;
      padding: 15px 20px;
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
    }

    .card .container h3 {
      margin: 0;
      font-size: 16px;
      color: #4b004b;
      font-family: Montserrat, sans-serif;
    }

    /* ✅ Recently Viewed: fixed width + centered in row */
    .card.recently-viewed {
      grid-column: 1 / -1;           /* Span full row */
      width: 600px;                  /* Fixed width */
      justify-self: center;          /* Center in grid */
    }

    @media screen and (max-width: 1000px) {
      .cards-container {
        grid-template-columns: repeat(2, 1fr);
      }

      .card.recently-viewed {
        width: 100%;
        grid-column: 1 / -1;
      }
    }

    @media screen and (max-width: 600px) {
      .cards-container {
        grid-template-columns: 1fr;
      }

      .card.recently-viewed {
        width: 100%;
        grid-column: auto;
      }
    }
  </style>
</head>
<body>

  <?php include('head.php'); ?>

  <h1>User's Dashboard</h1>

  <div class="cards-container">
    <!-- Top row -->
    <div class="card">
      <div class="container">
        <h3><b>My Notes</b></h3>
      </div>
    </div>

    <div class="card">
      <div class="container">
        <h3><b>Recommendations</b></h3>
      </div>
    </div>

    <div class="card">
      <div class="container">
        <h3><b>Connection</b></h3>
      </div>
    </div>

    <!-- Centered Recently Viewed card with fixed width -->
    <div class="card recently-viewed">
      <div class="container">
        <h3><b>Recently Viewed</b></h3>
      </div>
    </div>
  </div>
<?php include('footer.php'); ?>
</body>
</html>
