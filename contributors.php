<?php
require_once('config.php');
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query 2
$contributors_sql = "SELECT Users.UserID, Users.Username, COUNT(Recipes.RecipeID) AS RecipeCount
                    FROM Users
                    JOIN Recipes ON Users.UserID = Recipes.CreatedByUserID
                    GROUP BY Users.UserID, Users.Username
                    ORDER BY RecipeCount DESC";
$contributors_result = mysqli_query($conn, $contributors_sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($recipe['Title']) ?> - Recipe Details</title>
        <link rel="stylesheet" href="https://bootswatch.com/4/sketchy/bootstrap.min.css">
        <style>
            body {
                background-color: #f8f9fa;
            }
            .recipe-image {
                max-height: 400px;
                object-fit: cover;
            }
            .recipe-container {
                margin-top: 20px;
                background-color: #ffffff;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 20px;
            }
            .recipe-title {
                color: #343a40;
            }
            .recipe-section {
                margin-bottom: 20px;
            }
            .navbar {
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">HusciiByte</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
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

    <div class="container">
        <h1>Top Contributors</h1>
        <ul class="list-group">
            <?php while ($row = mysqli_fetch_assoc($contributors_result)): ?>
                <li class="list-group-item">
                    <a href="profile.php?user_id=<?php echo $row['UserID']; ?>">
                        <?php echo htmlspecialchars($row['Username']); ?> (Recipes: <?php echo $row['RecipeCount']; ?>)
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>

</html>
