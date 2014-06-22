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
РЕГИСТРАЦИЯ
<form method="post" action="">
    Имя
    <br>
    <input type="text" name="text_reg"   <? if(isset($_POST['text_reg'])){ ?> value="<? echo $_POST['text_reg']; ?>"  <? }?> />
    <br>
    Пароль
    <br>
    <input type="password" name="pass_reg" />
    <br>
    Подтвердите пароль
    <br>
    <input type="password" name="pass_reg2" />
    <br>
    <input type="submit" />
</form>

</body>
</html>
<?php

$counter = 0;


if(isset($_POST['text_reg']) && isset($_POST['pass_reg']))
{
    if($_POST['text_reg'] != '' && $_POST['pass_reg'] != '')
    {
        $badArray = ['!', '#', '$', '%', '^', '&', '(', ')'];


        for($i = 0; $i < strlen($_POST['text_reg']); $i++)
        {
            for($j = 0; $j < count($badArray); $j++)
            {
                if($_POST['text_reg'][$i] == $badArray[$j])
                {
                    $counter = true;
                    break;
                }
            }

        }
        if(file_exists(__DIR__ . "/{$_POST['text_reg']}.txt"))
        {
            echo "Username already exist, please choose another";
        }
        elseif($_POST['pass_reg'] != $_POST['pass_reg2'])
        {
            echo "Пароли несовпадают";
        }
        elseif($counter == true)
        {
            echo "В имени пользователя недопустимые символы!";
        }
        elseif(count($_POST['text_reg']) > 12)
        {
            echo "Пароль не более чем 12 символов!";
        }
        else
        {
            $reg_data = ['name'=>$_POST['text_reg'], 'password'=>hash('md5', $_POST['pass_reg'])];
            $file_array = json_encode($reg_data);

            file_put_contents(__DIR__ . "/{$_POST['text_reg']}.txt", $file_array);

            $_SESSION['user_name'] = $_POST['text_reg'];

            header('Location: sessions.php');
        }
    }
    else
    {
        echo "Enter registration data again";
    }
}

?>



