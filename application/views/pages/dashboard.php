<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $view->component( 'aside' ); ?>
<div id="wrapper" class="wrapper show">
    <?php $view->component( 'navbar' ); ?>
    <div class="container"> 
        <div class="row"><br></div>
        <div class="row">
            
            <div class="col-md-4">
                <div class="panel panel-default panel-primary">
                    <div class="panel-heading">
                        <h1 class="text-center">
                            <span class="glyphicon glyphicon-user"></span>
                        </h1>
                        <h1 class="text-center">
                            <?php echo $view->item( 'num_func' ); ?>
                        </h1>
                        <h4 class="text-center">Funcionários</h4>
                        <h6 class="text-center">Cadastrados no sistema</h6>
                    </div>
                </div>
            </div><!-- num funcionarios -->

            <div class="col-md-4">
                <div class="panel panel-default panel-primary">
                    <div class="panel-heading">
                        <h1 class="text-center">
                            <span class="glyphicon glyphicon-phone"></span>
                        </h1>
                        <h1 class="text-center">
                            <?php echo $view->item( 'num_func_logado' ); ?>
                        </h1>
                        <h4 class="text-center">Funcionários</h4>
                        <h6 class="text-center">Cadastrados no aplicativo</h6>
                    </div>
                </div>
            </div><!-- num funcionarios -->

            <div class="col-md-4">
                <div class="panel panel-default panel-primary">
                    <div class="panel-heading">
                        <h1 class="text-center">
                            <span class="glyphicon glyphicon-home"></span>
                        </h1>
                        <h1 class="text-center">
                            <?php echo $view->item( 'num_lojas' ); ?>
                        </h1>
                        <h4 class="text-center">Lojas</h4>
                        <h6 class="text-center">Cadastrados no sistema</h6>
                    </div>
                </div>
            </div><!-- num funcionarios -->

        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Ranking vendedores</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            <?php  $i = 0; foreach( $view->item( 'clusters' ) as $key => $cluster ): ?>
            <div class="col-md-6">
                <div class="page-header">
                    <h5><?php echo $key ; ?></h5>
                </div>
                <div id="myChart<?php echo $i; ?>"></div>
            </div>
            <?php $i++; endforeach;  ?>  
            </div>          
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Ranking gerentes</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            <?php  foreach( $view->item( 'lojas' ) as $key => $cluster ): ?>
            <div class="col-md-6">
                <div class="page-header">
                    <h5><?php echo $key ; ?></h5>
                </div>
                <div id="myChart<?php echo $i; ?>"></div>
            </div>
            <?php $i++; endforeach;  ?>  
            </div>          
        </div> 

    </div>    
</div>

<script>

// datasets
var datasets = [];

<?php foreach( $view->item( 'clusters' ) as $item ): ?>
datasets.push( <?php echo json_encode( $item ); ?>)
<?php endforeach; ?>
<?php foreach( $view->item( 'lojas' ) as $item ): ?>
datasets.push( <?php echo json_encode( $item ); ?>)
<?php endforeach; ?>
console.log( datasets );
</script>
