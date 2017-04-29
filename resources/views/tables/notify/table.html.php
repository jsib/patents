Количество: <?php echo count($this->matrix) ?>
<table cellspacing='0' cellpadding='1' border='<?php echo $this->border ?>' class='data' 
    style='border:<?php echo $this->border + 1 ?>px solid #09F;
           border-collapse: collapse;margin-top:10px;margin-bottom:10px;'>
    <tr>
    <!-- Table Header -->
        <?php foreach($this->headers as $column => $column_rus): ?>
            <th style='width:<?php echo $this->table->getColumnWidth($column) ?>;height:<?php echo $this->rowHeight ?>px;'>
                <?php echo $column_rus ?>
            </th>
        <?php endforeach; ?>
    </tr>

    <!-- //Table Header -->
    <!-- Table Body -->
    <?php foreach($this->matrix as $row => $columns): ?>
        <tr>
            <?php foreach($this->columns as $column => $empty): ?>
                <?php $value = $this->matrix[$row][$column] ?>
                <td style='width:<?php echo $this->table->getColumnWidth($column) ?>;<?php echo $this->table->getCellAppearance($row, $column) ?>;height:<?php echo $this->rowHeight ?>px;padding-left:8px;'>
                    <?php echo $value ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    <!-- //Table Body -->
</table>
Количество: <?php echo count($this->matrix) ?>
