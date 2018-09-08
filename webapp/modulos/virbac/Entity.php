<?php 

//namespace virbac;
/**
* Enity for match two data bases
*/
abstract class EntitySyncDB 
{
	private $keys;
	private $srcDB;
	private $desDB;

	public function __construct( /*array $DBsInfo ,*/  array $desDB , array $srcDB , /*array $attr,*/ array $keys)
	{
		/*$this->ATRIBUTES = $attr;*/
		$this->srcDB = $srcDB;
		$this->desDB = $desDB;
		$this->keys = $keys;
		
		$primarykey = "";
		$sql = "CREATE TABLE IF NOT EXISTS interchange_index_" .get_class($this). " (\n\t" ;
		foreach ($keys['src'] as $k => $v) {
			if( $k != 0) {
				$primarykey .= ", $v";
				$sql .= ",\n\t$v varchar(100) ";
			}
			else {
				$primarykey .= "$v";
				$sql .= "$v varchar(100) ";
			}
		}
		foreach ($keys['dest'] as $k => $v) {
			$sql .= ",\n\t$v varchar(100) ";
		}
		$sql .=",\n\tPRIMARY KEY ($primarykey)\n );\n";
		
		$this->desDB[0]->prepare($sql)->execute();
	}

	public function sync() 
	{
		$result = true;
		try {
			//$this->srcDB[0]->beginTransaction();
			//$this->desDB[0]->beginTransaction();

			$this->result = $this->getFromSRC();
			if(  $this->result ){
				foreach ($this->result as $key => $row) {
					var_dump($row);
					$rowKey = $this->existRow($row);
					$this->deleteDuplicateColumns($row);
					if($rowKey) {
						//Update
						$this->setToDEST($row);
					} else {
						//Insert
						$this->appendToDEST($row);
					}
				}
			} 

		//$this->srcDB[0]->commit();
		//$this->desDB[0]->commit();

		} catch (PDOException $e) {
		//$this->srcDB[0]->rollBack();
		//$this->desDB[0]->rollBack();
			$result = false;
			throw new Exception('Sync Error: ' . $e->getMessage() . PHP_EOL , 1);
			
    		//echo 'Error!: ' . $e->getMessage() . PHP_EOL;
		} finally {
		    //$this->srcDB = null;
		    //$this->desDB = null;
		}
		return $result;
	}

	private function existRow($row)
	{
		$sql = $this->constructQueryExistKey($row);
		$res = $this->desDB[0]->query($sql);
		return ( $res ) ? $res->fetch() : false;
	}
	private function constructQueryExistKey($row)
	{
		$dest = "";
		foreach ($this->keys['dest'] as $k => $v) {
			if($k != 0)
				$dest .= ", $v ";
			else
				$dest .= "$v ";
		}
		$src = "";
		foreach ($this->keys['src'] as $k => $v) {
			if($k != 0)
				$src .= ", $v = {$row[$v]}";
			else
				$src .= "$v = {$row[$v]}";
		}
		$sql = "SELECT	{$dest}\n
				FROM	interchange_index_" .get_class($this). "\n
				WHERE	{$src} ;";
		return $sql;
	}
	private function deleteDuplicateColumns(&$row) 
	{
		foreach ($row as $key => $value) {
			if( ! preg_match("/^\d+$/", $key))
				unset( $row["$key"] );
		}
	}
	

	private function getPrimaryKeySRC($row)
	{
		
	}
	private function appendToIndex($row)
	{
		$sql = $this->constructSentenceInsertIndex();

		$sentence = $this->desDB[0]->prepare($sql);
		return $sentence->execute($row);
	}
	private function constructSentenceInsertIndex()
	{
		$namesColumns = "";
	    $columnsBind = "";
	    
	    
	    $tableName = "interchange_index_".get_class($this);
		foreach (current( $this->keys['src'] ) as $key => $column) {
			if($key != 0) {
		    		$namesColumns .= ",$column";
		    		$columnsBind .= ",?";
		    	}
		    	else {
		    		$namesColumns .= "$column";
		    		$columnsBind .= "?";
		    	}
		}
		return "INSERT INTO $tableName ({$namesColumns}) VALUES ({$columnsBind})";
	}
	private function setToIndex($row)
	{
		$sql = $this->constructSentenceUpdateIndex();

		$sentence = $this->desDB[0]->prepare($sql);
		return $sentence->execute($row);
	}
	private function constructSentenceUpdateIndex(){
	    $columnsBind = "";
	    
	    $tableName = "interchange_index_".get_class($this);
		foreach (current( $this->keys['src'] ) as $key => $column) {
			if($key != 0) 
		    		$columnsBind .= ",$column='?'";
		    	else 
		    		$columnsBind .= "$column='?'";
		}
		echo "UPDATE $tableName SET {$columnsBind}"; die;
		return "UPDATE $tableName SET {$columnsBind}";
	}


	private function getFromSRC()
	{
		$sql = $this->constructQuerySelect();
		return $this->srcDB[0]->query($sql);
	}
	private function constructQuerySelect() {
		$ncol = 0;
		$select = "SELECT	";
		$from = "";
		$where = "\nWHERE	1=1 ";
		$firstTable = array_keys($this->srcDB['tables'])[0];
		foreach ($this->srcDB['tables'] as $namesTable => $columns) {

			if($namesTable == $firstTable) {
				$from .= "\nFROM	{$namesTable}";
				foreach ($columns as $key => $value) {
					if ( preg_match ( "/^'/" , $value ) ) {				
						if($key != 0)
							$select .= ", {$value} ";
						else
							$select .= " {$value} ";

					} else {
						if($key != 0)
							$select .= ", {$namesTable}.{$value} ";
						else
							$select .= " {$namesTable}.{$value} ";
					}
					$ncol++;
				}
	    	}
	    	else {
				//FIXED: Append condition for multiple ON 
	    		$from .= "\nINNER JOIN {$namesTable} ON ";
	    		$nON = 0;
	    		foreach ($this->srcDB['join'] as $key => $value) {
	    			$val = explode("=", $value);
	    			if( preg_match("/$namesTable/", $val[1]) ) {
	    				if($nON != 0)
	    					$from .= "AND $value ";
	    				else
	    					$from .= "$value ";
	    				$nON++;
	    			}
	    		}

				foreach ($columns as $key => $value) {
					if ( preg_match ( "/^'/" , $value ) ) {				
						$select .= ", {$value} {$this->ATRIBUTES[$ncol]}";
					} else {
						$select .= ", {$namesTable}.{$value} {$this->ATRIBUTES[$ncol]}";
					}
					$ncol++;
				}
	    	}
		}

		foreach ($this->srcDB['where'] as $key => $value) {
			$where .= "AND $value ";
		}
		return $select . $from . $where . "\n";
	}
	private function appendToDEST($row)
	{
		$sql = $this->constructSentenceInsert();

		$sentence = $this->desDB[0]->prepare($sql);
		return $sentence->execute($row);
	}
	private function constructSentenceInsert()
	{
		$namesColumns = "";
	    $columnsBind = "";
	    
	    current( $this->desDB['table'] );
	    $tableName = key($this->desDB['table']);
		foreach (current( $this->desDB['table'] ) as $key => $column) {
			if($key != 0) {
		    		$namesColumns .= ",$column";
		    		$columnsBind .= ",?";
		    	}
		    	else {
		    		$namesColumns .= "$column";
		    		$columnsBind .= "?";
		    	}
		}
		return "INSERT INTO $tableName ({$namesColumns}) VALUES ({$columnsBind})";
	}
	private function setToDEST($row)
	{
		$sql = $this->constructSentenceUpdate();

		$sentence = $this->desDB[0]->prepare($sql);
		return $sentence->execute($row);
	}
	private function constructSentenceUpdate(){
	    $columnsBind = "";
	    
	    current( $this->desDB['table'] );
	    $tableName = key($this->desDB['table']);
		foreach (current( $this->desDB['table'] ) as $key => $column) {
			if($key != 0) 
		    		$columnsBind .= ",$column='?'";
		    	else 
		    		$columnsBind .= "$column='?'";
		}
		echo "UPDATE $tableName SET {$columnsBind}"; die;
		return "UPDATE $tableName SET {$columnsBind}";
	}

}