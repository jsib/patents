<?php $this->extend('tables\possessions\base') ?>

<?php $this->start('body') ?>
    Количество: <?php echo count($this->matrix) ?>
    <form id='Form' action='<?php echo $this->form_action ?>' method='post' style='margin:0;padding:0;'>
        <table cellspacing='0' cellpadding='1' border='<?php echo $this->border ?>' class='data' 
            style='border:<?php echo $this->border + 1 ?>px solid #09F;'>
            <tr>
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
                                <input type='hidden' 
                                       name='Form[<?php echo $row ?>][<?php echo $column ?>]' 
                                       value='<?php echo $value ?>'>
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
                                <?php if ($this->table->getColumnType($column) != 'hidden'): ?>
                                    <input type='text' 
                                        name='Form[<?php echo $row ?>][<?php echo $column ?>]'
                                        value='<?php echo $value ?>'
                                        style='margin:0;padding:0;border:5px;width:<?php echo $this->table->getColumnInputWidth($column) ?>'
                                        class='<?php echo $this->appearance[$row][$column]['class'] ?>'
                                    >
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- //Edit rights -->
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            <!-- //Table Body -->
        </table>
        Количество: <?php echo count($this->matrix) ?><br/>
        <?php if( $this->table->auth->userHasRight('edit') ): ?>
            <br/><input type='submit' value='Сохранить'></form>
        <?php endif; ?>
    </form>
<?php $this->stop('body') ?>

<?php $this->start('script') ?>
    <script>
        <!-- Source http://yapro.ru/web-master/javascript/legkiy-kalendari.html -->
        $(".datepickerTimeField").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            changeFirstDay: false,
            navigationAsDateFormat: false,
            duration: 0, //Disable appearing effect
        });
    </script>
<?php $this->stop('script') ?>

