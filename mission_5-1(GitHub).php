
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <span style="font-size: 40px;">・あなたの好きな動物を教えてください！</span>
</body>
<br>


<?php

    $target_name="";
    $target_comment="";
    $target_num="";
    $target_pass="";

        $dsn = 'mysql:dbname=データベース名=localhost';
        $user = 'ユーザ名';
        $passwd = 'パスワード名';
        $pdo = new PDO($dsn, $user, $passwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        if(empty($_POST["submit"])==false)
        {
            if(empty($_POST["name"])==false and empty($_POST["comment"])==false and empty($_POST["pass"])==false)
            {
                if(empty($_POST["target_num"])==true)
                {
                    //新規登録
                    $sql = "CREATE TABLE IF NOT EXISTS tbmission5"
                    ." ("
                    . "id INT AUTO_INCREMENT PRIMARY KEY,"
                    . "name char(32),"
                    . "comment TEXT,"
                    . "modifydate datetime ,"
                    . "password char(32)"
                    .");";
                    $stmt = $pdo->query($sql);

            
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $dt = new datetime('now');
                    $modify  =  $dt->format('Y-m-d H:i:s'); 
                    $pass = $_POST["pass"];

                    $stmt = $pdo->prepare("INSERT INTO tbmission5 (name, comment, modifydate, password) VALUES (:name, :comment, :modifydate, :password)");
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':modifydate', $modify, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
                    $stmt->execute();


                

                }else
                {
                    
                    //編集登録
                    
                    $edit_num2 = $_POST["target_num"];
                    $edit_pass2 = $_POST["pass"];
                
                    $sql = 'SELECT * FROM tbmission5';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                
                    foreach ($results as $row){

                        $pieces = [];
                        $pieces[0] = $row['id'];
                        $pieces[1] = $row['name'];
                        $pieces[2] = $row['comment'];
                        $pieces[3] = $row['modifydate'];
                        $pieces[4] = $row['password'];
                    
                        if($pieces[0] != $edit_num2)
                        {
                            ; //nop
                        }else
                        {
                            if($edit_pass2 != $pieces[4])
                            {
                                ; //nop
                            }
                            else
                            {

                                $id = (int)$edit_num2; //変更する投稿番号
                                $name = $_POST["name"];
                                $comment = $_POST["comment"];
                                $dt = new datetime('now');
                                $modify  =  $dt->format('Y-m-d H:i:s'); 
                                $password = $_POST["pass"];
                                $sql = 'UPDATE tbmission5 SET name=:name, comment=:comment, modifydate=:modifydate, password=:password WHERE id=:id';
                                $stmt = $pdo->prepare($sql);
                            
                                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                                $stmt->bindParam(':modifydate', $modify, PDO::PARAM_STR);
                                $stmt->bindParam(':password',$password , PDO::PARAM_STR);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                               
                            }
                        }
                    }                        
                }
            }
        }elseif(empty($_POST["delete"])==false)
        {
            if(empty($_POST["delete_num"])==false and is_numeric($_POST["delete_num"])==true and empty($_POST["pass"])==false)
            {
                $id = (int)$_POST["delete_num"];
                $password = $_POST["pass"];


                $sql = 'delete from tbmission5 where id = :id and password = :password';
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);

                $stmt->execute();         
            }
        }elseif(empty($_POST["edit"])==false and is_numeric($_POST["edit_num"])==true and empty($_POST["pass"])==false)
        {
            $edit_num = $_POST["edit_num"];
            $edit_pass = $_POST["pass"];


            $sql = 'SELECT * FROM tbmission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
        
            foreach ($results as $row){

                $pieces = [];
                $pieces[0] = $row['id'];
                $pieces[1] = $row['name'];
                $pieces[2] = $row['comment'];
                $pieces[3] = $row['modifydate'];
                $pieces[4] = $row['password'];
            
                if($pieces[0] != $edit_num)
                {
                    ; //nop
                }else
                {
                    if($edit_pass != $pieces[4])
                    {
                        ; //nop
                    }
                    else
                    {

                        $target_num=$pieces[0];
                        $target_name=$pieces[1];
                        $target_comment=$pieces[2];
                        $target_pass=$pieces[4];
                       
                    }
                }
            }
        }

?>


<body>
<?php
    
    $sql = 'SELECT * FROM tbmission5';
	$stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    $lastId = (-1);
    foreach ($results as $row){

        $pieces = [];
        $pieces[0] = $row['id'];
        $pieces[1] = $row['name'];
        $pieces[2] = $row['comment'];
        $pieces[3] = $row['modifydate'];
        $pieces[4] = $row['password'];

        echo "投稿番号[" . htmlspecialchars($pieces[0], ENT_QUOTES) ."]";
        echo "名前[" . htmlspecialchars($pieces[1], ENT_QUOTES) ."]";
        echo "コメント[" . htmlspecialchars($pieces[2], ENT_QUOTES) ."]";
        echo "投稿日時[" .  htmlspecialchars($pieces[3], ENT_QUOTES)."]<br>";
    }
    
?>

    <form action="" method="post">
        <input type="hidden" name="target_num" value="<?php echo htmlspecialchars($target_num, ENT_QUOTES); ?>"><!--編集対象番号--><br>
        <input type="text" name="name" placeholder="名前" value="<?php echo htmlspecialchars($target_name, ENT_QUOTES); ?>">名前<br>
        <input type="text" name="comment" placeholder="コメント" value="<?php echo htmlspecialchars($target_comment, ENT_QUOTES); ?>">コメント<br>
        <input type="text" name="pass" placeholder="パスワード" size="10" maxlength="32" value="<?php echo htmlspecialchars($target_pass, ENT_QUOTES); ?>">パスワード<br>
        <input type="submit" name="submit"><br><br>
    </form>
    <form action="" method="post">
        <input type="text" name="delete_num" placeholder="消去番号">消去番号を指定してください<br>
        <input type="text" name="pass" placeholder="パスワード" size="10" maxlength="32">パスワード<br>
        <input type="submit" name="delete" value="消去">
    </form>
    <form action="" method="post">
        <input type="text" name="edit_num" placeholder="編集対象番号">編集対象番号を指定してください<br>
        <input type="text" name="pass" placeholder="パスワード" size="10" maxlength="32">パスワード<br>
        <input type="submit" name="edit" value="編集">
    </form><br>
    <span style="font-size: 25px;">回答ありがとうございました！</span>

    
</body>
</html>