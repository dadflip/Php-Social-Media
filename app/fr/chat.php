<?php 
    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);

    if(isset($_GET['user'])) {
        $type = 'default';
        $username = $_GET['user'];
        $destId = $cuicui_manager->getIdByUsername($username);
        $users = array(
            'names' => array($username),
            'ids' => array($destId)
        );
    } else if (isset($_GET['group'])) {
        $type = 'group';
        $usernames = explode("+", $_GET['group']);
        $userIds = [];
        foreach ($usernames as $username) {
            $userId = $cuicui_manager->getIdByUsername($username);
            $userIds[] = $userId;
        }
        $users = array(
            'names' => $usernames,
            'ids' => $userIds
        );
    } else {
        echo 'no discussion selected';
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chat | Cuicui App</title>

        <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <?php
            echo createTitleBar("@Chat");
        ?>
        <main class="container">
            <div class="chat-container">
                <div class="chat-messages" id="chatMessages">
                    <!-- Messages will be added here -->
                </div>
                
                <input type="text" id="messageInput" placeholder="Tapez votre message...">
                <button id="sendButton">Envoyer</button>
            </div>
        </main>
    </body>

    <script>
        window.__ajx__ = atob("<?php echo base64_encode($appdir['PATH_PHP_DIR'] . '/ajax/main/'); ?>");
    </script>

    <script>
        /**@description
         *  Checked and corrected with A.I. 
         * */

        $(document).ready(function(){
            loadMessages();

            setInterval(loadMessages, 5000);

            $("#sendButton").click(function(){
                sendMessage();
            });
        });

        function loadMessages(){
            $.ajax({
                url: window.__ajx__ + "chat/loadMessages.php",
                method: "GET",
                success: function(data){
                    $("#chatMessages").html(data);
                }
            });
        }

        function sendMessage() {
            var message = $("#messageInput").val();
            if (message != "") {
                var type = "<?php echo $type; ?>";
                var destId = <?php echo json_encode($users); ?>;

                if (Array.isArray(destId.ids)) {
                    destId.ids.forEach(function(id) {
                        sendAjaxRequest(message, type, id);
                    });
                } else {
                    sendAjaxRequest(message, type, destId.ids);
                }
            }
        }

        function sendAjaxRequest(message, type, destId) {
            $.ajax({
                url: window.__ajx__ + "chat/sendMessage.php",
                method: "POST",
                data: { message: message, type: type, destId: destId },
                success: function(response) {
                    console.log(response);
                    $("#messageInput").val("");
                    loadMessages();
                }
            });
        }
    </script>
</html>
