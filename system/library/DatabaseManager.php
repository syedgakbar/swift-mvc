<?php

// Database
// Manager the Database Connection and handle the DB layer. Based on ideas and code from:
// http://net.tutsplus.com/tutorials/php/real-world-oop-with-php-and-mysql/

class DatabaseManager  
{  
    private $con = false;               // Checks to see if the connection is active
	private $conn_id = null;
    private $result = array();          // Results that are returned from the query
	private $db_name = '';
	
	/*
    * Checks to see if the table exists when performing
    * queries
    */
    private function TableExists($table)
    {
		$query = 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = "' . $this->db_name .'" AND table_name = "'.$table.'"';
        $tablesInDb = @mysql_query($query);
		
		if($tablesInDb)
        {
            return (mysql_num_rows($tablesInDb)==1);
        }
    }
	
	/**
	 * @access	public
	 * @param	string
	 * @param	bool	whether or not the string will be used in a LIKE condition
	 * @return	string
	 */
	private function escape_str($str, $like = FALSE)	
	{	
		if (is_array($str))
		{
			foreach($str as $key => $val)
	   		{
				$str[$key] = $this->escape_str($val, $like);
	   		}
   		
	   		return $str;
	   	}

		if (function_exists('mysql_real_escape_string'))
		{
			$str = mysql_real_escape_string($str);
		}
		elseif (function_exists('mysql_escape_string'))
		{
			$str = mysql_escape_string($str);
		}
		else
		{
			$str = addslashes($str);
		}
		
		// escape LIKE condition wildcards
		if ($like === TRUE)
		{
			$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
		}
		
		return $str;
	}
	
	/**
	 * Escapes data based on type
	 * Sets boolean and null types
	 *
	 * @access	public
	 * @param	string
	 * @return	mixed		
	 */	
	private function escape($str)
	{
		if (is_string($str))
		{
			$str = "'".$this->escape_str($str)."'";
		}
		elseif (is_bool($str))
		{
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif (is_null($str))
		{
			$str = 'NULL';
		}

		return $str;
	}
	
    /*
     * Connects to the database, only one connection
     * allowed
     */
    public function Connect(&$config)
    {
        if(!$this->con)
        {
			// store the reference of the local database in this variable
			$this->db_name = $config->Mysql_Db;
            $myconn = @mysql_connect($config->Mysql_Host,$config->Mysql_User,$config->Mysql_Password);
							
            if(true)
            {
                $seldb = @mysql_select_db($config->Mysql_Db,$myconn);
				
                if($seldb)
                {
                    $this->con = true;
                    return true;
                }
                else
                    return false;
            }
            else
				die('Could not connect: ' . mysql_error());
        }
        else
        {
            return true;
        }
    }
	
	/*
    * Disconnect the open database connetion (if any)
    */
	public function Disconnect()  
	{  
		if($this->con)  
		{  
			if(@mysql_close())  
			{  
				$this->con = false;  
				return true;  
			}  
			else   
				return false;   
		}  
	}  
	
	/*
	* Execute the given SQL string
	*/
	function ExecuteSQL($sql)
	{
		return @mysql_query($sql);
	}

    /*
    * Selects information from the database.
    * Required: table (the name of the table)
    * Optional: rows (the columns requested, separated by commas)
    *           where (column = value as a string)
	*			group_by (columsn = value as string)
    *           order (column DIRECTION as a string)
    */
    public function Select($table, $cols = '*', $where = null, $group_by = null, $order = null)
    {
        $q = 'SELECT '.$cols.' FROM '.$table;
        if($where != null)
		{
			$whereFilter = "";
			foreach ($where as $column=>$value) { 
				$whereFilter = $whereFilter . $column . " = " . $this->escape($value) . " AND "; 
			} 
			$whereFilter = $whereFilter . " 1=1 ";
            $q .= ' WHERE '. $whereFilter;
		}
		if($group_by != null)
            $q .= ' GROUP BY '.$group_by;
        if($order != null)
            $q .= ' ORDER BY '.$order;

        $query = @mysql_query($q);
        if($query)
        {
            $this->numResults = mysql_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysql_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    // Sanitizes keys so only alphavalues are allowed
                    if(!is_int($key[$x]))
                    {
                        if(mysql_num_rows($query) > 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysql_num_rows($query) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
			
            return  $this->result;
        }
        else
        {
            return null;
        }
    }

    /*
    * Insert values into the table
    * Required: table (the name of the table)
    *           values (the values to be inserted)
    * Optional: rows (if values don't match the number of rows)
    */
    public function Insert($table,$values,$cols = null)
    {
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($cols != null)
            {
                $insert .= ' ('.$cols.')';
            }

            for($i = 0; $i < count($values); $i++)
            {
                if(is_string($values[$i]))
                    $values[$i] = $this->escape($values[$i]);
            }
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';
		
            $ins = @mysql_query($insert);

            if($ins)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /*
    * Deletes table or records where condition is true
    * Required: table (the name of the table)
    * Optional: where (condition [column =  value])
    */
    public function Delete($table,$where = null)
    {
        if($this->tableExists($table))
        {
            if($where == null)
            {
                $delete = 'DELETE '.$table;
            }
            else
            {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where;
            }
            $del = @mysql_query($delete);

            if($del)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Updates the database with the values sent
     * Required: table (the name of the table to be updated
     *           rows (the rows/values in a key/value array
     *           where (the row/condition in an array (row,condition) )
     */
    public function Update($table,$cols,$where)
    {
        if($this->tableExists($table))
        {
            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            for($i = 0; $i < count($where); $i++)
            {
                if($i%2 != 0)
                {
                    if(is_string($where[$i]))
                    {
                        if(($i+1) != null)
                            $where[$i] = '"'.$where[$i].'" AND ';
                        else
                            $where[$i] = '"'.$where[$i].'"';
                    }
                }
            }
            $where = implode('',$where);


            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($cols);
            for($i = 0; $i < count($cols); $i++)
            {
                if(is_string($cols[$keys[$i]]))
                {
                    $update .= $keys[$i].'="'.$cols[$keys[$i]].'"';
                }
                else
                {
                    $update .= $keys[$i].'='.$cols[$keys[$i]];
                }

                // Parse to add commas
                if($i != count($cols)-1)
                {
                    $update .= ',';
                }
            }
            $update .= ' WHERE '.$where;
            $query = @mysql_query($update);
            if($query)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
    * Returns the result set
    */
    public function GetResult()
    {
        return $this->result;
    }
}  

?>