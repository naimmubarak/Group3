<!-- 
TCSS 445 4/2024 
Group 3
Huscii Bytes:
Minh Vu, Naim Mubarak, Luke Chung
-->
<?php require_once('config.php'); // Include the database configuration file ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <!-- Add a reference to the external stylesheet -->
        <link rel="stylesheet" href="https://bootswatch.com/4/sketchy/bootstrap.min.css">
    </head>
    <body>
        <!-- START -- Add HTML code for the top menu section (navigation bar) -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">HusciiByte</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="recipe.php">Recipes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php?user_id=10000001">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contributors.php">Contributors</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- END -- Add HTML code for the top menu section (navigation bar) -->
        <div class="jumbotron">
            <h1 class="display-3">Starting Out</h1>
            <p class="lead">Welcome to HusciiByte!</p>
            <hr class="my-4">
            <p>This is an app to search/create cooking recipes.</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="recipe.php" role="button">Look at current recipes</a>
            </p>
        </div>
    </body>
</html>

