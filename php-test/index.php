<html>
    <head>

    </head>
<!-- scalars.php COMP334 -->

<body>  


    
<p>

<?php
$foo = true; 
if ($foo) 
echo "It is TRUE! <br /> \n";
$txt='1234'; 
echo "$txt <br /> \n";
$a = 1234; 
echo "$a <br /> \n";
$a = -123; 
echo "$a <br /> \n";
$a = 1.234; 
echo "$a <br /> \n";
$a = 1.2e3; 
echo "$a <br /> \n";
$a = 7E-10; 
echo "$a <br /> \n";

echo "hello single <br /> ";
echo "Aws with double qouted <br /> ";
print('aws is inside print <br> <br>');

echo 'Ali once said:';
$coffee = 'Java'; 
echo "$coffee's taste is great <br /> \n";
$str = <<<end
Example of string
spanning multiple lines
using “heredoc” 

q

syntax.sakfkdsaf;kasd;lfkl;sadf

end;
echo $str;
?>  
</p>



<h1>
    <?php

    echo "Arrays <br>" ;

    $arr = array(1 => 5, "aws" => "aws");
    echo $arr[1];
    echo "<br>";
    echo $arr["aws"]; 

    /*unset($arr[1]);
    echo $arr[1];*/

   /* for($i=0;$i< len($arr);$i++ )
    echo $arr[$i];*/
    echo "<br>";
    $arr2 = array(1,"two"=>2,3,4,5);
    echo $arr2[0];
    //echo  $arr;
    $b=array_values($arr2);
    //echo $b["two"];
    echo "<br>";
    echo "is: " . is_array($arr2);
    echo "<br>";

    echo count($arr2);
    print_r($arr2);

    $temp = explode(' ', "This is a sentence with seven words");
    print_r($temp);

    ?>
</h1>

<br>

<?php
$a = 1; /* limited variable scope */ 
function Test()
{ 
    global $a;
    $a = 5;
   echo $a; 
  /* reference to local scope variable */ 
} 
Test();

echo $a;

phpinfo();
?>


</body>
</html>
