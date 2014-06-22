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




<?php
if(isset($_SESSION['user_name']) == false)
{
?>

    <a href="login.php">Войти</a>
    ||
    <a href="register.php">Регистрация</a>
    <br>
    <br>

<?php
}
else
{

?>

    <form method="post" action="">
        <input type="hidden" name="exit" />
        <input type="submit" value="Выход" />
    </form>

<?php
}
?>


<?php


if(isset($_SESSION['user_name']))
{
	echo "Hello <b>{$_SESSION['user_name']}</b>";
	echo '<br />';
	echo '<br />';
}
?>


<?php
if(isset($_SESSION['user_name']))
{

?>
    <form method="post" action="" enctype="multipart/form-data">

	    <br />
	    <br />
	    <br />
	    Вырази свою мысль!
	    <br />
	    <textarea name="comment"></textarea>
	    <br />
        <input type="file"  name="file_add">
	    <input type="submit" value="Отправить" />
    </form>

<?php
}
?>


<?php



if(isset($_SESSION['user_name']) && isset($_POST['comment']) && $_POST['comment'] != '')
{
	$date = time();

	$comment_data = ['name'=> $_SESSION['user_name'], 'text'=> $_POST['comment'], 'date'=> date('d.m.Y h:i:s'), 'ip'=> $_SERVER['REMOTE_ADDR']];



    if(isset($_FILES['file_add']) && $_FILES['file_add']['error'] != 4)
    {
        if($_FILES['file_add']['error'] == 1)
        {
            echo "Файл слишком большой";
        }
        elseif(strpos($_FILES['file_add']['type'], 'age') == false)
        {

			echo "Файл должен быть картинкой! <br />";


        }
		else
		{
			move_uploaded_file($_FILES['file_add']['tmp_name'], __DIR__ . "/photo/$date" );
			$comment_data['file'] = "$date";
			$json = json_encode($comment_data);
			file_put_contents(__DIR__ . "/$date.json", $json);
		}
    }




}
elseif(isset($_POST['comment']) && $_POST['comment'] != '')
{
	echo 'please register';
	echo '<br />';
}
elseif(isset($_POST['comment']) && $_POST['comment'] == '')
{

	echo "Введите текст пожалуйста";
	echo '<br>';

}

$names = scandir(__DIR__);

foreach($names as $name)
{
	if(strpos($name,'.json'))
	{
		$file = file_get_contents(__DIR__ . "/$name");
		$file = json_decode($file, true);

		if(isset($file['file']) && $file['text'] != '')
		{
			echo "<b>{$file['name']}</b>";
			echo '<br />';
			$path = "photo/{$file['file']}";
			echo "<img src='$path' width='100'/>";
			echo " ";
			echo $file['text'];
			echo '<br />';
			echo $file['date'];
			echo '<br / >';
			if(isset($_SESSION['user_name']))
			{
				if($_SERVER['REMOTE_ADDR'] == $file['ip'] && file_exists(__DIR__ . "/{$_SESSION['user_name']}.txt") && $file['name'] == $_SESSION['user_name'])
				{
					echo "<a href='sessions.php?delete=$name'>удалить</a>";
					echo '<br>';
				}
			}
		}
		elseif($file['text'] != '')
		{
			echo "<b>{$file['name']}</b>";
			echo '<br />';
			echo $file['text'];
			echo '<br />';
			echo $file['date'];
			echo '<br / >';
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



