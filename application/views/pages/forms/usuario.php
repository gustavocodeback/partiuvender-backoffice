<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $usuario = $view->item( 'usuario' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'usuarios/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova usuario</h2>
        </div>
        <?php if( $usuario ): ?>
        <input type="hidden" name="cod" value="<?php echo $usuario->uid; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rotina">E-mail</label>
                    <input  type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            required
                            value="<?php echo $usuario ? $usuario->email : ''; ?>"
                            placeholder="usuario@email.com">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="link">Senha</label>
                    <input  type="password" 
                            class="form-control" 
                            id="senha" 
                            name="senha" 
                            required
                            value="<?php echo $usuario ? $usuario->senha : ''; ?>"
                            placeholder="******">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rotina">Cargos</label>
                    <select name="gid" class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'grupos' ) as $item ): ?>
                        <option value="<?php echo $item->gid?>" 
                                <?php echo $usuario && $usuario->gid == $item->gid ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->grupo; ?></option>
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
        <a href="<?php echo site_url( 'usuarios' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>