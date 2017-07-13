<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $questionario = $view->item( 'questionario' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open_multipart( 'questionarios/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Novo questionario</h2>
        </div>
        <?php if( $questionario ): ?>
        <input type="hidden" name="cod" value="<?php echo $questionario->CodQuestionario; ?>">
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
                            value="<?php echo $questionario ? $questionario->nome : ''; ?>"
                            placeholder="Galaxy S8">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="foto">Foto</label>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if( $questionario ): ?>
                                <img src="<?php echo base_url( 'uploads/'.$questionario->foto )?>" class="img-thumbnail" style="width: 100px; height: 100px;">  
                            <?php endif; ?>
                        </div>
                    </div>
                        <input  type="file" 
                                class="form-control" 
                                id="foto" 
                                name="foto" >
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea rows="3" cols="" type="text" 
                            class="form-control" 
                            id="descricao" 
                            name="descricao" 
                            required                            
                            placeholder=""><?php echo $questionario ? $questionario->descricao : ''; ?></textarea>
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
        <a href="<?php echo site_url( 'questionarios' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>