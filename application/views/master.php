<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $view->getTitle(); ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php foreach( $view->css as $css ): ?>
        <link href="<?php echo $css; ?>" rel="stylesheet" media="screen"/>
        <?php endforeach; ?>
        <script>
            var Site = {
                url: '<?php echo site_url(); ?>'
            };
        </script>
        <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
        <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyABW_WTijXJo0Ob6kIM50DawbY2JE20agw",
            authDomain: "neotass-96310.firebaseapp.com",
            databaseURL: "https://neotass-96310.firebaseio.com",
            projectId: "neotass-96310",
            storageBucket: "neotass-96310.appspot.com",
            messagingSenderId: "121979806569"
        };
        firebase.initializeApp(config);
        </script>
    </head>
    <body>
        <?php $this->load->view( 'pages/'.$view->page ); ?>
        <?php foreach( $view->js as $js ): ?>
        <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    </body>
</html>