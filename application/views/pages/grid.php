<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $finder = $view->item( 'finder' ); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>

    <div class="container">
        <?php $view->component( 'breadcrumb' ); ?>        
         <div class="row fade-in">
            <div class="col-md-12">
                <?php $view->component( 'filters' ); ?>
            </div>
        </div>
        <?php if ( $view->item( 'errors' ) ): ?>
        <div class="row fade-in">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <?php echo $view->item( 'errors' ); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="row margin fade-in">
            
            <?php if ( $view->item( 'add_url' ) ): ?>        
            <div class="col-md-6">
                <a href="<?php echo $view->item( 'add_url' ); ?>" class="btn btn-primary z-depth-2">Adicionar</a> 
            </div>
            <?php endif; ?>

            <?php if ( $view->item( 'import_url' ) ): ?>
            <?php echo form_open_multipart( $view->item( 'import_url' ), [  'id' => 'import-form', 'class' => 'col-md-6 text-right' ] ); ?>
                <input  id="planilha" 
                        name="planilha" 
                        onchange="importarPlanilha( $( this ) )" 
                        class="planilha hidden" 
                        type="file">
                <label for="planilha" class="btn btn-success z-depth-2">
                    Importar planilha
                </label> 
            <?php echo form_close(); ?>
            <?php endif; ?>
            
            <div class="col-md-12"><hr></div>
        </div>
        
        <div class="row fade-in">
            <div class="col-md-12">
                <?php $view->component( 'table' ); ?>            
            </div>
        </div>  
    </div>   
</div>

<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
    <?php $cont = 1; ?>
    <?php foreach( $view->getHeader( 'grid' ) as $row ): ?>
    td:nth-of-type(<?php echo $cont; ?>):before { content: "<?php echo $finder->getLabel( $row ); ?>"; }
    <?php $cont++;?>    
    <?php endforeach;?>
}
</style>