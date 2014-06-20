<!DOCTYPE html>
<html>
    <head>
		<link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="utf-8" />
        <title>Guest book</title>
    </head>
    <body>




	<?php
	if(isset($_POST['name_field'])&& isset($_POST['password_field']))
	{
		$field_data = ['name_field'=>$_POST['name_field'], 'password'=>$_POST['password_field'], 'ip'=>$_SERVER['REMOTE_ADDR']];
		$file_array = json_encode($field_data);


		if($_POST['name_field'] == "" || $_POST['password_field'] == "")
		{
			echo "Enter again please";
		}
		else
		{
			file_put_contents(__DIR__ . "/{$_POST['name_field']}.txt", $file_array);
		}
	}






	?>


    <form method="post" action="">
        Name
        <br>
        <input type="text" name="name" />
        <br>
		Password
		<br>
		<input type="password" name="password" />
		<br>
        Text
        <br>
        <textarea name="text"></textarea>
        <br>
        <input type="submit">
    </form>


    <?php



        if(isset($_POST['name'])&& isset($_POST['text']))
        {


            $array = ['name'=>$_POST['name'], 'text'=> $_POST['text'], 'date'=> date('d.m.Y h:i:s'), 'ip'=> $_SERVER['REMOTE_ADDR']];
            $names =[];
            $file =[];
            $date = time();

            $json = json_encode($array);
            if($_POST['text'] == "" || $_POST['name'] == "")
            {
                echo "Enter again please";
            }
			elseif(file_exists(__DIR__ . "/{$_POST['name']}.txt") == false)
			{
					echo "Please register";
			}
            else
			{
				file_put_contents(__DIR__ . "/$date.json", $json);
            }

        }

        if(isset($_GET['delete']))
            {
                $file = file_get_contents(__DIR__ . "/{$_GET['delete']}");
                $file = json_decode($file, true);

                if($_SERVER['REMOTE_ADDR'] == $file['ip'])
                {

                    unlink(__DIR__ . "/{$_GET['delete']}");
                    header('Location: register.php');

                }
                else
                {
                    echo "Pososi mou jopu";
                    header('Location: register.php');
                }


            }
        $names = scandir(__DIR__);

        foreach($names as $name)
        {
            if(strpos($name,'.json'))
            {
                $file = file_get_contents(__DIR__ . "/$name");
                $file = json_decode($file, true);

                echo "<h5>{$file['name']}</h5>";
                echo '<br>';
                echo $file['text'];
                echo '<br>';
                echo $file['date'];
                echo '<br>';
                if($_SERVER['REMOTE_ADDR'] == $file['ip'])
                {
                    echo "<a href='register.php?delete=$name'>удалить</a>";
                }


            }
        }





    ?>

    </body>
</html>
