<?php
/**
 * cron 脚步执行入口
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
//~ require init.php
require (__DIR__.'/core/init.php');

SimPHP::I()->boot();

class JobManager {

  private function jobs() {
    $dir  = SIMPHP_ROOT . '/cron';
    $jobs = [];
    $_handler = opendir($dir);
    while ( false !== ($filename = readdir($_handler)) ) {
      if (preg_match("/^(.+Job)\.php$/", $filename, $matches) && is_file("{$dir}/$filename")) {
        $jobs[$matches[1]] = "{$dir}/{$filename}";
      }
    }
    return $jobs;
  }

  public function exec() {
    foreach($this->jobs() as $name => $file) {
      $pid = shell_exec("php {$file} >> " . LOG_DIR . "/job_exec.log 2>&1 &");
      SystemLog::local_log('job_manager', "{$name} started, PID:" . str_replace('[1]', '', $pid));
    }
  }
}

(new JobManager)->exec();
 
/*----- END FILE: cron.php -----*/