<!--
Con My Agenda è possibile creare, modificare lo stato e rimuovere delle task.
Con l'opzione Drag and Drop è possibile spostare le casselle create in tutta la pgina.
My Agenda sfrutta JQuery per avere delle animazioni più fluide.


Per poter accedere alla sorgente MySQL consultare db_conn.php.
Enjoy!
-->

<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-section">
        <h1>Cosa facciamo oggi?</h1>
        <br>
       <div class="add-section">
          <form action="app/add.php" method="POST" autocomplete="off">
             <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
                <input type="text"
                     name="title"
                     style="border-color: #ff6666"
                     placeholder="Stiamo diventando pigri?" />
              <button type="submit">ADD HERE</button>

             <?php }else{ ?>
              <input type="text"
                     name="title"
                     placeholder="Che impegni abbiamo oggi?" />
              <button type="submit">ADD HERE</button>
             <?php } ?>
          </form>
       </div>
       <?php
          $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
       ?>
       <div class="show-todo-section">
            <?php if($todos->rowCount() <= 0){ ?>
                <div class="img">
                    <div class="empty">
                        <img src="img/f.png" width="100%" />
                    </div>
                </div>
            <?php } ?>

            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
              <div id="classe">
              <div class="todo-item">
                    <span id="<?php echo $todo['id']; ?>"
                          class="remove-to-do">x</span>
                    <?php if($todo['checked']){ ?>
                        <input type="checkbox"
                               class="check-box"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               checked />
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php }else { ?>
                        <input type="checkbox"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <small>Creato il: <?php echo $todo['date_time'] ?></small>
                </div>
            </div>
            <?php } ?>
       </div>
    </div>

    <div id="dropzone"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- Also include jQueryUI -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script>
        $("#classe").draggable();
        $("#dropzone").droppable({
            drop: function(event, ui) {
                $(this).css('background', 'rgb(0,200,0)');
            },
            over: function(event, ui) {
                $(this).css('background', 'orange');
            },
            out: function(event, ui) {
                $(this).css('background', 'cyan');
            }
        });
    </script>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');

                $.post("app/remove.php",
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');

                $.post('app/check.php',
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });
        });
    </script>
</body>
</html>
