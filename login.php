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
	<a href="sessions.php">На главную</a>
	<br>
    ВХОД
    <form method="post" action="">
        ИМЯ
        <input type="text" name="text_login" />
        ПАРОЛЬ
        <input type="password" name="pass_login" />
        <input type="hidden" value="hide" />
        <input type="submit" />
    </form>

</body>
</html>

<?php

if(isset($_POST['text_login']) && isset($_POST['pass_login']))
{
    if($_POST['text_login'] != '' && $_POST['pass_login'] != '')
    {
        if(file_exists(__DIR__ . "/{$_POST['text_login']}.txt"))
        {
            $file = file_get_contents(__DIR__ . "/{$_POST['text_login']}.txt");
            $file = json_decode($file, true);

            if($file['name'] == $_POST['text_login'] && $file['password'] == hash('md5', $_POST['pass_login']))
            {

                $_SESSION['user_name'] = $_POST['text_login'];
                header('Location: sessions.php');

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

?>
