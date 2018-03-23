<style>
.wrong {
   // margin: 0 auto;
   // padding: 5px;
    //background-color: #D9853B;
    //border: 2.5px solid #ECECEA;
    //width: auto;
   // max-width: 350px;
   // text-align:center;
    font-size: 25;
    color: white;
   // position:relative;
   // top:350px;
   width:100%;
	max-width:600px;
	text-align:center;
	margin:0 auto;
	position:relative;
	top:155px;
}
.right {
    margin: 0 auto;
    width: auto;
    max-width: 350px;
    text-align:center;
    color: white;
    position:relative;
    top:100px;
}
</style>
<?php

$db = new mysqli("localhost", "turtlejimmy", "coneyisland", "links141643");

function generateRandomString($length = 6) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

if (isset($_GET['title'])) {
	// SELECT link FROM our database
	$result = $db->prepare("SELECT * FROM links WHERE title=?");
	$result->bind_param("s", $_GET['title']);
	$result->execute();

	$goto = $result->get_result()->fetch_array();
	$g = $goto[1];
	header("Location: $g");
}

if (isset($_POST['shorten'])) {

	// Generate title
	$title = generateRandomString();

	// Insert http://
	if (substr($_POST['url_to_shorten'], 0, 8) == "https://") {
		// Prepend http://
		$url = $_POST['url_to_shorten'];
	} elseif (substr($_POST['url_to_shorten'], 0, 7) != "http://") {
		// Prepend http://
		$url = "http://".$_POST['url_to_shorten'];
	} else {
		$url = $_POST['url_to_shorten'];
	}

	// INSERT link into our database
	$result = $db->prepare("INSERT INTO links VALUES('', ?, ?)");
	$result->bind_param("ss",$url, $title);
	$result->execute();

	//echo "<center>Your shortened link: <br /> zipr.me/".$title."</center>";
	//echo "<div class=\"wrong\">Your shortened link: <br /> zipr.me/".$title."</div>";
	//echo "<div class=\"right\">Your shortened link:</div><div class=\"wrong\">zipr.me/".$title."</div>";
	//echo "<div class=\"wrong\">zipr.me/".$title."</div>";
	print "<div class=\"wrong\">Click the Link to Copy!</div>";

}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="http://www.zipr.me/favicon.ico" >
<link rel="shortcut icon" href="favicon.ico" >
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   background-color: #D9853B;
   color: white;
   text-align: center;
   height:34px;
   border: 1.2px solid #fff;
   font-size: 17px;
}
body {
	font: 1.12em Tahoma, sans-serif;
	background-color: #558C89;
	/*background-image: url("blah.jpg")*/
}
h1 {
    color: white;
    text-align: center;
}
.container {
	width:100%;
	max-width:600px;
	text-align:center;
	margin:0 auto;
	position:relative;
	top:150px;
}
.yo{
    //position: fixed;
   // top:400px;
    //left: 50%;
   // margin-left: -100px;
      width:100%;
	max-width:600px;
	text-align:center;
	margin:0 auto;
	position:relative;
	top:160px;
}
input {
	padding:13px;
	background-color:#fff;
	border: 2.5px solid #D9853B;
	margin:0;
}

input[type="text"] {
	width:350px;
	font-size: 15px;
}
input[type="yes"] {
	width:200px;
	font-size: 20px;
	text-align:center;
	cursor: pointer;
	margin: 0 auto;
	color: white;
        padding: 2px;
        background-color: #D9853B;
        border: 2.5px solid #ECECEA;
}
#copyStatus {
    color: white;
    font-size: 15px;
}
input[type="submit"] {
	font-size: 15px;
	cursor: pointer;
}
p {
    color: white;
    text-align: center;
    bottom:10px;
    position:relative;
}
</style>
<title>Zipr | URL Shortener and Minimalist Link Management</title>
</head>
<body>
<div class="yo">
<input type="yes" readonly="readonly" value="zipr.me/<?php echo htmlspecialchars($title);?>" onclick="copyToClipboard(this);">
	<div id="copyStatus" style="display: none;">Copied to Clipboard</div>

	<script type="text/javascript">
		function copyToClipboard(element) {
			element.select();
			if(document.execCommand('copy')) {
				document.getElementById('copyStatus').style.display = '';
				setTimeout(function() {
					document.getElementById('copyStatus').style.display = 'none';
				}, 800);
			}
		}
	</script>
</div>
<br>
<br>
<div class="container">
<center>
	<h1>Shorten your links with <img src="/public_html/ziprlogo1.png" alt="zipr.me" width="768" height="509" align="middle"></h1>
	<form action="/" method="POST">
	<input type="text" name="url_to_shorten" value="" placeholder="Enter URL here">
	<input type="submit" name="shorten" value="Shorten" />
</center>
</form>
</div>

<div class="footer">
  <p>zipr.me© 2018</p>
</div>
</body>
</html>
