<?php 

   //error_reporting(E_ALL);
   require_once "inc/LessLoader.php"; 
   $pal = $_GET['pal']; 
   $palettes = array('pink','gray', 'orange');
   $ll = new LessLoader("./css", $pal, $palettes, 'styles'); 
   //print_r($ll); die();

?>
<html>
    
    
    <head>
        
        <title>Sample demo page for less palette loader</title>
        <link href="<?php echo $ll->getCssName(); ?>" rel="stylesheet">
        
    </head>
    
    
    <body>
        
        
        <div class="color box200x200">
            
            <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                Select the palette you want for this page:<br/>
                <select name="pal">
                    <?php foreach ($palettes as $p): ?>
                    <option value="<?php echo $p ?>" <?php if($p==$pal) print "selected='selected'"?>>
                       <?php echo $p ?>
                    </option>
                    <?php endforeach; ?>
                    
                </select>

                <input type="submit" value="do"/>
            
            </form>
            
            
        </div>
        
    </body>
    
</html>
