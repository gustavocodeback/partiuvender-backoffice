<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $estado = $view->item( 'estado' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'estados/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Novo estado</h2>
        </div>
        <?php if( $estado ): ?>
        <input type="hidden" name="cod" value="<?php echo $estado->CodEstado; ?>">
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
                            value="<?php echo $estado ? $estado->nome : ''; ?>"
                            placeholder="Minas Gerais">
                </div>
            </div>
        </div>

         <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="uf">UF</label>
                    <input  type="text" 
                            class="form-control" 
                            id="uf" 
                            name="uf" 
                            required
                            value="<?php echo $estado ? $estado->uf : ''; ?>"
                            placeholder="MG">
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
        <a href="<?php echo site_url( 'estados' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>