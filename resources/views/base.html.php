<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=windows-1251" />
        <title>Управление патентами</title>
        <script type="text/javascript" src="/_content/js/jquery.js"></script>
        <link rel="stylesheet" href="/_content/css/calendar.css" type="text/css"/>
        <script type="text/javascript" src="/_content/js/calendar.js"></script>
        <link rel="stylesheet" href="/_content/css/style.css" type="text/css"/>
    </head>
    <body>
        <div id="main_area">
            <!-- Top Menu -->
            <div id='menu_top'>
                <div id='menu_top_left_part'>
                    <?php $topmenu_item_iteration = 1 ?>
                    <?php $topmenu_items = (new Entities\TopMenu($this->table->country))->getList() ?>
                    <?php if ( count($topmenu_items) > 0): ?>
                        <?php foreach ($topmenu_items as $item): ?>
                            <a href='<?php echo $item['href'] ?>' class='<?php echo $item['class'] ?>'><?php echo $item['text'] ?></a>
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
                    Логин: {login}<span style='width:100px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="index.php?action=logout" class='logout'>Выйти</a>
                </div>
            </div>
            <!-- //Top Menu -->
            <div class='clr'></div>
            
            <br/><h1 style='font-size:24pt;'><?php echo $this->name_rus ?></h1><br/>
            
            <div id='data_area'>
                <form id='Form' action='<?php echo $this->form_action ?>' method='post' style='margin:0;padding:0;'>
                    <?php $this->output('body') ?>
                    <?php if( $this->table->auth->getRight('edit') ): ?>
                        <br/><br/><input type='submit' value='Сохранить'></form>
                    <?php endif; ?>
                </form>
            </div>
            <div id='manage_menu'>
                <?php if( $this->table->auth->getRight('edit') ): ?>
                    <h3 style='margin-top:30px;'>Действия</h3>
                    <a href='/<?php echo $this->country ?>/patent/add'>Добавить патент</a>
                    <br/><br/>
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