<?php 
require_once('defs.paths.php');
?>

<form method="post">
    <label for="path">Choose a path :</label>
    <select name="path" id="path">
        <?php
foreach ($path_manager->getNormalizedPaths() as $variable => $value) : ?>
            <option value="<?php
echo $value; ?>"><?php
echo $variable; ?></option>
        <?php
endforeach; ?>
    </select>
    <button type="submit">Test</button>
</form>
