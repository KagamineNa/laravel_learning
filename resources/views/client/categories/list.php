<?php
$items = ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5']; // Replace with your own list of items

shuffle($items); // Shuffle the items randomly

ob_start(); // Start output buffering

?>
<h1>Danh sach san pham</h1>
<ul>
    <?php foreach ($items as $item): ?>
        <li>
            <?php echo $item; ?>
        </li>
    <?php endforeach; ?>
</ul>

<?php
$html = ob_get_clean(); // Get the buffered HTML code

echo $html; // Render the HTML code
?>