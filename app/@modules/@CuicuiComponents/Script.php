<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Wow.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>


<script src=<?php echo $GLOBALS['normalized_paths']['PATH_JS_DIR'] . "/routes.js" ?>></script>
<script src=<?php echo $GLOBALS['normalized_paths']['PATH_JS_DIR'] . "/index.js" ?>></script>
<script src=<?php echo $GLOBALS['normalized_paths']['PATH_JS_DIR'] . "/flip.js" ?>></script>
<script src=<?php echo $GLOBALS['normalized_paths']['PATH_JS_DIR'] . "/options.js" ?>></script>

<script>
    window.__app_url__ = atob("<?php echo base64_encode($GLOBALS["normalized_paths"]["PATH_CUICUI_APP"] . "/" . $GLOBALS["LANG"]); ?>");
    window.__u_url__ = atob("<?php echo base64_encode($GLOBALS["normalized_paths"]["PATH_CUICUI_APP"] . "/" . $GLOBALS["LANG"] . $GLOBALS["php_files"]["user"]); ?>");
    window.__ajx__ = atob("<?php echo base64_encode($GLOBALS["normalized_paths"]['PATH_PHP_DIR'] . '/ajax/main/'); ?>");
    window.__u__ = atob("<?php if(isset($_SESSION['UID'])){echo base64_encode($_SESSION['UID']);} ?>");
    window.__img_u__ = atob("<?php if(isset($_SESSION['pfp_url'])){ echo base64_encode($_SESSION['pfp_url']);} ?>");
</script>

<?php
    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
?>
<script>
    window.__admin_but__ = true;
</script>
<?php
    } else { 
?>
<script>
    window.__admin_but__ = false;
</script>
<?php 
    } 
?>