<?php
class DatabaseTable {
    public function __construct(private PDO $pdo, private string $table, private string $primaryKey) {
    }

    public function totalGeneric() {
      $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM `' .  $this->table . '`');
          $stmt->execute();
      $row = $stmt->fetch();
      return $row[0];
    }

    public function findGeneric($field, $value) {
      $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $field . '` = :value';
  
      $values = [
          'value' => $value
      ];
  
      $stmt = $this->pdo->prepare($query);
      $stmt->execute($values);
      return $stmt->fetchAll();
    }

    private function insertGeneric($values) {
      $query = 'INSERT INTO `' . $this->table . '` (';
  
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
  
      $values = $this->processDates($values);
  
          $stmt = $this->pdo->prepare($query);
          $stmt->execute($values);
    }

    private function updateGeneric($values) {

      $query = ' UPDATE `' . $this->table .'` SET ';
  
      foreach ($values as $key => $value) {
          $query .= '`' . $key . '` = :' . $key . ',';
      }
  
      $query = rtrim($query, ',');
  
      $query .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';
  
      // Set the :primaryKey variable
      $values['primaryKey'] = $values['id'];
  
      $values = $this->processDates($values);
  
          $stmt = $this->pdo->prepare($query);
          $stmt->execute($values);
    }

    public function deleteGeneric($field, $value) {
      $values = [':value' => $value];

      $stmt = $this->pdo->prepare('DELETE FROM `' . $this->table . '` WHERE `' . $field . '` = :value');

      $stmt->execute($values);
    }

    public function findAllGeneric() {
      $stmt = $this->pdo->prepare('SELECT * FROM `' . $this->table . '`');
          $stmt->execute();
  
      return $stmt->fetchAll();
    }

    private function processDates($values) {
      foreach ($values as $key => $value) {
          if ($value instanceof DateTime) {
              $values[$key] = $value->format('Y-m-d H:i:s');
          }
      }
  
      return $values;
    }

    public function saveGeneric($record) {
      try {
          if (empty($record[$this->primaryKey])) {
            unset($record[$this->primaryKey]);
          }
          $this->insertGeneric($record);
      }
      catch (PDOException $e) {
        $this->updateGeneric($record);
      }
    }
}