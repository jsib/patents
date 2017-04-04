<?php $this->extend('base') ?>

<?php $this->start('body') ?>
    <table cellspacing='0' cellpadding='1' border='<?php echo $this->border ?>' class='data' 
        style='border:<?php echo $this->border + 1 ?>px solid #09F;'>

        <!-- Table Header -->
        <tr>
            <?php foreach($this->headers as $column => $column_rus): ?>
                <?php if( $this->table->getColumnType($column) == 'hidden'): ?>
                    <?php continue; ?>
                <?php endif; ?>

                <th style='width:<?php echo $this->table->getColumnWidth($column) ?>'>
                    <?php echo $this->table->buildColumnHeader($column, $column_rus, $this->sortColumn, $this->sortDirection) ?>
                </th>
            <?php endforeach; ?>
        </tr>

        <!-- //Table Header -->

        <!-- Table Body -->
        <?php foreach($this->matrix as $row => $columns): ?>
            <tr>
                <?php foreach($columns as $column => $value): ?>
                    <?php if ($this->table->getColumnType($column) == 'hidden'): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <td style='<?php echo $this->table->getCellAppearance($row, $column) ?>;height: <?php echo $this->rowHeight ?>;padding-left:8px;'>
                        <!-- No edit rights -->
                        <?php if( !$this->table->auth->getRight('edit') ): ?>
                            <!-- Text with hyperlink -->
                            <?php if( isset($this->links[$row][$column]) && !$this->auth->getRight('edit')): ?>
                                <a href='<?php echo $this->links[$row][$column] ?>' class='no_underlined'>
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
<?php $this->stop('body') ?>