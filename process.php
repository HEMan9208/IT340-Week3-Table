<?php
// Week 8 PHP array assignment
// Print each array both without and with its indexes/keys.
function displayArray(string $heading, array $items): void {
    echo '<section>';
    echo '<h2>' . htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') . '</h2>';
    echo '<h3>Without keys / indexes</h3><p>';
    echo htmlspecialchars(implode(', ', $items), ENT_QUOTES, 'UTF-8');
    echo '</p>';
    echo '<h3>With keys / indexes</h3><ul>';
    foreach ($items as $key => $value) {
        echo '<li>[' . htmlspecialchars((string)$key, ENT_QUOTES, 'UTF-8') . '] = '
            . htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo '</ul></section>';
}

$input = $_POST['values'] ?? null;
$valid = is_array($input) && count($input) === 8;
if ($valid) {
    foreach ($input as $value) {
        if (!is_scalar($value) || trim((string)$value) === '' || !is_numeric($value)) {
            $valid = false;
            break;
        }
    }
}
if ($valid) {
    // Array keys start at 0; store numeric values for numeric sorting.
    $original = array_map('floatval', array_values($input));

    $deleted = $original;
    unset($deleted[1], $deleted[4]); // Delete two elements, leaving gaps in keys.

    $reindexed = array_values($deleted); // Remove the gaps.

    $ascending = $reindexed;
    sort($ascending, SORT_NUMERIC); // Ascending values, reset numeric keys.

    $descending = $reindexed;
    rsort($descending, SORT_NUMERIC); // Descending values, reset numeric keys.

    $ascendingKeepKeys = $deleted;
    asort($ascendingKeepKeys, SORT_NUMERIC); // Ascending values, preserve original keys.

    $ascendingKeys = $ascendingKeepKeys;
    ksort($ascendingKeys, SORT_NUMERIC); // Ascending keys, preserve key/value pairs.
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week 8 - Array Results</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f6fb; color: #172338; margin: 0; padding: 30px 16px; }
        main { max-width: 800px; margin: auto; background: white; padding: 28px; border-radius: 12px; box-shadow: 0 3px 14px #00000012; }
        h1 { color: #154480; margin-top: 0; }
        h2 { font-size: 1.2rem; color: #154480; margin-bottom: 8px; }
        h3 { font-size: 0.95rem; margin-bottom: 4px; }
        section { border-top: 1px solid #dce4ee; padding: 12px 0; }
        li { margin: 4px 0; }
        a { color: #154480; }
    </style>
</head>
<body>
<main>
    <h1>PHP Array Results</h1>
    <?php if (!$valid): ?>
        <p>Please return to the form and submit exactly eight valid numbers.</p>
    <?php else: ?>
        <?php
        displayArray('1. Original array', $original);
        displayArray('2. After deleting two elements (indexes 1 and 4)', $deleted);
        displayArray('3. After removing gaps in indexes', $reindexed);
        displayArray('4. Sorted ascending by value (sort)', $ascending);
        displayArray('5. Sorted descending by value (rsort)', $descending);
        displayArray('6. Sorted ascending by value, keeping keys (asort)', $ascendingKeepKeys);
        displayArray('7. Sorted ascending by key (ksort)', $ascendingKeys);
        ?>
    <?php endif; ?>
    <p><a href="index.html">Back to input form</a></p>
</main>
</body>
</html>
