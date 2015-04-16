<?php
/**
 * Crontab job abstract class
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
defined('IN_SIMPHP') or die('Access Denied');

abstract class CronJob {
  
  /**
   * whether enabled
   * @var bool
   */
  private $_enabled = true;
  
  /**
   * shutdown functions
   * @var array
   */
  private $_shutdown_functions = array();
  
  /**
   * abstract method, job logic
   */
  abstract protected function job();
  
  public function onProduction() {
    return $this->disable();
  }
  
  protected function __construct() {
    
  }
  
  /**
   * 
   * @param integer $start_minute
   * @param integer $repeat_period
   * @throws Exception
   * @return CronJob
   */
  public function onMinute($start_minute, $repeat_period = 60) {
    if($repeat_period > 60 || $repeat_period < 0) throw new Exception("repeat_period should below 60, use onHour() instead !");
    return $this->disable( (date('i') - $start_minute) % $repeat_period != 0 );
  }
  
  public function onHour($start_hour, $repeat_period = 24) {
    if($repeat_period > 24) throw new Exception("repeat_period should below 24, you may need a new function!");
    return $this->disable( (date('H') - $start_hour) % $repeat_period != 0 );
  }
  
  public function isUnique($key = ''){
    $key = get_class($this) . $key;
    if(Locker::lock($key)) {
      $this->shutdown_functions[] = function() use($key) { Locker::unlock($key); };
    } else {
      $this->disable(true);
    }
    return $this;
  }
  
  public function doJob($name) {
    if($this->_enabled) {
      $this->log('Start to run...');
      $this->$name();
      foreach($this->shutdown_functions as $func) {
        $func();
      }
      $this->log('Finished !');
    }
  }
  
  public function log($log_string){
    SystemLog::local_log('job_' . get_class($this), $log_string);
  }
  
  private function disable($condition = false) {
    if ($condition) $this->_enabled = false;
    return $this;
  }
  
} 
 
/*----- END FILE: class.CronJob.php -----*/