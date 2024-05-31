<!-- 
TCSS 445 2024 
Group 3
Huscii Bytes:
Minh Vu, Naim Mubarak, Luke Chung
-->
<?php
require_once('config.php');
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

// Check if an ID was passed
if (isset($_GET['id'])) {
    $recipeID = $_GET['id'];
    $sql = "SELECT * FROM Recipes WHERE RecipeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipeID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the recipe exists
    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
    } else {
        echo "Recipe not found.";
        exit();
    }
} else {
    echo "Invalid recipe ID.";
    exit();
}

// Query to get ingredient names
$ingredient_sql = "
    SELECT I.Name
    FROM Recipes R
    JOIN RecipeIngredients ON R.RecipeID = RecipeIngredients.RecipeID
    JOIN Ingredients I ON RecipeIngredients.IngredientID = I.IngredientID
    WHERE R.RecipeID = ?
    GROUP BY I.Name
";
$ingredient_stmt = $conn->prepare($ingredient_sql);
$ingredient_stmt->bind_param("i", $recipeID);
$ingredient_stmt->execute();
$ingredient_result = $ingredient_stmt->get_result();
$ingredients = [];
while ($row = $ingredient_result->fetch_assoc()) {
    $ingredients[] = $row['Name'];
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
        <div class="container recipe-container">
            <h1 class="recipe-title"><?= htmlspecialchars($recipe['Title']) ?></h1>
            <div class="recipe-section">
                <h3>Description</h3>
                <p><?= htmlspecialchars($recipe['Description']) ?></p>
            </div>
            <div class="recipe-section">
                <h3>Ingredients</h3>
                <ul>
                    <?php foreach ($ingredients as $ingredient): ?>
                    <li><?= htmlspecialchars($ingredient) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="recipe-section">
                <h3>Instructions</h3>
                <p><?= nl2br(htmlspecialchars($recipe['Instructions'])) ?></p>
            </div>
        </div>
    </body>
</html>