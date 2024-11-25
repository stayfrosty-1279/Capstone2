<?php
// Include necessary files
require_once 'includes/classes/ImageComparison.php';

// Initialize the database connection if needed
require_once 'includes/db.php';  // Adjust path as needed

// Start session if needed
session_start();

// HTML form to test image comparison
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Image Comparison</title>
</head>
<body>
    <h2>Image Comparison Test</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label>First Image:</label>
            <input type="file" name="image1" accept="image/*" required>
        </div>
        <br>
        <div>
            <label>Second Image:</label>
            <input type="file" name="image2" accept="image/*" required>
        </div>
        <br>
        <button type="submit" name="compare_images">Compare Images</button>
    </form>

    <?php
    if (isset($_POST['compare_images']) && isset($_FILES['image1']) && isset($_FILES['image2'])) {
        try {
            // Create instance of ImageComparison class
            $imageComparison = new \Admin\Includes\Classes\ImageComparison();
            
            // Get temporary paths of uploaded files
            $image1Path = $_FILES['image1']['tmp_name'];
            $image2Path = $_FILES['image2']['tmp_name'];
            
            // Compare the images
            $result = $imageComparison->compareImages($image1Path, $image2Path);
            
            // Display results
            echo "<h3>Comparison Results:</h3>";
            if (isset($result['error'])) {
                echo "<p style='color: red;'>Error: " . $result['error'] . "</p>";
            } else {
                echo "<p>Similarity: " . $result['similarity'] . "%</p>";
                echo "<p>Match: " . ($result['is_match'] ? "Yes" : "No") . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>