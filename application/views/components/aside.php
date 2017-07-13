<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="aside" class="z-depth-2 show">
    <div class="row visible-xs visible-sm">
        <div class="col-md-12">
            <button style="margin: 5px" class="btn btn-default pull-right" onclick="toggleSideBar()">
                Fechar
            </button>
        </div>
    </div>
    <div class="aside-header"></div>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php foreach( $view->getMenu() as $item ): ?>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading<?php echo $item['Nome']; ?>">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $item['Nome']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $item['Nome']; ?>">
            <span class="glyphicon glyphicon-<?php echo $item['Icone']; ?>"></span> &nbsp; <?php echo $item['Nome']; ?>
            </a>
        </h4>
        </div>
        <div id="collapse<?php echo $item['Nome']; ?>" class="panel-collapse collapse <?php echo $item['active'] ? 'in' : ''; ?>" role="tabpanel" aria-labelledby="heading<?php echo $item['Nome']; ?>">
        <ul class="list-group">
                <?php foreach( $item['rotinas'] as $rotina ): ?>
                <a href="<?php echo site_url( $rotina['Link'] ); ?>" class="aside-link <?php echo $rotina['active'] ? 'active' : ''; ?> list-group-item"><?php echo $rotina['Rotina']?></a>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>