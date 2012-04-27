<?php

/*
 * @todo: final file css estructure variable, like $str = "s-p" or "ps" or "p_s"
 * @todo: folder to less compilated that can be ignored by git and returns that by getCssName()
 * @todo: test if one style.less that includes more less run ok with this class
 */

require_once "lessphp/lessc.inc.php";
if (!defined('__DIR__')) define ("__DIR__",dirname(__FILE__));

/**
 * Description of LessLoader
 * a loader and mixer from less palette file independent of master style file
 * see sample.php for demonstration of use
 * the idea is to have one or more less files that specifying colors and other attributes palette dependants
 * and one style less file that use this attributes, in the fly LessLoader checks if there are
 * any change in the less files, join their, and then compile final less with lessphp compiler,
 * finally we can use getCssName method to have direct access to the generated file
 * 
 * @author Joseluis Laso
 */
class LessLoader {
    
    // folder where are files (less and css, and her caches)
    private $dir;
    // name of less file palette
    private $palette;
    // name of less file styles
    private $styles;
    
    /**
     * construct of the class
     * @param string $dir
     * @param string $palette
     * @param array $legalPalettes array of palette string name what are valid 
     * @param string $styles 
     */
    public function __construct($dir,$palette,array $legalPalettes, $styles) {
        $this->dir     = $dir.'/';
        $this->palette = $palette;
        $this->styles  = $styles;
        
        if(!in_array($this->palette, $legalPalettes)) $this->palette=$legalPalettes[0];
        $this->auto_compile_less($this->dir, $this->palette, $this->styles);
    }
    
    /**
     * this compiles the less file prefixing them with definitions of palette passed
     * @param string $dir
     * @param string $paleta
     * @param string $styles 
     */
    function auto_compile_less($dir,$paleta,$styles) {
          $mix_less = $dir.$paleta.$styles.".less";
          $paleta_fname = $dir.'palettes/'.$paleta.".less";
          $less_fname = $dir.$styles.".less";
          $f1 = file_exists($mix_less)?filemtime($mix_less):0;
          $f2 = filemtime($paleta_fname);
          $f3 = filemtime($less_fname);
          if($f1<$f2 || $f1<$f3){
              @unlink($mix_less);
              copy($paleta_fname, $mix_less);
              file_put_contents($mix_less, "\r\n".file_get_contents($less_fname),FILE_APPEND);
          }
          // load the cache
          $cache_fname = $dir.$paleta.$styles.".cache";
          $css_fname = $dir.$paleta.$styles.".css";
          if (file_exists($cache_fname)) {
            $cache = unserialize(file_get_contents($cache_fname));
          } else {
            $cache = $mix_less;
          }
          // y ahora a ver si hay que recompilar?
          $new_cache = lessc::cexecute($cache);
          if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
            file_put_contents($cache_fname, serialize($new_cache));
            file_put_contents($css_fname, $new_cache['compiled']);
          }
    }

    /**
     * helper that returns the name of the compiled css having present palette passed
     * @return string 
     */
    public function getCssName(){
        return $this->dir.$this->palette.$this->styles.'.css';
    }
}

?>
