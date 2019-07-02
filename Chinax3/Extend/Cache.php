<?php
namespace Extend;
class Cache{

    /**
     * 架构函数
     * @access public
     */
    public function __construct($options=array()) {
        if(!empty($options)) {
            $this->options =  $options;
        }
        $this->options['temp']      =   \Chinax3::$conf['cacheDir'];
        $this->options['prefix']    =  \Chinax3::$conf['prefix'];
        $this->options['expire']    =  \Chinax3::$conf['cacheTime'];
        $this->options['length']    =   0;
        if(substr($this->options['temp'], -1) != '/')    $this->options['temp'] .= '/';
        $this->init();
    }

    /**
     * 初始化检查
     * @access private
     * @return boolen
     */
    private function init() {
        $stat = stat($this->options['temp']);
        $dir_perms = $stat['mode'] & 0007777; // Get the permission bits.
        $file_perms = $dir_perms & 0000666; // Remove execute bits for files.

        // 创建项目缓存目录
        if (!is_dir($this->options['temp'])) {
            if (!  mkdir($this->options['temp']))
                return false;
             chmod($this->options['temp'], $dir_perms);
        }
    }

    /**
     * 取得变量的存储文件名
     * @access private
     * @param string $name 缓存变量名
     * @return string
     */
    private function filename($name) {
        $name	=	md5($name);
        $filename	=	$this->options['prefix'].$name.'.php';
        return $this->options['temp'].$filename;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        $filename   =   $this->filename($name);
        if (!is_file($filename)) {
           return false;
        }
        $content    =   file_get_contents($filename);
        if( false !== $content) {
            $expire  =  (int)substr($content,8, 12);
            if($expire != 0 && time() > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
                unlink($filename);
                return false;
            }         
            $content   =  substr($content,20, -3);           
            $content    =   unserialize($content);
            return $content;
        }
        else {
            return false;
        }
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param int $expire  有效时间 0为永久
     * @return boolen
     */
    public function set($name,$value,$expire=null) {
        if(is_null($expire)) {
            $expire =  $this->options['expire'];
        }
        $filename   =   $this->filename($name);
        $data   =   serialize($value);
        $data    = "<?php\n//".sprintf('%012d',$expire).$check.$data."\n?>";
        $result  =   file_put_contents($filename,$data);
        if($result) {
            if($this->options['length']>0) {
                // 记录缓存队列
                $this->queue($name);
            }
            clearstatcache();
            return true;
        }else {
            return false;
        }
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolen
     */
    public function rm($name) {
        return unlink($this->filename($name));
    }

    /**
     * 清除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolen
     */
    public function clear() {
        $path   =  $this->options['temp'];
        if ( $dir = opendir( $path ) ) {
            while ( $file = readdir( $dir ) ) {
                $check = is_dir( $file );
                if ( !$check )
                    unlink( $path . $file );
            }
            closedir( $dir );
            return true;
        }
    }
}