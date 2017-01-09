<?php
   $fn="./warehouse/".$_GET["fn"].".enw";      
   header("Content-Type: application/force-download");
   header("Content-Disposition: attachment; filename=".basename($fn));
   readfile($fn); 
?>
