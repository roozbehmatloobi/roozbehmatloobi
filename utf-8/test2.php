
<html>
<body>
<?php
$data = $_POST["name"];
$output = "The character count for each distinct character in the string ";
echo $output;
countChar($data);

function countChar($data) {
    foreach (count_chars($data, 1) as $i => $val) {
        echo "\"" , chr($i) , "\" : $val .\n";
    }
}

?>

</body>
</html>