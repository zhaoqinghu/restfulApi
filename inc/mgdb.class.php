<?php
class mgdb
{
	protected $connection;
	protected $collection;
	protected $mgdb;
	function __construct($host,$dbname,$repset = null)
	{
		$this->connect($host,$dbname,$repset);
	}
	protected function connect($host,$dbname,$repset=null)
	{
		$mongourl = "mongodb://{$host}/";
		if ($repset)		
			$mgdb = new MongoDB\Driver\Manager($mongourl,array("replicaSet" => $repset));	
		else
			$mgdb = new MongoDB\Driver\Manager($mongourl);


		$this->connection = $dbname;		
		$this->mgdb = $mgdb;		
	}
	public function getconnection()
	{
		return $this->connection;
	}
	protected function getcollection($table)
	{
		$this->collection =  $this->connection.'.'.$table;
		//return $this->collection;
	}
	public function id($obj)
	{
		if (is_string($obj)) return new MongoDB\BSON\ObjectID($obj);
		if (is_array($obj)) return $obj['_id'];
		return new MongoId($obj->_id);
	}
	public function select($table,$condition = array(), $orderby = null,$options = array('skip' => 0,'limit' => 2000,'fields' => array()))
	{
		$this->getcollection($table);
		
		if ($options == '')
			$options = array('skip' => 0,'limit' => 2000,'fields' => array());
		
		if(!empty($options['fields']))
		{
			$options['projection'] = $options['fields'];
			unset($options['fields']);
		}
		if(!empty($orderby))
		{
			$options['sort'] = $orderby;
		}
		$condition = $this->parsecondition($condition);
		$query = new MongoDB\Driver\Query($condition, $options);
		$cursor = $this->mgdb->executeQuery($this->collection, $query);
		$cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
		$result = array();
		foreach($cursor as $key=>$val)
		{
			$result[(string)$val['_id']] = $val;
		}
		return $result;
	}
	public function selectOne($table,$condition = null)
	{
		if(!is_array($condition))
		{
			if($condition != "")
			{
				$condition = array('_id'=>new MongoDB\BSON\ObjectID($condition));
			}
			else
			{
				$condition = array();
			}
		}
		$this->getcollection($table);
		$options = array('limit'=>1);
		$query = new MongoDB\Driver\Query($condition,$options);
		try{
			$cursor = $this->mgdb->executeQuery($this->collection, $query);
		}catch(MongoDB\Driver\Exception\ConnectionTimeoutException $e){
			return null;
		}
		$cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
		$result = $cursor->toArray();
		return $result[0];
	}
	/**
	*$table String(demo value: TUser)
	*$keys Array(demo value: array('FCompany'=>true))
	*$initial Array(demo value: array('items'=>array()))
	*$reduce String(demo value: function(doc, prev){prev.items.push(doc.FUserName);} OR new MongoDB\BSON\JavaScript('function(doc, prev){prev.items.push(doc.FUserName);}'))
	*
	*/
	public function group($table,$keys,$initial,$reduce,$option=array())
	{
		$command = array(
			'group' => array(
				'ns'=>$table,
				'$reduce'=>$reduce,
				'key'=>$keys,
				'cond'=>$option,
				'initial'=>$initial 
			)
		);
		$cmd = new MongoDB\Driver\Command($command);
 		$cursor = $this->mgdb->executeCommand($this->connection, $cmd);
		$cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
		$result = $cursor->toArray();
		return $result[0];
	}

	public function count($table,$condition = null)
	{
		$command = array(
			'count' => $table,
			'query'=>$condition,
		);
		$cmd = new MongoDB\Driver\Command($command);
 		$cursor = $this->mgdb->executeCommand($this->connection, $cmd);
		$cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
 		$result = $cursor->toArray();
 		return $result[0]['n'];
	}

	public function insert($table,$data)
	{
		$this->getcollection($table);

		$bulk = new MongoDB\Driver\BulkWrite();
		$data = $this->object_to_array($data);
		$bulk->insert($data);
		$this->mgdb->executeBulkWrite($this->collection,$bulk);
	}
	public function update($table,$data,$condition = null,$options = array())
	{
		$this->getcollection($table);
		$data = $this->object_to_array($data);
		$bulk = new MongoDB\Driver\BulkWrite();
		$op['upsert'] = false;
		$op['multi'] = false;
		if(isset($options['upsert']))
		{
			if($options['upsert'] == true)
			{
				$op['upsert'] = true;
			}
			
		}
		if(isset($options['multiple']))
		{
			if($options['multiple'] == true)
			{
				$op['multi'] = true;
			}
		}
		if(!is_array($condition))
		{
			if($condition != "")
			{
				$condition = array('_id'=>$this->id($condition));
			}
			else
			{
				$condition = array();
			}
		}
		$data = array('$set'=>$data);
		$bulk->update($condition,$data,$op);
		$result = $this->mgdb->executeBulkWrite($this->collection, $bulk);
		return is_object($result);
	}
	public function delete($table,$condition = null,$justOne = false)
	{
		$this->getcollection($table);
		if(!is_array($condition))
		{
			if($condition != "")
			{
				$condition = array('_id'=>$this->id($condition));
			}
			else
			{
				$condition = array();
			}
		}
		$limit = 0;
		if($justOne == true)
		{
			$limit = 1;
		}
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->delete($condition,['limit'=>$limit]);
		$result = $this->mgdb->executeBulkWrite($this->collection, $bulk);
		return is_object($result);
	}
	public function parsecondition($condition = null)
	{
		if(!$condition) return array();
		$new_condition = array();
		if(is_array($condition)) return $condition;
		foreach($condition AS $key => $value)
		{
			$n_v = $this->new_array($key,$value);
			if(!$new_condition[$n_v['key']])
			{
				$new_condition[$n_v['key']] = $n_v['value'];
			}else
			{
				$new_condition[$n_v['key']] += $n_v['value'];
			}
	
		}
		return $new_condition;
	}

	public function new_array($key,$value)
	{
		$where_string = array('>','>=','<','<=','=','!=');
		$w_string = array('>' => '$gt','>=' => '$gte','<' => '$lt','<=' => '$lte','!=' => '$ne');
		$diff = preg_replace('/([a-zA-Z0-9_]+?)/ei','',$key);
		if(!$diff) return array('key'=> $key,'value' => $value);
		if(in_array($diff,$where_string))
		{
			$narray = array();
			$name = substr($key,0,strlen($key) - strlen($diff));
			if($diff == '=') return array('key'=> $key,'value' => $value);
			return array('key' => $name,'value' => array($w_string[$diff] => $value));
		}else
		{
			return array('key'=> $key,'value' => $value);
		}
	}
	private function object_to_array($obj) 
	{ 
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj; 
		foreach ($_arr as $key => $val) 
		{ 
			if($key != '_id')
			{
				$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val; 
				$arr[$key] = $val; 
			}
			else
			{
				$arr[$key] = $val; 
			}
		} 
		return $arr; 
	}
	
}

function DB_MongoRegex($regular) 
{
	$regular_a = substr($regular,1,strrpos($regular,'/')-1);
	$regular_b = substr($regular,strrpos($regular,'/')+1);
	return new \MongoDB\BSON\Regex($regular_a,$regular_b);
	
}
?>