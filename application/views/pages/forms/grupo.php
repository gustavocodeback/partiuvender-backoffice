<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $grupo = $view->item( 'grupo' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'grupos/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Novo grupo</h2>
        </div>
        
        <?php if( $grupo ): ?>
        <input type="hidden" name="cod" value="<?php echo $grupo->gid; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="grupo">Grupo</label>
                    <input  type="text" 
                            class="form-control" 
                            id="grupo" 
                            name="grupo" 
                            required
                            value="<?php echo $grupo ? $grupo->grupo : ''; ?>"
                            placeholder="Administrador">
                </div>
            </div>
        </div>
        <?php if( $view->item( 'errors' ) ): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <b>Erro ao salvar</b>
                    <p><?php echo $view->item( 'errors' ); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <hr>
        <button class="btn btn-primary">Salvar</button>
        <a href="<?php echo site_url( 'grupos' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>