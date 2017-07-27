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
    </head>
    <body>
        <?php $this->load->view( 'pages/'.$view->page ); ?>
        <?php foreach( $view->js as $js ): ?>
        <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    </body>
</html>