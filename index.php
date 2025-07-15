<?php
// Load functions and fetch data, with optional search
require 'functions.php';
$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$data = getData($conn, $search);
?>

<!DOCTYPE html>
<html>
<head>
    <title>QR Code Generator</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <link rel="stylesheet" href="https://bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- should put this on the style sheet/style.css -->
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h1>QR Code Generator</h1>
<!-- QR Code Generation Form -->
<form action="save.php" method="POST">
    <!-- Title input -->
    <input type="text" name="title" placeholder="Enter Name/Title" required>
    <!-- URL input -->
    <input type="text" name="url" placeholder="Enter URL" required>
    <button type="submit">Generate</button>
</form>

        </div>

        <div class="col-md-8">
            <form method="GET" style="margin-bottom: 32px;">
    <input type="text" name="search" placeholder="Search by title..." value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit">Search</button>
</form>

<hr>

<!-- Saved QR Codes Section -->
<h3>Saved QR Codes</h3>
<?php if (count($data) > 0): ?>
    <ul>
        <?php foreach ($data as $item): ?>
            <li id="qr-item-<?= $item['id'] ?>" style="position:relative;">
                <!-- Header row: favicon, name, edit button -->
                <div class="qr-header-row">
                    <div class="qr-title-row">
                        <strong>Name:</strong>
                        <!-- Favicon for visual identification -->
                        <img src="https://www.google.com/s2/favicons?domain=<?= urlencode(parse_url($item['url'], PHP_URL_HOST)) ?>&sz=32" alt="icon" style="vertical-align:middle;border-radius:4px;box-shadow:0 1px 3px rgba(60,60,100,0.10);">
                        <?= htmlspecialchars($item['title']) ?>
                    </div>
                    <!-- Edit button in upper right -->
                    <button class="edit-btn" title="Edit" onclick="showEditForm(<?= $item['id'] ?>); return false;">
                        <span>✏️</span> <span style="font-size:0.98em;color:#111;">Edit</span>
                    </button>
                </div>
                <!-- Display QR code image -->
                <div class="display-row">
                    <img src="<?= htmlspecialchars($item['qr']) ?>" alt="QR" class="qr-img"><br>
                </div>
                <!-- Edit form, shown only when editing -->
                <form action="update.php" method="POST" class="edit-form">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required placeholder="Edit Name/Title">
                    <input type="text" name="url" value="<?= htmlspecialchars($item['url']) ?>" required placeholder="Edit URL">
                    <button type="submit">Update</button>
                    <button type="button" onclick="hideEditForm(<?= $item['id'] ?>)">Cancel</button>
                </form>
                <!-- Delete button -->
                <form action="delete.php" method="POST" style="display:inline; margin-top: 8px;">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No saved QR codes.</p>
<?php endif; ?>

        </div>

    </div>

</div>


<!-- Search Form -->


<!-- JavaScript for toggling edit mode -->
<script>
// Show the edit form for a specific QR code entry
function showEditForm(id) {
    document.getElementById('qr-item-' + id).classList.add('show-edit');
}
// Hide the edit form for a specific QR code entry
function hideEditForm(id) {
    document.getElementById('qr-item-' + id).classList.remove('show-edit');
}
</script>

</body>
</html>
