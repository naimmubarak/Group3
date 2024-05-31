<?php
require_once('config.php');
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['user_id'])) {
    $userID = $_GET['user_id'];

    // User Info
    $user_sql = "SELECT * FROM Users WHERE UserID = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $userID);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();


    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();

        // Query 3
        $nested_query_sql = "SELECT A.UserID, ROUND((A.User_Rank * 100.0 / A.All_Users), 2) AS User_Percentile
                            FROM (
                                SELECT U1.UserID, COUNT(U2.UserID) AS User_Rank, (SELECT COUNT(*) FROM Users) AS All_Users
                                FROM Users U1
                                JOIN Users U2 ON U2.Lvl <= U1.Lvl
                                WHERE U1.UserID = ?
                                GROUP BY U1.UserID, U1.Lvl
                            ) A";
        $nested_query_stmt = $conn->prepare($nested_query_sql);
        $nested_query_stmt->bind_param("i", $userID);
        $nested_query_stmt->execute();
        $nested_query_result = $nested_query_stmt->get_result();
        $percentile_row = $nested_query_result->fetch_assoc();
        $user_percentile = $percentile_row['User_Percentile'];

        // Recipes by Users
        $recipes_sql = "SELECT * FROM Recipes WHERE CreatedByUserID = ?";
        $recipes_stmt = $conn->prepare($recipes_sql);
        $recipes_stmt->bind_param("i", $userID);
        $recipes_stmt->execute();
        $recipes_result = $recipes_stmt->get_result();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Invalid user ID.";
    exit();
}
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
            .div2{
                margin-left: 40px;
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
        <div class="div2">
            <h1>User Profile</h1>
            <h2>Information</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['Username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
            <p><strong>Registration Date:</strong> <?php echo htmlspecialchars($user['Registration_Date']); ?></p>
            <p><strong>Level:</strong> <?php echo htmlspecialchars($user['Lvl']); ?></p>
            <p><strong>Level Percentile:</strong> <?php echo htmlspecialchars($user_percentile); ?>%</p>

            <h2>Recipes Created</h2>
            <?php if ($recipes_result->num_rows > 0): ?>
            <ul>
                <?php while ($recipe = $recipes_result->fetch_assoc()): ?>
                <li>
                    <a href="recipe_detail.php?id=<?php echo $recipe['RecipeID']; ?>">
                        <?php echo htmlspecialchars($recipe['Title']); ?>
                    </a>
                </li>
                <?php endwhile; ?>
            </ul>
            <?php else: ?>
            <p>No recipes found.</p>
            <?php endif; ?>
        </div>
    </body>
</html>
