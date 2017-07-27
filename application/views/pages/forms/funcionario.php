<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $funcionario = $view->item( 'funcionario' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'funcionarios/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova funcionário</h2>
        </div>
        <?php if( $funcionario ): ?>
        <input type="hidden" name="cod" value="<?php echo $funcionario->CodFuncionario; ?>">
        <?php endif; ?><!-- id -->

        <div class="row">
            <div class="col-md-6">
                 <div class="form-group">
                    <label for="nome">Nome</label>
                    <input  type="text" 
                            class="form-control" 
                            id="nome" 
                            name="nome" 
                            required
                            value="<?php echo $funcionario ? $funcionario->nome : ''; ?>"
                            placeholder="Roberto">
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
                            value="<?php echo $funcionario ? $funcionario->pontos : 0; ?>"
                            placeholder="99">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                 <div class="form-group">
                    <label for="email">E-mail</label>
                    <input  type="text" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            value="<?php echo $funcionario ? $funcionario->email : ''; ?>"
                            placeholder="Roberto">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-2">
                 <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input  type="text" 
                            class="form-control cpf" 
                            id="cpf" 
                            name="cpf" 
                            required
                            value="<?php echo $funcionario ? mascara_cpf( $funcionario->cpf ) : ''; ?>"
                            placeholder="999.999.999-99">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="cargo">Cargo</label>
                    <select id="cargo" name="cargo" class="form-control">
                        <option value="">-- Selecione --</option>
                        <option value="Vendedor" <?php echo $funcionario && $funcionario->cargo == "Vendedor" ? 'selected="selected"' : ''; ?>>
                            Vendedor
                        </option>
                        <option value="Gerente" <?php echo $funcionario && $funcionario->cargo == "Gerente" ? 'selected="selected"' : ''; ?>>
                            Gerente
                        </option>
                        <option value="Sub-Gerente" <?php echo $funcionario && $funcionario->cargo == "Sub-Gerente" ? 'selected="selected"' : ''; ?>>
                            Sub-Gerente
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="loja">Loja</label>
                    <select id="loja" name="loja" class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'lojas' ) as $item ): ?>
                        <option value="<?php echo $item->CodLoja?>" 
                                <?php echo $funcionario && $funcionario->loja == $item->CodLoja ? 'selected="selected"' : ''; ?>>
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
                    onchange="atualizarSelect( '#cidade', 'cidades/obter_cidades_estado', $( this ) )">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'estados' ) as $item ): ?>
                        <option value="<?php echo $item->CodEstado?>" 
                                <?php echo $funcionario && $funcionario->estado == $item->CodEstado ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->uf; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <select id="cidade" name="cidade" <?php echo $funcionario && $funcionario->cidade  ? '' : 'disabled="disabled"'; ?> class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach( $view->item( 'cidades' ) as $item ): ?>
                        <option value="<?php echo $item->CodCidade?>" 
                                <?php echo $funcionario && $funcionario->cidade == $item->CodCidade ? 'selected="selected"' : ''; ?>>
                        <?php echo $item->nome; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input  type="text" 
                            class="form-control cep" 
                            id="cep" 
                            name="cep" 
                            value="<?php echo $funcionario ? $funcionario->cep : ''; ?>"
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
                            value="<?php echo $funcionario ? $funcionario->endereco : ''; ?>"
                            placeholder="Rua das Laranjeiras">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="numero">Número</label>
                    <input  type="number" 
                            class="form-control" 
                            id="numero" 
                            name="numero" 
                            value="<?php echo $funcionario ? $funcionario->numero : ''; ?>"
                            placeholder="99">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input  type="text" 
                            class="form-control" 
                            id="complemento" 
                            name="complemento" 
                            value="<?php echo $funcionario ? $funcionario->complemento : ''; ?>"
                            placeholder="apartamento">
                </div>
            </div>
            
            <div class="col-md-2">
                 <div class="form-group">
                    <label for="rg">RG</label>
                    <input  type="text" 
                            class="form-control rg" 
                            id="rg" 
                            name="rg" 
                            value="<?php echo $funcionario && $funcionario->rg ? mascara_rg( $funcionario->rg ) : ''; ?>"
                            placeholder="99.999.999-9">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="celular">Celular</label>
                    <input  type="text" 
                            class="form-control telefone" 
                            id="celular" 
                            name="celular" 
                            value="<?php echo $funcionario && $funcionario->celular ? mascara_telefone( $funcionario->celular ) : ''; ?>"
                            placeholder="(xx) 99999-9999">
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
        <a href="<?php echo site_url( 'funcionarios' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>