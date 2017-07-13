<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $rotina = $view->item( 'rotina' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'rotinas/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova rotina</h2>
        </div>
        <?php if( $rotina ): ?>
        <input type="hidden" name="cod" value="<?php echo $rotina->rid; ?>">
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rotina">Rotina</label>
                    <input  type="text" 
                            class="form-control" 
                            id="rotina" 
                            name="rotina" 
                            required
                            value="<?php echo $rotina ? $rotina->rotina : ''; ?>"
                            placeholder="Sistema">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input  type="text" 
                            class="form-control" 
                            id="link" 
                            name="link" 
                            required
                            value="<?php echo $rotina ? $rotina->link : ''; ?>"
                            placeholder="meu_link/adicionar">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rotina">Classificação</label>
                    <select name="classificacao" class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'class' ) as $item ): ?>
                        <option value="<?php echo $item->CodClassificacao?>" 
                                <?php echo $rotina && $rotina->classificacao == $item->CodClassificacao ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
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
        <a href="<?php echo site_url( 'rotinas' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>