<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="navbar" class="z-depth-1">

  <div class="left pull-left visible-xs visible-sm">
    <button class="btn btn-primary" onclick="toggleSideBar();">
      <span class="glyphicon glyphicon-menu-hamburger"></span>
    </button>
  </div>

  <div class="right pull-right">
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <?php echo $view->user->data['email']; ?>
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
        <li><a href="<?php echo site_url( 'dashboard/logout' ); ?>">Sair</a></li>
      </ul>
    </div>
  </div>
</div>