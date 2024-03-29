<?php
namespace Generic;
class DatabaseTable {
    public function __construct(private \PDO $pdo, private string $table, private string $primaryKey, private string $className = '\stdClass', private array $constructorArgs = []) {
    }

    public function totalGeneric($values) {
      $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE ';

      foreach ($values as $key => $value) {
        $query .= '`' . $key . '` = :' . $key . ' AND ';
      }

      $query = rtrim($query, ' AND ');
    
      $stmt = $this->pdo->prepare($query);

      $stmt->execute($values); 

      $row = $stmt->fetch();

      return $row['COUNT(*)'];
    }

    public function findGeneric(string $field, string $value, string $orderBy = null, int $limit = 0) {
      $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $field . '` = :value';
  
      $values = [
          'value' => $value
      ];

      if ($orderBy != null) {
        $query .= ' ORDER BY ' . $orderBy;
      }

      if ($limit > 0) {
          $query .= ' LIMIT ' . $limit;
      }
  
      $stmt = $this->pdo->prepare($query);
      $stmt->execute($values);

      return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
    }

    public function searchGeneric($values, $others, string $orderBy = null, int $limit = 0, int $offset = 0) {
      $query = 'SELECT * FROM `' . $this->table . '` WHERE ';

      $mergedValues = [];

      if (!empty($values)) {
        
        $query .= '(';
        
        foreach ($values as $key => $value) {
          $query .= '`' . $key . '` LIKE :' . $key . ' OR ';
        }

        $query = rtrim($query, ' OR ');
        $query .= ')';
        
        $mergedValues = array_merge($mergedValues, $values);
      }

      if (!empty($others)) {
        if (!empty($values)) {
          $query .= ' AND (';
        }

        foreach ($others as $key => $value) {
          $query .= '`' . $key . '` = :' . $key . ' AND ';
        }

        $query = rtrim($query, ' AND ');

        if (!empty($values)) {
          $query .= ') ';
        }
        
        $mergedValues = array_merge($mergedValues, $others);
      }

      if ($orderBy != null) {
        $query .= ' ORDER BY ' . $orderBy;
      }

      if ($limit > 0) {
          $query .= ' LIMIT ' . $limit;
      }

      if ($offset > 0) {
        $query .= ' OFFSET ' . $offset;
      }
  
      $stmt = $this->pdo->prepare($query);

      $stmt->execute($mergedValues);

      return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
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
          
          return $this->pdo->lastInsertId();
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

    public function findAllGeneric($orderBy = null, int $limit = 0, int $offset = 0) {
      $query = 'SELECT * FROM `' . $this->table . '`';

      if ($orderBy != null) {
        $query .= ' ORDER BY ' . $orderBy;
      }

      if ($limit > 0) {
          $query .= ' LIMIT ' . $limit;
      }

      if ($offset > 0) {
        $query .= ' OFFSET ' . $offset;
      }

      $stmt = $this->pdo->prepare($query);

      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
    }

    private function processDates($values) {
      foreach ($values as $key => $value) {
          if ($value instanceof \DateTime) {
              $values[$key] = $value->format('Y-m-d H:i:s');
          }
      }
  
      return $values;
    }

    public function saveGeneric($record) {
      $entity = new $this->className(...$this->constructorArgs);
      try {
          if (empty($record[$this->primaryKey])) {
            unset($record[$this->primaryKey]);
          }
          
          $insertId = $this->insertGeneric($record);

          $entity->{$this->primaryKey} = $insertId;
      }
      catch (\PDOException $e) {
        if(str_contains($e->getMessage()," Duplicate entry ")){
          $this->updateGeneric($record);
        }else{
          exit();
        };
      }

      foreach ($record as $key => $value) {
        if (!empty($value)) {
          if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d H:i:s');
          }
          $entity->$key = $value;
        }
      }

      return $entity;
    }
}