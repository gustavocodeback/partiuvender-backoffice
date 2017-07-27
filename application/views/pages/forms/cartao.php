<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $cartao = $view->item( 'cartao' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'cartoes/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova cartao</h2>
        </div>
        <?php if( $cartao ): ?>
            <input type="hidden" name="cod" value="<?php echo $cartao->CodCartao; ?>">
        <?php endif; ?><!-- id -->
        
        <div class="row">
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="codigo">Codigo</label>
                    <input  type="text" 
                            class="form-control" 
                            id="codigo" 
                            name="codigo"
                            required
                            <?php echo $cartao && $cartao->codigo && $cartao->status != 'A' ? 'disabled="disabled"' : ''; ?>
                            value="<?php echo $cartao && $cartao->codigo ? $cartao->codigo : ''; ?>"
                            placeholder="CODIGO CARTAO">
                </div>
            </div>
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input  type="text" 
                            class="form-control cpf" 
                            id="cpf" 
                            name="cpf" 
                            <?php echo $cartao && $cartao->cpf && $cartao->status != 'A' ? 'disabled="disabled"' : ''; ?>
                            value="<?php echo $cartao && $cartao->cpf ? mascara_cpf( $cartao->cpf ) : ''; ?>"
                            placeholder="999.999.999-99">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" required name="status" class="form-control">
                        <option value="">-- Selecione --</option>
                        <option value="A" <?php echo $cartao && $cartao->status == "A" ? 'selected="selected"' : ''; ?>>
                            Aberto
                        </option>
                        <option value="U" <?php echo $cartao && $cartao->status == "U" ? 'selected="selected"' : ''; ?>>
                            Usado
                        </option>
                        <option value="D" <?php echo $cartao && $cartao->status == "D" ? 'selected="selected"' : ''; ?>>
                            Debitado
                        </option>                    
                        <option value="C" <?php echo $cartao && $cartao->status == "C" ? 'selected="selected"' : ''; ?>>
                            Cancelado
                        </option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="valor">Valor</label>
                    <input  type="number" step="0.01"
                            class="form-control" 
                            id="valor" 
                            name="valor" 
                            required
                            
                            <?php echo $cartao && $cartao->valor && $cartao->status != 'A' ? 'disabled="disabled"' : ''; ?>
                            value="<?php echo $cartao ? $cartao->valor : ''; ?>"
                            placeholder="99,99">
                </div>
            </div>
        </div>

        <?php if( $cartao  && !$cartao->data ) : ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="data">Data</label>
                        <input  type="date" 
                                class="form-control" 
                                id="data" 
                                name="data"
                                value="<?php echo $cartao ? $cartao->data : ''; ?>">
                    </div>
                </div>
            </div>        
        <?php endif; ?><!-- id -->

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
        <a href="<?php echo site_url( 'cartoes' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>