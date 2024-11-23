<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input data from the form
    $inputData = $_POST["inputData"];

    // Split by new lines and trim whitespace
    $items = array_filter(array_map('trim', explode("\n", $inputData)));

    // Join items with commas
    $outputData = implode(", ", $items);

    // Redirect back to index.php with the result
    header("Location: index.php?output=" . urlencode($outputData));
    exit();
}
?>
