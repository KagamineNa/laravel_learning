<h1>Upload File Here</h1>
<form action="<?php echo route("categories.upload") ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <label for="file">Choose a file:</label>
    <input type="file" name="file" id="file">
    <input type="submit" value="Upload">
</form>