<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $finder = $view->item( 'finder' ); ?>
<?php if ( $finder->filters ): ?>
<form id="filter-form" method="get" class="well" style="background: white">

    <h4>Filtros</h4>
    <hr>

    <?php foreach( $_GET as $key => $value ): ?>
    <?php if ( !$finder->isFilter( $key ) ): ?>
    <input type="hidden" value="<?php echo $value; ?>" name="<?php echo $key; ?>">
    <?php endif; ?>
    <?php endforeach; ?>

    <?php foreach( $finder->filters as $filter ): ?>
    <?php if ( $filter['type'] !== 'select' ): ?>
    <div class="col-md-4">
        <div class="form-group">
            <label for="<?php echo $filter['field']; ?>"><?php echo $filter['label']; ?></label>
            <input  type="<?php echo $filter['type']?>" 
                    class="form-control" 
                    name="<?php echo $filter['field']; ?>"
                    value="<?php echo $filter['value'] ? $filter['value'] : ''; ?>"
                    placeholder="<?php echo $filter['label']; ?>">
        </div>
    </div>
    <?php else: ?>
    <div class="col-md-4">
        <div class="form-group">
            <label for="<?php echo $filter['field']; ?>"><?php echo $filter['label']; ?></label>                
            <select name="<?php echo $filter['field']; ?>" class="form-control">
                <option value="">-- Selecione --</option>
                <?php foreach( $filter['data'] as $value => $label ): ?>
                <option value="<?php echo $value; ?>" <?php echo $filter['value'] == $value ? 'selected' : ''; ?>>
                    <?php echo $label; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>          
    <?php endif; ?>                
    <?php endforeach; ?>
    <div class="col-md-12">
        <hr>
        <button id="filter-it" class="btn btn-warning pull-right btn-sm">
            <span class="glyphicon glyphicon-filter"></span>
            Filtrar
        </button>
    </div>
    <div class="clearfix"></div>
</form>
<?php endif; ?>