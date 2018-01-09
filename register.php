<?PHP
//connect to host and db
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "ppics_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
}
//
$name = $_POST['nameField'];//name
$email = $_POST['emailField'];//email
$date = date('d-M-Y H:i:s', time()+60*60*24);
$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

$sql = "INSERT INTO pol02_contact_enquiries (name, email, ip, date) VALUES ('$name','$email','$ip','$date')";
$result = mysqli_query($conn, $sql);

//
//send email
//$to = 'info@perfectpics.co.ke';
$to = 'mbuluma@gbc.co.ke';
$webemail = 'website@perfectpics.co.ke';
$subject = 'PerfectPics Download Notification';
$from = 'PerfectPics Download Notification [ ' .$webemail. ' ]';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=us-ascii' . "\r\n";
$headers .= 'From: PerfectPics Download Notification <website@perfectpics.co.ke>' . "\r\n";
//$headers .= 'Bcc: bulumaknight@gmail.com' . "\r\n";
$body = $name. ' (' .$email. ') has downloaded the software at ' .$date. '';
	mail($to, $subject, $body,$headers);//send email

	//echo $body;

//Send email to the Downloader..is there such a word
$message = '<html>
<head>
	<title>PerfectPics Email</title>
	<style type="text/css">
		*{
			margin:0px;
			padding:0px;
			font-family:Arial;
			font-size: 12px;
			font-family:Arial;
		}
		table{
			border-collapse: collapse;
			font-family:Arial;
		}
		.wrapper-table{
			width:100%;
			background-color: #a5a3a3;
		}
		.top-row{
			background-color:#c1d72d;
			font-family:Arial;
		}
		.top-row-table{
			width:800px;
			font-family:Arial;
		}
		.banner-table-row{
			background-color: #a5a3a3;
			font-family:Arial;
		}
		.banner-table{
			width:800px;
			background-color: #ffffff;
			font-family:Arial;
		}
		.banner-table p{
			margin:0px 30px 10px 30px;
			font-size: 14px;
			text-align: justify;
			font-family:Arial;
		}
		.banner-table p a{
			font-size:14px;
			font-family:Arial;
		}
		.banner-table h2{
			font-size:17px;
			margin:0px 30px 0px 30px;
			color:#bdd616;
			font-family:Arial;
		}
		.banner-table h3{
			font-size:17px;
			margin:15px 30px 70px 30px;
			color:#bdd616;
			font-family:Arial;
		}
	</style>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="0" class="wrapper-table">
	<tr>
		<td class="top-row" align="center">
			<table class="top-row-table" border="0" cellspacing="0" cellpadding="0" width="800px">
				<tr>
					<td align="right" valign="middle"><img src="http://perfectpics.co.ke/images/email/Email-Logo.jpg"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="banner-table-row" align="center">
			<table class="banner-table" border="0" cellspacing="0" cellpadding="0" width="800px">
				<tr>
					<td align="center" valign="middle"><img src="http://perfectpics.co.ke/images/email/body.jpg"></td>
				</tr>
				<tr>
					<td>
						<p>Thanks for downloading our software. You are one step closer to ensuring your memories remain intact forever. At PerfectPics, we are all about "keeping memories alive". We have got a full range of products for you to choose from, depending on your need. Have a story to tell, there is photobooks. Need to cherish a single moment, there is canvases. There is something for everyone... and if you feel you want an alternative, tell us and we will find a solution for you</p>
						<p>Now that you have discovered us, let us get you started:</p>
						<h2>Is your download complete?</h2>
						<p>Just in case something did not go to plan, you will find the PerfectPics software at <a href="http://perfectpics.co.ke/download">http://perfectpics.co.ke/download</a></p>
						<h2>Choose your book?</h2>
						<p>If you have not already, choose a book that works best for your project <a href="http://perfectpics.co.ke/photobooks">http://perfectpics.co.ke/photobooks</a> - if you are not sure, just ask us.</p>
						<h2>Could you do with a hand?</h2>
						<p>Most of us could. So here is some basic tips: <a href="http://perfectpics.co.ke/how-it-works">http://perfectpics.co.ke/how-it-works</a>. Need help with design? Layout of your book? Need a designer to assist? Let us know and we will send someone to assist you. If you can not find what you are looking for, call us <a href="tel:0705155135">0705 155 135</a> or email us at <a href="mailto:info@perfecpics.co.ke">info@perfectpics.co.ke</a> and we will be happy to show you how!</p>
						<h2>Order your book?</h2>
						<p>Once your book is finished, upload it directly to our system, and you can order one copy or many. You will have your book in approximately 7-10 working days</p>
						<p>You are ready to tell your first story, and to help you on, we will give you 20% off you first order. Just add the following code: JUST_FOR_ME in the voucher window when checking out</p>
						<h3>Happy Bookmaking</br>The PerfectPics Team</h3>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>

</body>
</html>';
$subject2 = 'Thank you for downloading the PerfectPics Software';
mail($email, $subject2, $message, $headers);
//
//end
//var_dump($name);
echo ($message);
// if(!$message) header("Location: ".$this->baseUrl."?tmpl=comingsoon");
header("Location: index.php?tmpl=comingsoon");
// header(Location: 'index.php');
?>
