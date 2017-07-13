<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $classificacao = $view->item( 'classificacao' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'classificacoes/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova classificacao</h2>
        </div>
        <?php if( $classificacao ): ?>
        <input type="hidden" name="cod" value="<?php echo $classificacao->CodClassificacao; ?>">
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
                            value="<?php echo $classificacao ? $classificacao->nome : ''; ?>"
                            placeholder="ADM">
                </div>
            </div>
        </div><!-- input do nome -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="classificacao">Icone</label>
                    <input  type="text" 
                            class="form-control" 
                            id="icone" 
                            name="icone" 
                            required
                            value="<?php echo $classificacao ? $classificacao->icone : ''; ?>"
                            placeholder="Contador">
                </div>
            </div>
        </div><!-- input do icone -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="classificacao">Ordem</label>
                    <input  type="text" 
                            class="form-control" 
                            id="ordem" 
                            name="ordem" 
                            required
                            value="<?php echo $classificacao ? $classificacao->ordem : ''; ?>"
                            placeholder="1">
                </div>
            </div>
        </div><!-- input da ordem -->
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
        <a href="<?php echo site_url( 'classificacoes' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>