<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $pergunta = $view->item( 'pergunta' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <?php echo form_open( 'perguntas/salvar', [ 'class' => 'card container fade-in' ] )?>
        <?php $view->component( 'breadcrumb' ); ?>        
        <div class="page-header">
            <h2>Nova pergunta</h2>
        </div>
        <?php if( $pergunta ): ?>
        <input type="hidden" name="cod" value="<?php echo $pergunta->CodPergunta; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="estado">Questionário</label>
                            <select id="questionario" name="questionario" class="form-control">
                                <option value="">-- Selecione --</option>
                                <?php foreach( $view->item( 'questionarios' ) as $item ): ?>
                                <option value="<?php echo $item->CodQuestionario?>" 
                                        <?php echo $pergunta && $pergunta->questionario == $item->CodQuestionario ? 'selected="selected"' : ''; ?>>
                                <?php echo $item->nome; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                 </div><!-- input do questionario -->
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="texto">Texto</label>
                            <textarea   class="form-control" 
                                        name="texto" 
                                        id="texto" 
                                        cols="30" 
                                        rows="10"><?php echo $pergunta ? $pergunta->texto : ''; ?></textarea>
                        </div>
                    </div>
                </div><!-- input do texto -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pontos">Pontos</label>
                            <input  type="number" 
                                    class="form-control" 
                                    id="pontos" 
                                    name="pontos" 
                                    required
                                    value="<?php echo $pergunta ? $pergunta->pontos : ''; ?>"
                                    placeholder="0">
                        </div>
                    </div>
                </div><!-- input dos pontos -->
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Alternativas</label>
                            <input  type="text" 
                                    class="form-control" 
                                    name="alternativa1"
                                    value="<?php echo $pergunta ? $pergunta->alternativa1 : ''; ?>"
                                    placeholder="Alternativa 1">
                            <br>
                            <input  type="text" 
                                    class="form-control" 
                                    name="alternativa2"
                                    value="<?php echo $pergunta ? $pergunta->alternativa2 : ''; ?>"
                                    placeholder="Alternativa 2">
                            <br>
                            <input  type="text" 
                                    class="form-control" 
                                    name="alternativa3"
                                    value="<?php echo $pergunta ? $pergunta->alternativa3 : ''; ?>"
                                    placeholder="Alternativa 3">
                            <br>
                            <input  type="text" 
                                    class="form-control" 
                                    name="alternativa4"
                                    value="<?php echo $pergunta ? $pergunta->alternativa4 : ''; ?>"
                                    placeholder="Alternativa 4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pontos">Resposta</label>
                            <input  type="number" 
                                    class="form-control" 
                                    id="resposta" 
                                    name="resposta" 
                                    required
                                    value="<?php echo $pergunta ? $pergunta->resposta : ''; ?>"
                                    placeholder="0">
                        </div>
                    </div>
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
        <a href="<?php echo site_url( 'perguntas' ); ?>" class="btn btn-danger">Cancelar</a>
    <?php echo form_close(); ?> 
</div>