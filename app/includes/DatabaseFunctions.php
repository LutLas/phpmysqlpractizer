<?php
    function processDates($values) {
      foreach ($values as $key => $value) {
          if ($value instanceof DateTime) {
              $values[$key] = $value->format('Y-m-d H:i:s');
          }
      }
  
      return $values;
    }

    function findAllGeneric($pdo, $table) {
      $stmt = $pdo->prepare('SELECT * FROM `' . $table . '`');
          $stmt->execute();
  
      return $stmt->fetchAll();
    }

    function deleteGeneric($pdo, $table, $field, $value) {
      $values = [':value' => $value];

      $stmt = $pdo->prepare('DELETE FROM `' . $table . '` WHERE `' . $field . '` = :value');

      $stmt->execute($values);
    }

    function insertGeneric($pdo, $table, $values) {
      $query = 'INSERT INTO `' . $table . '` (';
  
      foreach ($values as $key => $value) {
          $query .= '`' . $key . '`,';
      }
  
      $query = rtrim($query, ',');
  
      $query .= ') VALUES (';
  
      foreach ($values as $key => $value) {
          $query .= ':' . $key . ',';
      }
  
      $query = rtrim($query, ',');
  
      $query .= ')';
  
      $values = processDates($values);
  
          $stmt = $pdo->prepare($query);
          $stmt->execute($values);
    }

    function updateGeneric($pdo, $table, $primaryKey, $values) {

      $query = ' UPDATE `' . $table .'` SET ';
  
      foreach ($values as $key => $value) {
          $query .= '`' . $key . '` = :' . $key . ',';
      }
  
      $query = rtrim($query, ',');
  
      $query .= ' WHERE `' . $primaryKey . '` = :primaryKey';
  
      // Set the :primaryKey variable
      $values['primaryKey'] = $values['id'];
  
      $values = processDates($values);
  
          $stmt = $pdo->prepare($query);
          $stmt->execute($values);
    }

    function findGeneric($pdo, $table, $field, $value) {
      $query = 'SELECT * FROM `' . $table . '` WHERE `' . $field . '` = :value';
  
      $values = [
          'value' => $value
      ];
  
      $stmt = $pdo->prepare($query);
      $stmt->execute($values);
      return $stmt->fetchAll();
    }

    function totalGeneric($pdo, $table) {
      $stmt = $pdo->prepare('SELECT COUNT(*) FROM `' . $table . '`');
          $stmt->execute();
      $row = $stmt->fetch();
      return $row[0];
    }


      