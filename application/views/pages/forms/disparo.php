<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $disparo = $view->item( 'disparo' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'disparos/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Novo disparo</h2>
        </div>
        <?php if( $disparo ): ?>
        <input type="hidden" name="cod" value="<?php echo $disparo->CodDisparo; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="notificacao">Notificacao</label>
                    <select id="notificacao" name="notificacao" class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'notificacoes' ) as $item ): ?>
                        <option value="<?php echo $item->CodNotificacao?>" 
                                <?php echo $disparo && $disparo->notificacao == $item->CodNotificacao ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="grupo">Grupo</label>
                    <select id="grupo" name="grupo" class="form-control">
                        <option value="">-- Selecione --</option>
                        <option value="Todos" <?php echo $disparo && $disparo->grupo == "Todos" ? 'selected="selected"' : ''; ?>>
                            Todos
                        </option>
                        <option value="Vendedor" <?php echo $disparo && $disparo->grupo == "Vendedor" ? 'selected="selected"' : ''; ?>>
                            Vendedor
                        </option>
                        <option value="Gerente" <?php echo $disparo && $disparo->grupo == "Gerente" ? 'selected="selected"' : ''; ?>>
                            Gerente e Sub-Gerente
                        </option>
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
        <a href="<?php echo site_url( 'disparos' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>