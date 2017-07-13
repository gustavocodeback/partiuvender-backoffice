<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'permissoes/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Permissōes de acesso</h2>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td></td>
                    <?php foreach( $view->item( 'cargos' ) as $item ): ?>
                    <td><?php echo $item->grupo; ?></td>
                    <?php endforeach;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $view->item( 'rotinas' ) as $item ): ?>
                    <tr>
                        <td><?php echo $item->rotina; ?></td>
                        <?php foreach( $view->item( 'cargos' ) as $cargo ): ?>
                        <td>
                            <input  type="checkbox" 
                                    name="permissoes[]" 
                                    <?php echo $view->hasAccess( $item->rid, $cargo->gid ) ? 'checked="checked"' : ''; ?>
                                    value="<?php echo $item->rid.'_'.$cargo->gid; ?>">
                        </td>
                        <?php endforeach;?>                       
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary">Salvar</button>
            </div>
        </div>
    <?php echo form_close(); ?> 
</div>