<?php if ( $view->getHeader( 'grid' ) ): ?>
<?php $finder = $view->item( 'finder' ); ?>
<table class="table table-bordered z-depth-2" style="background: white">
    <thead>
        <tr>
            <?php foreach( $view->getHeader( 'grid' ) as $row ): ?>
            <?php echo $finder->order_link( $row ); ?>
            <?php endforeach;?>
        </tr>
    </thead>
    <tbody>
        <?php foreach( $view->item( 'grid' ) as $row ): ?>
            <tr>
                <?php foreach( $row as $key => $item ): ?>
                <td><?php echo $finder->apply( $key, $row ); ?></td>
                <?php endforeach; ?>
            </tr>                    
        <?php endforeach; ?>
    </tbody>
    <tfooter>
        <tr>
            <th colspan="<?php echo count( $view->getHeader( 'grid' ) ); ?>">
                Total <?php echo $finder->count; ?>
            </th>
        </tr>
        <?php if ( $finder->perPage < $finder->count ): ?>
        <tr>
            <th class="row" colspan="<?php echo count( $view->getHeader( 'grid' ) ); ?>">
                <div class="center-block" style="width: 200px">
                    <?php $finder->create_links(); ?>                                                            
                </div>
            </th>
        </tr>
        <?php endif; ?>
    </tfooter>
</table>
<?php else: ?>
<p>Nenhum resultado encontrado</p>
<?php endif; ?>