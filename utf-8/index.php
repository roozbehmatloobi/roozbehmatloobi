
<link rel="stylesheet" type="text/css" href="layout.css">
<?php 
if ($_POST){
	$name = $_POST['name'];
	$content = $_POST['content'];
}
?>
<html>
<head>
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script>
    $( document ).ready(function() {
   
    });
    </script>
</head>
<body>
<form  method = "POST">
<label style="padding:5px 30px">Name:</label><input class="lab-name" type="text" name="name"><br/>
<label>Comments:</label> 
<textarea class="comment" rows = "5" cols="60" name="content"></textarea>
<br/> <br/> 
<input type="submit" value="Post" class="submit"><br/>
</form>
<br>
<?php echo $contentFinal .="<b>" . $name . "</br><br/>".$content."<br/>"; ?>
</body>
</html>