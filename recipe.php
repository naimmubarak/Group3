<!-- 
TCSS 445 2024 
Group 3
Huscii Bytes:
Minh Vu, Naim Mubarak, Luke Chung
-->
<?php

require_once('config.php'); // Include the database configuration file

// Establish a connection to the database
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

// Initialize filter variables from the GET parameters
$filter_dietary = isset($_GET['filter_dietary']) ? $_GET['filter_dietary'] : '';
$filter_ingredients = isset($_GET['filter_ingredients']) ? $_GET['filter_ingredients'] : '';

// Build the SQL query based on filters
$sql = "SELECT R.RecipeID, R.Title, R.Description, R.Image FROM Recipes R";

$conditions = array(); // Array to hold query conditions

// Add a nested query to filter recipes with less than 10 ingredients
if ($filter_ingredients) {
    $sql .= " JOIN (SELECT RecipeID 
                    FROM RecipeIngredients 
                    GROUP BY RecipeID 
                    HAVING COUNT(RecipeID) < 10) RI 
              ON R.RecipeID = RI.RecipeID";
}

// Add a nested query to filter recipes by dietary preference
if ($filter_dietary) {
    $conditions[] = "R.PreferenceID = (SELECT PreferenceID FROM DietaryPreferences WHERE Name = '" . mysqli_real_escape_string($conn, $filter_dietary) . "')";
}

// Append conditions to the main SQL query if any conditions exist
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

// Execute the query and get the result
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Recipes List</title>
        <link rel="stylesheet" href="https://bootswatch.com/4/sketchy/bootstrap.min.css">
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
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <!-- Filters section -->
                    <h4>Filter Recipes</h4>
                    <form method="GET" action="recipe.php">
                        <div class="form-group">
                            <label for="filter_dietary">Dietary Preferences</label>
                            <select class="form-control" id="filter_dietary" name="filter_dietary">
                                <option value="">Select</option>
                                <option value="Vegetarian">Vegetarian</option>
                                <option value="Vegan">Vegan</option>
                                <option value="Gluten-Free">Gluten-Free</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="filter_ingredients">Ingredients</label>
                            <button type="submit" name="filter_ingredients" value="true" class="btn btn-primary btn-block">Less than 10 Ingredients</button>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Apply Filters</button>
                    </form>
                    <a href="recipe.php" class="btn btn-secondary btn-block mt-2">Clear Filters</a>
                </div>
                <div class="col-md-9">
                    <h1>Available Recipes</h1>
                    <div class="row">
                        <!-- Check if there are any recipes and display them -->
                        <?php if ($result->num_rows > 0): ?>
                        <?php while ($recipe = $result->fetch_assoc()): ?>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($recipe['Title']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($recipe['Description']) ?></p>
                                    <a href="recipe_detail.php?id=<?= $recipe['RecipeID'] ?>" class="btn btn-primary">View Recipe</a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <p>No recipes found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
