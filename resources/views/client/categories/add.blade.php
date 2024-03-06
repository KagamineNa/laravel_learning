<h2>Day la trang them item</h2>
<form action="<?php echo route('categories.add'); ?>?id=1" method="POST">
    <?php echo csrf_field(); ?>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo old('name'); ?>" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo old('email'); ?>" required>
    <br>
    <input type="submit" value="Submit">
</form>

@for ($i = 0; $i < 10; $i++)
    {{ $i }} -
@endfor

@verbatim
    {{ variable }}
    <h1>Day la trang them item {{ age }}</h1>
    <script>
        Hello, {{ name }}
        HI, {{ age }}
    </script>
@endverbatim
