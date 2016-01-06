
<?php 
$input = $_POST[“name”];
$reverse = utf8_strrev($input);
echo "The same string in reverse order $reverse";

function utf8_strrev($input){
    $input = "你好吗";
    preg_match_all('/./us', $input, $str);
    print_r($str[0]);
    print_r($str);
    return join('',array_reverse($str[0]));
}
?>