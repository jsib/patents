<div id='menu_top'>
    <div id='menu_top_left_part'>
        <!-- Countries -->
        <?php $topmenu_items = (new App\Entity\TopMenu($this->table->country, $this->table->possession))->getCountryList() ?>
        <?php //show($topmenu_items) ?>
        <?php if ( count($topmenu_items) > 0): ?>
            <?php foreach ($topmenu_items as $item): ?>
                <a href='<?php echo $item['href'] ?>' class='<?php echo $item['class'] ?>'><?php echo $item['name_rus'] ?></a>
                <span class='divider'></span>
            <?php endforeach; ?>
        <?php else: ?>
            Нет пунктов для данного меню.
        <?php endif; ?>
        <!-- //Countries -->
        <br/>
        <!-- Properties -->
        <?php $topmenu_items = (new App\Entity\TopMenu($this->table->country, $this->table->possession))->getPossesionList() ?>
        <?php //show($topmenu_items) ?>
        <?php if ( count($topmenu_items) > 0): ?>
            <?php foreach ($topmenu_items as $item): ?>
                <a href='<?php echo $item['href'] ?>' class='<?php echo $item['class'] ?>'><?php echo $item['name_rus'] ?></a>
                <span class='divider'></span>
            <?php endforeach; ?>
        <?php else: ?>
            Нет пунктов для данного меню.
        <?php endif; ?>
        <!-- //Properties -->
        <br/>
    </div>
    <div id='menu_top_middle_part'>
    </div>
    <div id='menu_top_right_part'>
        Логин: <?php echo $this->table->auth->getSignedInUserName() ?><span style='width:100px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/sign_out/" class='logout'>Выйти</a>
    </div>
    <div class='clear'></div>
</div>
