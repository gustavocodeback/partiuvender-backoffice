<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $produto = $view->item( 'produto' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open_multipart( 'produtos/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Novo produto</h2>
        </div>
        <?php if( $produto ): ?>
        <input type="hidden" name="cod" value="<?php echo $produto->CodProduto; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input  type="text" 
                            class="form-control" 
                            id="nome" 
                            name="nome" 
                            required
                            value="<?php echo $produto ? $produto->nome : ''; ?>"
                            placeholder="Galaxy S8">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="pontos">Pontos</label>
                    <input  type="number" 
                            class="form-control" 
                            id="pontos" 
                            name="pontos" 
                            required
                            value="<?php echo $produto ? $produto->pontos : 0; ?>"
                            placeholder="99">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'categorias' ) as $item ): ?>
                        <option value="<?php echo $item->CodCategoria?>" 
                                <?php echo $produto && $produto->categoria == $item->CodCategoria ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="foto">Foto</label>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if( $produto ): ?>
                                <img src="<?php echo base_url( 'uploads/'.$produto->foto )?>" class="img-thumbnail" style="width: 100px; height: 100px;">  
                            <?php endif; ?>
                        </div>
                    </div>
                        <input  type="file" 
                                class="form-control" 
                                id="foto" 
                                name="foto" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="video">Video</label>
                    <input  type="url" 
                            class="form-control" 
                            id="video" 
                            name="video" 
                            required
                            value="<?php echo $produto ? $produto->video : ''; ?>"
                            placeholder="Link">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea rows="3" cols="" type="text" 
                            class="form-control" 
                            id="descricao" 
                            name="descricao" 
                            required                            
                            placeholder=""><?php echo $produto ? $produto->descricao : ''; ?></textarea>
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
        <a href="<?php echo site_url( 'categorias' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>