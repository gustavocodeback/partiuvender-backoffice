<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $loja = $view->item( 'loja' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'lojas/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova loja</h2>
        </div>
        <?php if( $loja ): ?>
        <input type="hidden" name="cod" value="<?php echo $loja->CodLoja; ?>">
        <?php endif; ?><!-- id -->

        <div class="row">
            <div class="col-md-12">
                 <div class="form-group">
                    <label for="nome">Nome</label>
                    <input  type="text" 
                            class="form-control" 
                            id="nome" 
                            name="nome" 
                            required
                            value="<?php echo $loja ? $loja->nome : ''; ?>"
                            placeholder="Loja">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <input  type="text" 
                            class="form-control cnpj" 
                            id="cnpj" 
                            name="cnpj" 
                            required
                            value="<?php echo $loja ? mascara_cnpj( $loja->cnpj ) : ''; ?>"
                            placeholder="99.999.999/9999-99">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="razao">Razão</label>
                    <input  type="text" 
                            class="form-control" 
                            id="razao" 
                            name="razao" 
                            required
                            value="<?php echo $loja ? $loja->razao : ''; ?>"
                            placeholder="Empresa">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="cluster">Cluster</label>
                    <select id="cluster" name="cluster" class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'clusters' ) as $item ): ?>
                        <option value="<?php echo $item->CodCluster?>" 
                                <?php echo $loja && $loja->cluster == $item->CodCluster ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" class="form-control"
                    onchange="atualizarSelect( '#cidade', 'lojas/obter_cidades_estado', $( this ) )">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'estados' ) as $item ): ?>
                        <option value="<?php echo $item->CodEstado?>" 
                                <?php echo $loja && $loja->estado == $item->CodEstado ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->uf; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <select id="cidade" name="cidade" <?php echo $loja && $loja->cidade  ? '' : 'disabled="disabled"'; ?> class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'cidades' ) as $item ): ?>
                        <option value="<?php echo $item->CodCidade?>" 
                                <?php echo $loja && $loja->cidade == $item->CodCidade ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <input  type="text" 
                            class="form-control" 
                            id="bairro" 
                            name="bairro" 
                            required
                            value="<?php echo $loja ? $loja->bairro : ''; ?>"
                            placeholder="Bairro">
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input  type="text" 
                            class="form-control" 
                            id="endereco" 
                            name="endereco" 
                            required
                            value="<?php echo $loja ? $loja->endereco : ''; ?>"
                            placeholder="Rua das Laranjeiras">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="numero">Número</label>
                    <input  type="text" 
                            class="form-control" 
                            id="numero" 
                            name="numero" 
                            required
                            value="<?php echo $loja ? $loja->numero : ''; ?>"
                            placeholder="99">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input  type="text" 
                            class="form-control" 
                            id="complemento" 
                            name="complemento" 
                            required
                            value="<?php echo $loja ? $loja->complemento : ''; ?>"
                            placeholder="Rua das Laranjeiras">
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
        <a href="<?php echo site_url( 'lojas' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>