<?php $this->extend('base') ?>

<?php $this->start('body') ?>
    <form id='Form' action='<?php echo $this->form_action ?>' method='post' style='margin:0;padding:0;'>
        <table cellspacing='0' cellpadding='1' border='<?php echo $this->border ?>' class='data' 
            style='border:<?php echo $this->border + 1 ?>px solid #09F;'>

            <!-- Table Header -->
                <?php foreach($this->headers as $column => $column_rus): ?>
                    <?php if( $this->table->getColumnType($column) == 'hidden'): ?>
                        <?php continue; ?>
                    <?php endif; ?>

                    <th style='width:<?php echo $this->table->getColumnWidth($column) ?>;height:<?php echo $this->rowHeight ?>px;'>
                        <?php echo $this->table->buildColumnHeader($column, $column_rus, $this->sortColumn, $this->sortDirection) ?>
                    </th>
                <?php endforeach; ?>
            </tr>

            <!-- //Table Header -->
            <!-- Table Body -->
            <?php foreach($this->matrix as $row => $columns): ?>
                <tr>
                    <?php foreach($this->columns as $column => $empty): ?>
                        <?php $value = $this->matrix[$row][$column] ?>
                        <?php if ($this->table->getColumnType($column) == 'hidden'): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <td style='<?php echo $this->table->getCellAppearance($row, $column) ?>;height:<?php echo $this->rowHeight ?>px;padding-left:8px;'>
                            <!-- No edit rights -->
                            <?php if( !$this->table->auth->userHasRight('edit') || isset($this->links[$row][$column])): ?>
                                <!-- Text with hyperlink -->
                                <?php if ( isset($this->links[$row][$column])): ?>
                                    <a href='<?php echo $this->links[$row][$column]['href'] ?>'
                                       class='no_underlined'
                                       style='<?php echo $this->appearance[$row][$column]['style'] ?>'
                                       onclick='<?php echo $this->appearance[$row][$column]['onclick'] ?>'
                                    >
                                        <?php echo $value ?>
                                    </a>
                                <!-- Only text -->
                                <?php else: ?>
                                    <?php echo $value ?>
                                <?php endif; ?>
                            <!-- //No edit rights -->
                            <!-- Edit rights -->
                            <?php else: ?>
                                <?php if ($this->table->getColumnType($column) == 'hidden'): ?>
                                    <input type='hidden' 
                                           name='Form[<?php echo $row ?>][<?php echo $column ?>]' 
                                           value='<?php echo $value ?>'>
                                <?php else: ?>
                                    <input type='text' 
                                           name='Form[<?php echo $row ?>][<?php echo $column ?>]'
                                           value='<?php echo $value ?>'
                                           style='margin:0;padding:0;border:5px;width:<?php echo $this->table->getColumnInputWidth($column) ?>'>
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- //Edit rights -->
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            <!-- //Table Body -->
        </table>
        <?php if( $this->table->auth->userHasRight('edit') ): ?>
            <br/><input type='submit' value='Сохранить'></form>
        <?php endif; ?>
    </form>
<?php $this->stop('body') ?>

<?php $this->start('topmenu') ?>
    <!-- Top Menu -->
    <div id='menu_top'>
        <div id='menu_top_left_part'>
            <?php $topmenu_item_iteration = 1 ?>
            <?php $topmenu_items = (new App\Entity\TopMenu($this->table->country))->getList() ?>
            <?php //show($topmenu_items) ?>
            <?php if ( count($topmenu_items) > 0): ?>
                <?php foreach ($topmenu_items as $item): ?>
                    <a href='<?php echo $item['href'] ?>' class='<?php echo $item['class'] ?>'><?php echo $item['name_rus'] ?></a>
                    <span class='divider'></span>
                    <?php if ($topmenu_item_iteration % 7 == 0): ?>
                        <br/>
                    <?php endif; ?>
                    <?php $topmenu_item_iteration++ ?>
                <?php endforeach; ?>
            <?php else: ?>
                Нет пунктов для верхнего меню.
            <?php endif; ?>
        </div>
        <div id='menu_top_middle_part'>
        </div>
        <div id='menu_top_right_part'>
            Логин: <?php echo $this->table->auth->getSignedInUserName() ?><span style='width:100px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/sign_out/" class='logout'>Выйти</a>
        </div>
    </div>
    <!-- //Top Menu -->
<?php $this->stop('topmenu') ?>

