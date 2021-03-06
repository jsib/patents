<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=windows-1251" />
        <title>Патенты и товарные знаки</title>
        <?php $this->assetJS('js/jquery.js') ?>
        <?php $this->assetCSS('vendor/bootstrap/dist/css/bootstrap.min.css') ?>
        <?php $this->assetCSS('css/calendar.css') ?>
        <?php $this->assetJS('js/calendar.js') ?>
        <?php $this->assetCSS('css/style.css') ?>
    </head>
    <body>
        <div id="main_area">
            <?php $this->includeView('topmenu') ?>
            
            <h1 style='font-size:24pt;'><?php if ($this->table->possession == 'trademark') {echo "Товарные знаки";} else {echo "Патенты";} ?> (<?php echo $this->table->country_rus ?>)</h1>
            
            <div id='data_area'>
                <?php $this->output('body') ?>
            </div>
            <div id='manage_menu'>
                <?php if( $this->table->auth->userHasRight('edit') ): ?>
                    <h3 style='margin-top:30px;'>Действия</h3>
                    <a href='/<?php echo $this->table->country ?>/<?php echo $this->table->possession ?>/add'>Добавить <?php if ($this->table->possession == 'trademark') {echo "товарный знак";} else {echo "патент";} ?></a><br/><br/>
                <?php endif; ?>
            </div>
        </div>

        <!--START: Код календаря. Эту штуку надо ставить именно здесь, перед закрывающим body-->
        <script>
            $(".datepickerTimeField").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd.mm.yy',
                firstDay: 1, changeFirstDay: false,
                navigationAsDateFormat: false,
                duration: 0,// отключаем эффект появления
            });
        </script>
        <!--Используется так <input name="min" value="04.05.2010" class="datepickerTimeField">-->
        <!--Взять здесь http://yapro.ru/web-master/javascript/legkiy-kalendari.html-->
        <!--END: Код календаря-->
    </body>
</html>
