<?php

session_start();

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8" />
	<title>Guest book</title>
</head>
<body>
РЕГИСТРАЦИЯ
<form method="post" action="">
	<input type="text" name="text_reg" />
	<input type="password" name="pass_reg" />
	<input type="submit" />
</form>
<br>
ВХОД
<form method="post" action="">
	<input type="text" name="text_login" />
	<input type="password" name="pass_login" />
	<input type="hidden" value="hide" />
	<input type="submit" />
</form>

<form method="post" action="">
	<input type="hidden" name="exit" />
	<input type="submit" value="Выход" />
</form>



<?php

//registration

if(isset($_POST['text_reg']) && isset($_POST['pass_reg']))
{
	if($_POST['text_reg'] != '' && $_POST['pass_reg'] != '')
	{
		if(file_exists(__DIR__ . "/{$_POST['text_reg']}.txt"))
		{
			echo "Username already exist, please choose another";
		}
		else
		{
			$reg_data = ['name'=>$_POST['text_reg'], 'password'=>$_POST['pass_reg']];
			$file_array = json_encode($reg_data);

			file_put_contents(__DIR__ . "/{$_POST['text_reg']}.txt", $file_array);

			echo "Thanks for register";
		}
	}
	else
	{
		echo "Enter registration data again";
	}
}

//login
if(isset($_POST['text_login']) && isset($_POST['pass_login']))
{
	if($_POST['text_login'] != '' && $_POST['pass_login'] != '')
	{
		if(file_exists(__DIR__ . "/{$_POST['text_login']}.txt"))
		{
			$file = file_get_contents(__DIR__ . "/{$_POST['text_login']}.txt");
			$file = json_decode($file, true);
			if($file['name'] == $_POST['text_login'] && $file['password'] == $_POST['pass_login'])
			{

				$_SESSION['user_name'] = $_POST['text_login'];



			}
			else
			{
				echo "You enter wrong data";
			}
		}
		else
		{
			echo "User doesn't exist, please register";
		}
	}
	else
	{
		echo "Enter registration data again";
	}
}

if(isset($_SESSION['user_name']))
{
	echo "Hello <b>{$_SESSION['user_name']}</b>";
	echo '<br />';
	echo '<br />';
}
?>

<form method="post" action="">

	<br />
	<br />
	<br />
	Вырази свою мысль!
	<br />
	<textarea name="comment"></textarea>
	<br />
	<input type="submit" value="Отправить" />
</form>




<?php

if(isset($_SESSION['user_name']) && isset($_POST['comment']))
{
	$date = time();

	$comment_data = ['name'=> $_SESSION['user_name'], 'text'=> $_POST['comment'], 'date'=> date('d.m.Y h:i:s'), 'ip'=> $_SERVER['REMOTE_ADDR']];
	$json = json_encode($comment_data);
	file_put_contents(__DIR__ . "/$date.json", $json);



}
elseif(isset($_POST['comment']) && $_POST['comment'] != '')
{
	echo 'please register';
	echo '<br />';
}

$names = scandir(__DIR__);

foreach($names as $name)
{
	if(strpos($name,'.json'))
	{
		$file = file_get_contents(__DIR__ . "/$name");
		$file = json_decode($file, true);

		echo "<b>{$file['name']}</b>";
		echo '<br>';
		echo $file['text'];
		echo '<br>';
		echo $file['date'];
		echo '<br>';
		if(isset($_SESSION['user_name']))
		{
			if($_SERVER['REMOTE_ADDR'] == $file['ip'] && file_exists(__DIR__ . "/{$_SESSION['user_name']}.txt") && $file['name'] == $_SESSION['user_name'])
			{
				echo "<a href='sessions.php?delete=$name'>удалить</a>";
				echo '<br>';
			}
		}



	}
}

if(isset($_GET['delete']))
{
	$file = file_get_contents(__DIR__ . "/{$_GET['delete']}");
	$file = json_decode($file, true);

	if($_SERVER['REMOTE_ADDR'] == $file['ip'] && file_exists(__DIR__ . "/{$_SESSION['user_name']}.txt"))
	{

		unlink(__DIR__ . "/{$_GET['delete']}");
		header('Location: sessions.php');

	}
	else
	{
		echo "Pososi mou jopu";
		header('Location: sessions.php');
	}


}

if(isset($_POST['exit']))
{
	session_unset();
	header('Location: sessions.php');
}





?>



</body>
</html>



