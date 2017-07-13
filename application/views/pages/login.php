<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="left-panel col-md-6">
            <div class="row">
                <?php echo form_open( 'login/logar', [ 'id'=> 'login-form', 'class' => 'col-md-9 col-md-offset-1 fade-in' ] )?>
                    <div class="page-header fade-in">
                        <h3>Login</h3>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input  type="email" 
                                class="form-control" 
                                name="email"
                                required
                                placeholder="joao@contador.com.br">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Senha</label>
                        <input  type="password" 
                                class="form-control" 
                                name="senha"
                                required
                                minlength="6"
                                maxlenth="16"
                                placeholder="******">
                    </div>
                    <?php if ( $view->item( 'error' ) ): ?>
                    <div class="alert alert-danger">
                        <b>Erro ao logar</b>
                        <p><?php echo $view->item( 'error' ); ?></p>
                    </div>
                    <?php endif; ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#">Esqueci minha senha</a>
                            <button class="btn pull-right btn-primary">Entrar</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">Todos os direitos reservados</p>
                        </div>
                    </div>
                <?php echo form_close(); ?>                
            </div>
        </div>
        <div class="right-panel col-md-6 hidden-xs"></div>
    </div>
</div>
