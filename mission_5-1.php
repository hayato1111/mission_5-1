<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <?php
            //データベース接続
            $dsn = 'データベース名';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            //編集項目に
            $editNumber = "";
            $editName = "";
            $editComment = "";
            $editPassword = "";
            
            //editNumberが入っている場合
            if(!empty($_POST["num"]) && isset($_POST["submit"])){
                $id = $_POST["num"]; //変更する投稿番号
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $pass1 = $_POST["pass1"];
                
                $sql = 'UPDATE tables SET name=:name,comment=:comment,password=:password WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':password', $pass1, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            //名前とコメント、パスワードが入力された場合
            elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass1"]) && isset($_POST["submit"])){
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $pass1 = $_POST["pass1"];
                
                //データを入力する
                $sql = $pdo -> prepare("INSERT INTO tables (name, comment, password) VALUES (:name, :comment, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
                $name = $name;
                $comment = $comment;
                $pass = $pass1;
                $sql -> execute();
            }
            //削除対象番号とパスワードが入力された場合
            elseif(!empty($_POST["sakujoBango"]) && !empty($_POST["pass2"]) && isset($_POST["delete"])){
                $sakujoBango = $_POST["sakujoBango"];
                $pass2 = $_POST["pass2"];
                //削除番号のデータを選び、パスワードを比較する
                $id = $sakujoBango;
                $sql = 'SELECT * FROM tables WHERE id=:id ';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->execute();                             // ←SQLを実行する。
                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    if($pass2 == $row['password']){
                        $sql = 'delete from tables where id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
            //編集対象番号とパスワードが入力された場合
            elseif(!empty($_POST["henshuBango"]) && !empty($_POST["pass3"]) && isset($_POST["edit"])){
                $henshuBango = $_POST["henshuBango"];
                $pass3 = $_POST["pass3"];
                
                $id = $henshuBango;
                $sql = 'SELECT * FROM tables WHERE id=:id ';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->execute();                             // ←SQLを実行する。
                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    if($pass3 == $row['password']){
                        $editNumber = $row['id'];
                        $editName = $row['name'];
                        $editComment = $row['comment'];
                        $editPassword = $row['password'];
                    }
                }
            }
        ?>
        
        <form action = "" method = "post">
            <input type = "hidden" name = "num" value = "<?php echo $editNumber; ?>">
            <input type = "text" name = "name" placeholder = "名前" value = "<?php echo $editName; ?>"><br>
            <input type = "text" name = "comment" placeholder = "コメント" value = "<?php echo $editComment; ?>"><br>
            <input type = "password" name = "pass1" placeholder = "パスワード" value = "<?php echo $editPassword; ?>">
            <input type = "submit" name = "submit"><br><br>
            <input type = "text" name = "sakujoBango"  placeholder = "削除対象番号"><br>
            <input type = "password" name= "pass2" placeholder = "パスワード">
            <input type = "submit" name = "delete" value = "削除"><br><br>
            <input type = "text" name = "henshuBango"  placeholder = "編集対象番号"><br>
            <input type = "password" name= "pass3" placeholder = "パスワード">
            <input type = "submit" name = "edit" value = "編集">
        </form>
        
        <br><br>
        <?php
            $sql = 'SELECT * FROM tables';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['password'].'<br>';
                echo "<hr>";
            }
        ?>
    </body>
</html>
