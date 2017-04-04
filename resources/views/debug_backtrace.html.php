<style>
    .error {
        margin: 20px 0;
        border:1px solid #000;
        padding:10px;
        background:#CCC;
        font-family:Tahoma;
        font-size:11pt;
        z-index:1000;
    }
    
    .header {
        font-family:Tahoma;
        font-size:18pt;
        font-weight:normal;
        color:red
    }
    
    .debug_backtrace {
        margin:10px 0;
        padding:1px 15px;
        background:#DDD;
    }
    
    .hidden, .shown {
        margin:10px 0px;
        padding:5px 15px;
        
        background:#EEE;
    }
    
    .hidden {
        display:none;
    }
    
    .shown {
        display:block;
    }
</style>

<div class='error'>
    <h1 class='header'>Error</h1>
    <?php if ($errno !== false): ?>
        Code: <?php echo $errno ?><br/>
    <?php endif ?>

    Text: <b><?php echo $error ?></b><br/>
    
    <?php if ($errfile !== false): ?>
        File: <?php echo $errfile ?><br/>
    <?php endif ?>
    
    <?php if ($errline !== false): ?>
        Line: <?php echo $errline ?><br/>
    <?php endif ?>
        
    <?php if ($errno_uknown !== false): ?>
        Code: Uknown error code, php could throw error by it's standart
        mechanism<br/>
    <?php endif ?>

    <div class='debug_backtrace'>
        <?php foreach (self::$debug as $key => $entry): ?>
            <h2>Debug backtrace #<?php echo $key ?></h2>
            <?php if (isset($entry['file'])): ?>
                File: <?php echo $entry['file'] ?><br/>
            <?php endif ?>

            <?php if (isset($entry['line'])): ?>
                Line: <?php echo $entry['line'] ?><br/>
            <?php endif ?>

            <?php if (isset($entry['function'])): ?>
                Function: <?php echo $entry['function'] ?><br/>
            <?php endif ?>

            <?php if (isset($entry['class'])): ?>
                Class: <?php echo $entry['class'] ?><br/>
            <?php endif ?>

            <?php if (isset($entry['object'])): ?>
                <a href='#' onclick="showOrHide(<?php echo $key ?>, 'object')">
                    Object: 
                </a>
                <div id='entry[<?php echo $key ?>][object]' class='hidden'>
                    <pre><?php print_r($entry['object']) ?></pre>
                </div><br/>
            <?php endif ?>

            <?php if (isset($entry['args'])): ?>
                <a href='#' onclick="showOrHide(<?php echo $key ?>, 'args')">
                    Args: 
                </a>
                <div id='entry[<?php echo $key ?>][args]' class='hidden'>
                    <pre><?php print_r($entry['args']) ?></pre>
                </div><br/>
            <?php endif ?>

        <?php endforeach ?>
    </div>
</div>

<script>
    //Store information about each entry param value,
    //is it shown or hidden at this moment
    entries = [];
    
    function showOrHide(entry_key, param)
    {
        //Form block id which we should show or hide
        block_id = 'entry[' + entry_key.toString() + '][' + param + ']';
        
        //Get block object
        block = document.getElementById(block_id);
        
        //Define if block shown or hidden now
        if (block_id in entries) {
            now = entries[block_id];
        } else {
            now = 'hidden';
        }
        
        //If block is visible, let's hide it, or show it, if it's hidden.
        switch (now) {
            case 'hidden':
                block.className = 'shown';
                entries[block_id] = 'shown';
                break;
            case 'shown':
                block.className = 'hidden';
                entries[block_id] = 'hidden';
                break;
        }
        
        console.log(block_id);
        
    }
</script>