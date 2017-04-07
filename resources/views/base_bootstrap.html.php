<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>
    <!-- Link CSS files -->
    <?php $this->assetCSS('vendor/bootstrap/dist/css/bootstrap.min.css') ?>
    <?php $this->assetCSS('css/signin.css') ?>
    
    <!-- Link Java Script -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php $this->assetJS('vendor/jquery/dist/jquery.min.js') ?>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <?php $this->assetJS('vendor/bootstrap/dist/js/bootstrap.min.js') ?>
    <?php $this->assetJS('vendor/validator/validator.min.js') ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php $this->output('body') ?>
    <?php $this->output('script') ?>
  </body>
</html>
