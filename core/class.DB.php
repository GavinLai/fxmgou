<?php
/**
 * DB操作总接口类
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
class DB {
  
  // Indicates the place holders that should be replaced in query_callback().
  const DB_QUERY_REGEXP = '/(%d|%s|%%|%f|%b|%n)/';
  
  // Writable && Readonly(master && slave)
  const WRITABLE = 'write';
  const READONLY = 'read';
  
  /**
   * Record current db connection mode(WRITABLE or READONLY), default to 'write'
   * @var string
   */
  protected $_dbMode    = self::WRITABLE;
  
  /**
   * Driver Class Object
   * @var object array
   */
  protected $_driverObj = array(self::WRITABLE => null, self::READONLY => null);
  
  /**
   * DB Result Set
   * @var DbResult
   */
  protected $_query;
  
  /**
   * DB connection info
   * @var array
   */
  protected $_dbInfo = array(
    //DB writable connecting Info
    self::WRITABLE  => array(
      'host'    => 'localhost', // DB Connecting Host
      'port'    => 3306,        // DB Connecting Port
      'user'    => 'root',      // DB Connecting User
      'pass'    => '',          // DB Connecting Password
      'name'    => 'test',      // DB Connecting Name
      'charset' => 'utf8',      // DB charset
      'pconnect'=> 0,           // Whether Persistent Connection
    ),
    //DB readonly connecting Info
    self::READONLY => array(
      'host'    => 'localhost', // DB Connecting Host
      'port'    => 3306,        // DB Connecting Port
      'user'    => 'root',      // DB Connecting User
      'pass'    => '',          // DB Connecting Password
      'name'    => 'test',      // DB Connecting Name
      'charset' => 'utf8',      // DB charset
      'pconnect'=> 0,           // Whether Persistent Connection
    )
  );
  
  /**
   * Default Config Set
   * @var array
   */
  protected $_config = array(
      'driverType'   => 'mysql',  // DB Driver Type, maybe mysql, mysqli...
      'tablePrefix'  => 'tb_',    // Table prefix
      'connTimeout'  => 5,        // Connect timeout(seconds)
      'pingInterval' => 5,        // Ping interval(seconds)
  );
  
  /**
   * DB Write && Read whether the same connection
   * @var bool
   */
  protected $_isSameWR = FALSE;
  
  /**
   * The singleton instance
   * @var DB
   */
  protected static $_instance;
  
  /**
   * The current submiting query SQL string, read only
   * @var string
   */
  public $qstring = '';
  
  /**
   * Whether realtime query
   * @var bool
   */
  public $realtime_query = FALSE;
  
  /**
   * Get the singleton instance
   * @param array $config_write, required
   * @param array $config_read, optional
   * @param array $config_extra, optional
   * @return DB
   */
  public static function I(Array $config_write = array(), Array $config_read = array(), Array $config_extra = array()) {
    if ( !isset(self::$_instance) ) {
      self::$_instance = new self($config_write, $config_read, $config_extra);
    }
    return self::$_instance;
  }
  
  /**
   * Constructor
   * @param array $config_write, required
   * @param array $config_read, optional
   * @param array $config_extra, optional
   */
  public function __construct(Array $config_write = array(), Array $config_read = array(), Array $config_extra = array()) {
    if (empty($config_write) && empty($config_read)) {
      throw new DbException('parameter \'config_write\' or \'config_read\' required.');
    }
    
    $this->_isSameWR = FALSE;
    if (empty($config_read)) {
      $this->_isSameWR = TRUE;
      $config_read = $config_write;
    }
    if (empty($config_write)) {
      $this->_isSameWR = TRUE;
      $config_write = $config_read;
    }
    $this->_dbInfo[self::WRITABLE] = array_merge($this->_dbInfo[self::WRITABLE], $config_write);
    $this->_dbInfo[self::READONLY] = array_merge($this->_dbInfo[self::READONLY], $config_read);
    $this->_config                 = array_merge($this->_config, $config_extra);
    if (!$this->_isSameWR && $this->isSameConnection($this->_dbInfo[self::WRITABLE], $this->_dbInfo[self::READONLY])) {
      $this->_isSameWR = TRUE;
    }
    
    $driverType = $this->driverType;
    unset($this->driverType);
    if ($driverType == 'mysql') { //mysql driver interface
      $this->_driverObj[self::WRITABLE] = new DbMysql($this->_config);
      $this->_driverObj[self::READONLY] = $this->_driverObj[self::WRITABLE];
      if (!$this->_isSameWR) {
        $this->_driverObj[self::READONLY] = new DbMysql($this->_config);
      }
    }
    elseif ($driverType == 'mysqli') { // mysqli driver interface
      $this->_driverObj[self::WRITABLE] = new DbMysqli($this->_config);
      $this->_driverObj[self::READONLY] = $this->_driverObj[self::WRITABLE];
      if (!$this->_isSameWR) {
        $this->_driverObj[self::READONLY] = new DbMysqli($this->_config);
      }
    }
    //Other driverType...
  }
  
  /**
   * Destructor
   */
  public function __destruct() {
    unset($this->_driverObj[self::READONLY], $this->_driverObj[self::WRITABLE]);
  }
  
  /**
   * Check whether the same connection configure
   * @param array $config1
   * @param array $config2
   * @return bool
   */
  protected function isSameConnection(Array $config1 = array(), Array $config2 = array()) {
    return (isset($config1['host']) && isset($config1['port']) && isset($config1['user'])
            && $config1['host']==$config2['host']
            && $config1['port']==$config2['port']
            && $config1['user']==$config2['user']
           ) ? TRUE : FALSE;
  }

  /**
   * Check server mode(WRITABLE or READONLY)
   *
   * @param string $qstring, query SQL string
   * @return string WRITABLE or READONLY
   */
  protected function check_server_mode($qstring) {
    return ($this->realtime_query || preg_match( '/^\s*(insert|delete|update|replace|create|alter|truncate|drop)\s/i', $qstring ))
           ? self::WRITABLE : self::READONLY;
  }
  
  /**
   * Get the current query server mode, can appoint a $server_mode for checking
   * @param string $server_mode
   * @return string, self::WRITABLE or self::READONLY
   */
  protected function getServerMode($server_mode = NULL) {
    $server_mode = !isset($server_mode) ? $this->_dbMode : $server_mode;
    if (!in_array($server_mode, array(self::WRITABLE,self::READONLY))) {
      throw new DbException("server mode '{$mode}' invalid.");
    }
    return $server_mode;
  }
  
  /**
   * Connect to db, and then set the link identifier
   *
   * @param string $server_mode, self::WRITABLE or self::READONLY
   * @return DB
   */
  public function connect($server_mode = self::WRITABLE) {
    $link_status = $this->_driverObj[$server_mode]->check_link();
    if (1==$link_status) { //no connection exists
      $dbinfo = $this->_dbInfo[$server_mode];
      $this->_driverObj[$server_mode]->connect($dbinfo['host'], $dbinfo['user'], $dbinfo['pass'], $dbinfo['name'], $dbinfo['port'], $dbinfo['charset'], $dbinfo['pconnect']);
    }
    elseif (2==$link_status) { //the previous connection is disconnection or bad
      $this->_driverObj[$server_mode]->close();
      $this->connect($server_mode); //reconnecting
    }
    return $this;
  }
  
  /**
   * Runs a basic query in the active database.
   *
   * User-supplied arguments to the query should be passed in as separate
   * parameters so that they can be properly escaped to avoid SQL injection
   * attacks.
   *
   * @param string $sql
   *   A string containing an SQL query.
   * @param ...
   *   A variable number of arguments which are substituted into the query
   *   using printf() syntax. Instead of a variable number of query arguments,
   *   you may also pass a single array containing the query arguments.
   *
   *   Valid %-modifiers are: %s, %d, %f, %b (binary data, do not enclose
   *   in '') and %%.
   *
   *   NOTE: using this syntax will cast NULL and FALSE values to decimal 0,
   *   and TRUE values to decimal 1.
   *
   * @return DbResult
   */
  public function query($sql) {
    if (is_array($sql)) {
      $args = $sql;
    }
    else {
      $args = func_get_args();
    }
    $sql = array_shift($args);
    
    $append = TRUE;
    if (is_bool($sql)) { //called by raw_query
      $append = FALSE;
      $sql = array_shift($args);
    }
    if (isset($args[0]) && is_array($args[0])) { // 'All arguments in one array' syntax
      $args = $args[0];
    }
    
    $server_mode = $this->check_server_mode($sql);
    $this->connect($server_mode);
    if ($append) {
      $sql = $this->append_prefix_tables($sql, $this->tablePrefix);
    }
    $this->query_callback($args, TRUE, $server_mode);
    $sql = preg_replace_callback(self::DB_QUERY_REGEXP, array($this,'query_callback'), $sql);
    $this->qstring = $sql;
    $this->_dbMode = $server_mode;
    $this->_query  = $this->_driverObj[$server_mode]->query($sql);
    return $this->_query;
  }
  
  /**
   * Like query method, just no do 'append_prefix_tables' action
   * 
   * @param mixed(string|array) $sql
   *   A string containing an SQL query.
   * @param ...
   *   A variable number of arguments which are substituted into the query
   *   using printf() syntax. Instead of a variable number of query arguments,
   *   you may also pass a single array containing the query arguments.
   *
   *   Valid %-modifiers are: %s, %d, %f, %b (binary data, do not enclose
   *   in '') and %%.
   *
   *   NOTE: using this syntax will cast NULL and FALSE values to decimal 0,
   *   and TRUE values to decimal 1.
   * @return DbResult
   */
  public function raw_query($sql) {
    if (is_array($sql)) {
      $args = $sql;
    }
    else {
      $args = func_get_args();
    }
    return $this->query(array_merge(array(FALSE),$args));
  }
  
  /**
   * Pager query
   *
   * @param string  $sqlquery, query sql string
   * @param integer $limit, limit num, optional
   * @param string  $sqlcnt, count sql stirng, optional
   * @param integer $element, pager index, optional
   * @return DbResult
   */
  public function pager_query($sqlquery, $limit = 30, $sqlcnt = NULL, $element = 0) {
    global 	$pager_totalrecord_arr,	// total record num array
            $pager_totalpage_arr, 	// total page num array
            $pager_currpage_arr; 		// current page no. array
  
    $pagername = 'p';
    $page = isset($_GET[$pagername]) ? $_GET[$pagername] : '';
  
    // Substitute in query arguments.
    $args = func_get_args();
    $args = array_slice($args, 4);
    // Alternative syntax for '...'
    if (isset($args[0]) && is_array($args[0])) {
      $args = $args[0];
    }
  
    // Construct a count query if none was given.
    if (!isset($sqlcnt)) {
      $sqlcnt = preg_replace(array('/SELECT.*?FROM /As', '/ORDER BY .*/'), array('SELECT COUNT(*) FROM ', ''), $sqlquery);
    }
  
    // We calculate the total of pages as ceil(items / limit).
    $pager_totalrecord_arr[$element] = $this->result($sqlcnt, $args);
    $pager_totalpage_arr[$element] 	 = ceil($pager_totalrecord_arr[$element] / $limit);
    if (is_numeric($page) || empty($page)) {
      $pager_currpage_arr[$element]  = max(1, min((int)$page, ((int)$pager_totalpage_arr[$element])));
    }
    else {
      if ($page == 'last') {
        $pager_currpage_arr[$element]= $pager_totalpage_arr[$element];
      }
      else {
        $pager_currpage_arr[$element]= 1;
      }
    }
    $start  = (($pager_currpage_arr[$element]-1) * $limit);
    $start  = $start>0 ? $start : 0;
    $sqlquery .= " LIMIT {$start},{$limit}";
  
    return $this->query($sqlquery, $args);
  }
  
  /**
   * Execute sql statement, only get one row record
   *
   * @param string $sql
   *  A string containing an SQL query.
   *
   * @param ...
   *
   * @return array
   *   One row data in from current query, or false if there are no more rows
   */
  public function get_one($sql) {
    return $this->query(func_get_args())->get_one();
  }
  
  /**
   * Get result data
   *
   * @param mixed(DbResult|string) $query
   *   The DbResult object that is being evaluated, or SQL string
   * @param int $row,
   *   The row number from the result that's being retrieved. Row numbers start at 0.
   * @param mixed[optional] $field,
   *   The name or offset of the field being retrieved.
   *   It can be the field's offset, the field's name, or the field's table dot field name (tablename.fieldname). If the column name has been aliased ('select foo as bar from...'), use the alias instead of the column name. If undefined, the first field is retrieved.
   * @return mixed
   *   The contents of one cell from a MySQL result set on success, or false on failure.
   */
  public function result($query = NULL, $row = 0, $field = 0) {
    if (is_string($query)) {
      $result = $this->query(func_get_args())->result();
    }
    else {
      $query = !isset($query) ? $this->_query : $query;
      $result= $query->result($row, $field);
    }
    return $result;
  }
  
  /**
   * insert data to a table
   *
   * @param string $tablename
   *  table name
   *
   * @param array $insertarr
   *  insert key-value array
   *
   * @param boolean $returnid
   *  whether return insert id
   *  
   * @param boolean $rawmode
   *  whether raw mode, when in raw mode, $tablename use original value, rather than with table prefix
   *
   * @return int
   *  insert id if set $returnid=1, else affected rows
   */
  public function insert($tablename, Array $insertarr, $returnid = TRUE, $rawmode = FALSE) {
    $server_mode  = self::WRITABLE; //Because of 'INSERT', so use self::WRITABLE
    $insertkeysql = $insertvaluesql = $comma = '';
    foreach ($insertarr as $insert_key => $insert_value) {
      $insertkeysql   .= $comma.'`'.$insert_key.'`';
      $insertvaluesql .= $comma.'\''.$this->escape_string($insert_value, $server_mode).'\'';
      $comma = ',';
    }
    if (!$rawmode) {
      $tablename = '`' . $this->tablePrefix . $tablename . '`';
    }
    $this->realtime_query = TRUE;  //make sure use writable mode
    $rs = $this->raw_query("INSERT INTO {$tablename} ({$insertkeysql}) VALUES ({$insertvaluesql})");
    $this->realtime_query = FALSE; //restore
    return $returnid ? $rs->insert_id() : $rs->affected_rows();
  }

  /**
   * update a table's data
   *
   * @param string $tablename
   *  table name
   *
   * @param array $setarr
   *  set sql key-value array
   *
   * @param array $wherearr
   *  where condition
   *  
   * @param boolean $rawmode
   *  whether raw mode, when in raw mode, $tablename use original value, rather than with table prefix
   *
   * @return int
   *  affected rows
   */
  public function update($tablename, Array $setarr, Array $wherearr = array(), $rawmode = FALSE) {
    $server_mode = self::WRITABLE; //Because of 'UPDATE', so use self::WRITABLE
    $setsql = $comma = '';
    foreach ($setarr as $set_key => $set_value) {
      $setsql .= $comma.'`'.$set_key.'`=\''.$this->escape_string($set_value, $server_mode).'\'';
      $comma = ',';
    }
    $where = $comma = '';
    if(empty($wherearr)) {
      $where = '0'; // force avoiding misoperation
    }
    elseif(is_array($wherearr)) {
      foreach ($wherearr as $key => $value) {
        $where .= $comma.'`'.$key.'`=\''.$this->escape_string($value, $server_mode).'\'';
        $comma  = ' AND ';
      }
    }
    else {
      $where = $wherearr; //unsafe
    }
    if (!$rawmode) {
      $tablename = '`' . $this->tablePrefix . $tablename . '`';
    }    
    $this->realtime_query = TRUE;  //make sure use writable mode
    $rs = $this->raw_query("UPDATE {$tablename} SET {$setsql} WHERE {$where}");
    $this->realtime_query = FALSE; //restore
    return $rs->affected_rows();
  }
  
  /**
   * update table record
   * 
   * @param string $tablename
   * @param array $wherearr
   * @return int
   *   affected rows
   */
  public function delete($tablename, Array $wherearr) {
    $server_mode = self::WRITABLE; //Because of 'DELETE', so use self::WRITABLE
    $where = '';
    if(empty($wherearr)) {
      $where = '0'; // force avoiding misoperation
    }
    elseif(is_array($wherearr)) {
      $comma = '';
      foreach ($wherearr as $key => $value) {
        $where .= $comma.'`'.$key.'`=\''.$this->escape_string($value, $server_mode).'\'';
        $comma  = ' AND ';
      }
    }
    else {
      $where = $wherearr; //unsafe
    }
    
    $tablename = $this->tablePrefix . $tablename;
    $this->realtime_query = TRUE;  //make sure use writable mode
    $rs = $this->raw_query("DELETE FROM `{$tablename}` WHERE {$where}");
    $this->realtime_query = FALSE; //restore 
    return $rs->affected_rows();
  }
  
  /**
   * insert data to a table
   *
   * @param string $tablename
   *  table name
   *
   * @param array $insertarr
   *  insert key-value array
   *
   * @param boolean $returnid
   *  whether return insert id
   *
   * @return
   *  insert id if set $returnid=1, else affected rows
   *  
   * @deprecated
   *  use $this->insert() method instead
   */
  public function insert_table($tablename, Array $insertarr, $returnid = TRUE) {
    return $this->insert($tablename, $insertarr, $returnid);
  }
  
  /**
   * update data to table
   *
   * @param string $tablename
   *  table name
   *
   * @param array $setarr
   *  set sql key-value array
   *
   * @param array $wherearr
   *  where condition
   *
   * @return int
   *  affected rows
   *  
   * @deprecated
   *  use $this->update() method instead
   */
  public function update_table($tablename, Array $setarr, Array $wherearr = array()) {
    return $this->update($tablename, $setarr, $wherearr);
  }
  
  /**
   * Append a database prefix to all tables in a query.
   *
   * Queries sent to Drupal should wrap all table names in curly brackets. This
   * function searches for this syntax and adds Drupal's table prefix to all
   * tables, allowing Drupal to coexist with other systems in the same database if
   * necessary.
   *
   * @param $sql string
   *   A string containing a partial or entire SQL query.
   * @param $db_prefix mixed(string|array)
   *   DB talbe prefix
   * @return
   *   The properly-prefixed string.
   */
  protected function append_prefix_tables($sql, $db_prefix = '') {
  
    if (is_array($db_prefix)) {
      if (array_key_exists('default', $db_prefix)) {
        $tmp = $db_prefix;
        unset($tmp['default']);
        foreach ($tmp as $key => $val) {
          $sql = strtr($sql, array('{'. $key .'}' => $val . $key));
        }
        return strtr($sql, array('{' => $db_prefix['default'], '}' => ''));
      }
      else {
        foreach ($db_prefix as $key => $val) {
          $sql = strtr($sql, array('{'. $key .'}' => $val . $key));
        }
        return strtr($sql, array('{' => '', '}' => ''));
      }
    }
    else {
      return strtr($sql, array('{' => $db_prefix, '}' => ''));
    }
    
  }
  
  /**
   * Helper function for query().
   * 
   * @param array $match
   * @param bool $init
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   */
  protected function query_callback($match, $init = FALSE, $server_mode = NULL) {
    static $args = NULL, $the_mode = NULL;
    if ($init) {
      $args = $match;
      $the_mode = $server_mode;
      return;
    }
  
    switch ($match[1]) {
      case '%d': // We must use type casting to int to convert FALSE/NULL/(TRUE?)
        return (int) array_shift($args); // We don't need escape_string as numbers are db-safe
      case '%s':
        return $this->escape_string(array_shift($args), $the_mode);
      case '%n':
        // Numeric values have arbitrary precision, so can't be treated as float.
        // is_numeric() allows hex values (0xFF), but they are not valid.
        $value = trim(array_shift($args));
        return is_numeric($value) && !preg_match('/x/i', $value) ? $value : '0';
      case '%%':
        return '%';
      case '%f':
        return (float) array_shift($args);
      case '%b': // binary data
        return $this->encode_blob(array_shift($args), $the_mode);
    }
  }
  
  /**
   * Get number of affected rows in previous MySQL operation
   *
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return int
   *   the number of affected rows on success, and -1 if the last query failed.
   */
  public function affected_rows($server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    return $this->_driverObj[$server_mode]->affected_rows();
  }
  
  /**
   * Get the ID generated in the last query
   *
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return int
   *   The ID generated for an AUTO_INCREMENT column by the previous query on success, 0 if the previous query does not generate an AUTO_INCREMENT value, or false if no MySQL connection was established.
   */
  public function insert_id($server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    return $this->_driverObj[$server_mode]->insert_id();
  }
  
  /**
   * Escapes special characters in a string for use in an SQL statement
   * 
   * @param string $text
   *   The string that is to be escaped.
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return string
   *  the escaped string, or false on error.
   */
  public function escape_string($text, $server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    $this->connect($server_mode);
    return $this->_driverObj[$server_mode]->escape_string($text);
  }
  
  /**
   * Escapes special characters in a string for use in an SQL statement
   *
   * @param string $data
   *   The string that is to be escaped.
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return string
   *  the escaped string, or false on error.
   */
  public function encode_blob($data, $server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    $this->connect($server_mode);
    return $this->_driverObj[$server_mode]->encode_blob($data);
  }
  
  /**
   * Get MySQL server info
   *
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return string
   *   the MySQL server version on success & return false for failure
   */
  public function version($server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    $this->connect($server_mode);
    return $this->_driverObj[$server_mode]->version();
  }
  
  /**
   * Close MySQL connection
   *
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return bool
   *   Returns true on success or false on failure.
   */
  public function close($server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    return $this->_driverObj[$server_mode]->close();
  }
  
  /**
   * Returns the text of the error message from previous MySQL operation
   * 
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return string
   *   the error text from the last MySQL function, or '' (empty string) if no error occurred.
   */
  public function error($server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    return $this->_driverObj[$server_mode]->error();
  }
  
  /**
   * Returns the error number from the lastest MySQL operation.
   * 
   * @param string $server_mode
   *   self::READONLY or self::WRITABLE or NULL(default)
   * @return int
   *   the error number from the last MySQL function, or 0 (zero) if no error occurred.
   */
  public function errno($server_mode = NULL) {
    $server_mode = $this->getServerMode($server_mode);
    return $this->_driverObj[$server_mode]->errno();
  }
  
  /**
   * magic method '__get'
   *
   * @param string $name
   */
  public function __get($name) {
    return array_key_exists($name, $this->_config) ? $this->_config[$name] : NULL;
  }
  
  /**
   * magic method '__set'
   *
   * @param string $name
   * @param string $value
   */
  public function __set($name, $value) {
    $this->_config[$name] = $value;
  }
  
	/**
	 * magic method '__isset'
	 * 
	 * @param string $name
	 */
	public function __isset($name) {
		return isset($this->_config[$name]);
	}
	
	/**
	 * magic method '__unset'
	 *
	 * @param string $name
	 */
	public function __unset($name) {
	  if (isset($this->_config[$name])) unset($this->_config[$name]);
	}
  
}

/**
 * Db Exception
 */
class DbException extends Exception {
  public function __construct($message = null, $code = null) {
    parent::__construct($message, $code);
  }
}

/*----- END FILE: class.DB.php -----*/