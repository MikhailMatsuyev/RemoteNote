<?php
class ModelIndex extends Model
{
    private $var_model_index;


    public function findNoteById($id)
    {
      try
        {
            // Открытие БД
            $db_path='d:/gbook.db';
            $db = new PDO("sqlite:$db_path");
            // Подготовка данных

            //$date = time();
            // Запрос
            $query = $db->prepare("SELECT * FROM `gbook` WHERE (`id`= (?)) LIMIT 1");
            $query->bindParam(1, $id);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $row;

    }

    public function updateExistNote($data_from_contr)
    {
        $name = htmlspecialchars($data_from_contr["txtName"]);
        $txt = htmlspecialchars($data_from_contr["txtMessage"]);
        $id = (int)(htmlspecialchars($data_from_contr["id"]));

        try {
            $db_path = 'd:/gbook.db';
            $db = new PDO("sqlite:$db_path");

            $query = $db->prepare("UPDATE gbook SET
                          postname = :postname,
                          message = :message
                        WHERE 
                          id=:id"
            );

            $query->bindParam(':postname', $name);
            $query->bindParam(':message', $txt);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getModel()
    {
        $rawPost = file_get_contents('php://input');

// Заголовки ответа
        header('Content-type: text/plain; charset=utf-8');
        header('Cache-Control: no-store, no-cache');
        header('Expires: ' . date('r'));

// Если данные были переданы...
        if ($rawPost) {
            // Разбор пакета JSON
            $record = json_decode($rawPost);
            try
            {
                // Открытие БД
                $db_path='d:/gbook.db';
                $db = new PDO("sqlite:$db_path");
                // Подготовка данных
                $postname = htmlspecialchars($record->postname);
                $message = htmlspecialchars($record->message);
                $date = time();

                // Запрос
                $query=$db->prepare("INSERT INTO gbook (postname, message, date) VALUES (?, ?, ?)");
                $query->bindParam(1, $postname);
                $query->bindParam(2, $message);
                $query->bindParam(3, $date);

                $query->execute();

            }catch(PDOException $e){
                echo $e->getMessage();
            }
            // Возврат результата
            echo json_encode(
                array
                (
                    'result'          => 'OK',
                    'lastInsertRowId' => $db->lastInsertRowID()
                )
            );
        } else {
            // Данные не переданы
            echo json_encode(
                array
                (
                    'result' => 'No data'
                )
            );
        }
    }
}