<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $parametro = $view->item( 'parametro' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'parametros/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Novo parametro</h2>
        </div>
        <?php if( $parametro ): ?>
        <input type="hidden" name="cod" value="<?php echo $parametro->CodParametro; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input  type="text" 
                            class="form-control" 
                            id="nome" 
                            name="nome" 
                            required
                            value="<?php echo $parametro ? $parametro->nome : ''; ?>"
                            placeholder="Cor">
                </div>
            </div>
        </div>

         <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="valor">Valor</label>
                    <input  type="text" 
                            class="form-control" 
                            id="valor" 
                            name="valor" 
                            required
                            value="<?php echo $parametro ? $parametro->valor : ''; ?>"
                            placeholder="Vermelho">
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
        <a href="<?php echo site_url( 'parametros' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>