<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $venda = $view->item( 'venda' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'vendas/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova venda</h2>
        </div>
        <?php if( $venda ): ?>
            <input type="hidden" name="cod" value="<?php echo $venda->CodVenda; ?>">
        <?php endif; ?><!-- id -->
        
        <div class="row">
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input  type="text" 
                            class="form-control cpf" 
                            id="cpf" 
                            name="cpf" 
                            required
                            value="<?php echo $venda && $venda->cpf ? mascara_cpf( $venda->cpf ) : ''; ?>"
                            placeholder="999.999.999-99">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" class="form-control"
                    onchange="atualizarSelect( '#produto', 'produtos/obter_produtos_categoria', $( this ) )">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'categorias' ) as $item ): ?>
                        <option value="<?php echo $item->CodCategoria?>" 
                                <?php echo $venda && $venda->categoria == $item->CodCategoria ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="produto">Produto</label>
                    <select id="produto" name="produto" <?php echo $venda && $venda->categoria  ? '' : 'disabled="disabled"'; ?> class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'produtos' ) as $item ): ?>
                        <option value="<?php echo $item->CodProduto?>" 
                                <?php echo $venda && $venda->produto == $item->CodProduto ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="quantidade">Quantidade</label>
                    <input  type="number" 
                            class="form-control" 
                            id="quantidade" 
                            name="quantidade" 
                            required
                            value="<?php echo $venda ? $venda->quantidade : ''; ?>"
                            placeholder="99">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="data">Data</label>
                    <input  type="date" 
                            class="form-control" 
                            id="data" 
                            name="data" 
                            required
                            value="<?php echo $venda ? $venda->data : ''; ?>"
                            placeholder="99">
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
        <a href="<?php echo site_url( 'vendas' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>