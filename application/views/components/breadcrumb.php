<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $parts = $this->uri->segment_array(); ?>
<div class="row">
    <div class="col-md-12">
        <br>
        <ol class="breadcrumb z-depth-1" style="background: white">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Inicio</a></li>
            <?php foreach( $parts as $key => $part ): ?>
            <?php if ( $part !== 'index' ): ?>
            <?php
            
                $url = '';
                for( $i = 1; $i <= $key; $i++ ) {
                    $url .= '/'.$parts[$i];
                }            
            ?>
            <li class="breadcrumb-item"><a href="<?php echo site_url( $url ); ?>"><?php echo $part; ?></a></li>            
            <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</div>