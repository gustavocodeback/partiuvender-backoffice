<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $notificacao = $view->item( 'notificacao' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open_multipart( 'notificacoes/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova notificação</h2>
        </div>
        <?php if( $notificacao ): ?>
        <input type="hidden" name="cod" value="<?php echo $notificacao->CodNotificacao; ?>">
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
                            value="<?php echo $notificacao ? $notificacao->nome : ''; ?>"
                            placeholder="Campanha">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="notificacao">Imagem</label>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if( $notificacao ): ?>
                                <img src="<?php echo base_url( 'uploads/'.$notificacao->notificacao )?>" class="img-thumbnail" style="width: 100px; height: 100px;">  
                            <?php endif; ?>
                        </div>
                    </div>
                        <input  type="file" 
                                class="form-control" 
                                id="notificacao" 
                                name="foto" >
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="texto">Texto</label>
                    <textarea rows="3" cols="" type="text" 
                            class="form-control" 
                            id="texto" 
                            name="texto" 
                            required                            
                            placeholder=""><?php echo $notificacao ? $notificacao->texto : ''; ?></textarea>
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
        <a href="<?php echo site_url( 'notificacoes' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>