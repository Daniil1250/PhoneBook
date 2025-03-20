<!doctype html>
  <html>
<head>
<title>Телефонный справочник.</title>
<style>
    body{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

.popup{
  background: #1f1f1f;
  border: 1px solid rgb(255, 0, 0);
  border-radius: 23px;
  width: 100%;
  max-width: 500px;
  height: 100%;
  max-height: 450px;
  padding: 20px;
  color: white;
  text-align: center;
  display: none; 
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%); 
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0,0,0,0.3);

}

.popup {
  animation: fadeIn 0.3s ease-in-out forwards;

}

.popup.active {
  animation: none; 
}

.popup.closing {
  animation: fadeOut 0.3s ease-in-out forwards;
}


@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.8); }
  to { opacity: 1; transform: scale(1); }
}

@keyframes fadeOut {
  from { opacity: 1; transform: scale(1); }
  to { opacity: 0; transform: scale(0.8); }
}

.popup p{
  cursor: pointer;
   font-size: 23px;
   position: fixed;
   right: 20px;
   margin-top: -15px;

}
.TitlePopup h3{
  font-family: 'FontBold';
  font-size: 20px;
}


#overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.863); 
  z-index: 999; 
}

.AddUserStyle{
    display: flex;
    flex-direction: column;
    max-width: 230px;
    margin: 0 auto;
}

.AddUserStyle input{
    outline: none;
    border: 1px solid aqua;
    background: none;
    border-radius: 23px;
    padding-left: 15px;
    padding-bottom: 5px;
    color: white;
}

.AddUserStyle button{
    color: white;
    background: none;
    border: 1px solid aqua;
    padding: 5px 10px 5px 10px;
    box-shadow: 5px 5px black;
}


</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<h2>Список контактов.</h2>
<br>

                    <form method='get'>
                <input type="text" name='q' placeholder="Поиск..."/>
                <input type="submit" value="Найти"/>
             </form>

             <br><br>

            <span class="flex-grow-1">
            <?php

            include('connect.php');
            session_start();
        
            $searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

            if (!empty($searchQuery)) {
                try {
                    $stmt = $conn->prepare("SELECT * FROM people WHERE firstname LIKE ?");
                    $stmt->execute(["%{$searchQuery}%"]);
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($results) > 0) {
                    
                        foreach ($results as $user) {
                        echo "<div class='card mb-5' style='border: 2px solid black; border-radius: 25px; height: 130px; width: 320px;'>";
                        echo "<div class='card-body p-1 d-flex align-items-center'>"; 
                        echo "<img src='Basic_Ui__28186_29.jpg' width='65px' height='65px' class='ms-2 mb-4'>";
                        echo "<div>";
                        echo "<div class='card-body p-4 d-flex flex-column'>";       
                        echo "<h2 class='card-title mb-0' style='letter-spacing: 0.5px;'>" . htmlspecialchars($user['firstname']) . "</h2>";

                        if ($user['active'] === 'success') {
                            echo "<img src='16799038.png' width='50px' height='50px' class='mt-2'>"; 
                        } else {
                            echo "<form method='post' action='' id='form-" . $user['id'] . "'>";
                            echo "<input type='hidden' name='user_id' value='" . $user['id'] . "'>";
                            echo "<button type='submit' name='activate' class='btn btn-primary mt-2'>Активировать</button>"; 
                            echo "</form>";
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";


                        }
                    
                    } else {
                        echo "<center><h3>Пользователи не найдены.</h3></center>";
                    }
                } catch (PDOException $e) {
                    die("Ошибка выполнения запроса: " . $e->getMessage());
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['activate'])) {
                $userIdToActivate = $_POST['user_id'];

                try {
                    $updateStmt = $conn->prepare("UPDATE people SET active = 'success' WHERE id = ?");
                    $updateStmt->execute([$userIdToActivate]);
                } catch (PDOException $e) {
                    die("Ошибка активации аккаунта: " . $e->getMessage());
                }
            }

?>
         </span>

         <br>
<a id="openPopup" style="color: black; cursor: pointer;">Добавить Usera</a>
<!-- ---------------------------------------------------------------------------------------------------------------------------------- -->
<div id="overlay"></div> 
<div class="popup" id="myPopup">
<p id="closePopup">X</p>
    <header>
        <div class="TitlePopup">
           <h3>Добавление клиента</h3>
           <br>
        </div>
    </header>
            <main>
                <div class="AddUserStyle">
                <form action="addUser.php" method="post">
                <input type="text" name="lastname" placeholder="Должность">
                <br><br>
                <input type="text" name="firstname" placeholder="Фамилия">
                <br><br>
                <input type="text" name="title" placeholder="Номер кабинета">
                <br><br>
                <input type="text" name="location" placeholder="Адрес">
                <br><br>
                <button type="submit" name="ButtinAddUser">Добавить</button>
                </div>
                </form>
            </main>
        </div>
    </main>
   </div>



</body>
</html>
<script>
    const openPopupButton = document.getElementById('openPopup');
  const popup = document.getElementById('myPopup');
  const overlay = document.getElementById('overlay');
  const closePopupButton = document.getElementById('closePopup');

  openPopupButton.addEventListener('click', () => {
  popup.style.display = 'block';
  popup.classList.add('active'); 
  overlay.style.display = 'block';
});

closePopupButton.addEventListener('click', () => {
  popup.classList.add('closing');
  setTimeout(() => {
    popup.classList.remove('closing');
    popup.style.display = 'none';
    overlay.style.display = 'none';
  }, 300);
});
</script>