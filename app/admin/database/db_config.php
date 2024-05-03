<?php
// Inclusion des fichiers nécessaires
include '../../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

new DatabaseSetUp($database_configs, DATASET);
DatabaseSetUp::initializeDatabases($database_configs);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.admin.db.css"?> >
    <title>?</title>
  </head>
  <body>
    <div class="counter">
      <div class="nums">
        <span class="in">3</span>
        <span>2</span>
        <span>1</span>
        <span>0</span>
      </div>
      <h4>configuring ...</h4>
    </div>

    <div class="final">
      <h1>CUI CUI !</h1>
      <button id="replay">
        <span>Aller à la page principale</span>
      </button>
        <script>
            // Sélection du bouton
            const redirectButton = document.querySelector('#replay');

            // Ajout d'un écouteur d'événement pour le clic sur le bouton
            redirectButton.addEventListener('click', () => {
                // Redirection vers la page principale
                window.location.href = '<?php echo $appdir['PATH_CUICUI_APP'] ; ?>';
            });
        </script>
    </div>
    <script src=<?php echo $appdir['PATH_JS_DIR'] . "/flip.admin.db.script.js"?>></script>
  </body>
</html>
