<?php
  function checkLogin(){
      session_start();
      if(!isset($_SESSION["username"])){
          header("Location: index.php");
          exit;
      }
  }

  function getdb(){
      $db = new SQLite3('../www-data-dir/scores.sqlite');
      return $db; 
  }


  function init_db() {
      global $db;
      $res = $db->querySingle("SELECT count(name) FROM sqlite_master WHERE type='table' AND name='accounts';");
      if ($res==0) {  // table does not exist yet, so create it
        $db->exec('CREATE table accounts(id integer primary key not null, USERNAME text not null default "Noname", PASSWORD text not null)');
        // $db->exec('insert into scores (name, score) values ("N00b", 13), ("r00ki3", 21), ("cr4ck", 55), ("pr0", 34)');
      }
    }

  function countRows($result){
    $numRows = 0;
    while ($rows = $result->fetchArray()){
      ++$numRows;
    }
    $result->reset();
    return $numRows;
  }
?>  