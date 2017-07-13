<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    /**
	 * Finder Loader
	 *
	 * Loads and instantiates finders.
	 *
	 * @param	string	$model		Model name
	 * @param	string	$name		An optional object name to assign to
	 * @param	bool	$db_conn	An optional database connection configuration to initialize
	 * @return	object
	 */
	public function finder( $finder, $name = '', $db_conn = FALSE ) {

        // check if there is a finder
		if ( empty( $finder ) ) {
			return $this;
		}
		elseif (is_array( $finder ) ) {
			foreach ( $finder as $key => $value ) {
				is_int( $key ) ? $this->finder( $value, '', $db_conn ) : $this->finder( $key, $value, $db_conn );
			}
			return $this;
		}

        // the path
		$path = '';

        // set the name
        $name = empty( $name ) ? $finder : $name;

        // check if the finder is already loaded
		if ( in_array( $name, $this->_ci_models, TRUE ) ) return $this;
        

        // gets CI instance
		$CI =& get_instance();

        // if the class has a property with the same name of the finder
		if ( isset( $CI->$name ) ) {
			throw new RuntimeException('The model name you are loading is the name of a resource that is already being used: '.$name);
		}

        // check the connection
		if ($db_conn !== FALSE && ! class_exists('CI_DB', FALSE)) {
			if ($db_conn === TRUE) {
				$db_conn = '';
			}
			$this->database($db_conn, FALSE, TRUE);
		}

		// Note: All of the code under this condition used to be just:
		//
		//       load_class('Model', 'core');
		//
		//       However, load_class() instantiates classes
		//       to cache them for later use and that prevents
		//       MY_Model from being an abstract class and is
		//       sub-optimal otherwise anyway.
		if ( !class_exists( 'CI_Model', FALSE ) ) {
			
            // app path
            $app_path = APPPATH.'core'.DIRECTORY_SEPARATOR;
			if ( file_exists( $app_path.'Model.php' ) ) {
				require_once($app_path.'Model.php');
				if ( !class_exists( 'CI_Model', FALSE ) ) {
					throw new RuntimeException($app_path."Model.php exists, but doesn't declare class CI_Model");
				}
			} elseif ( ! class_exists('CI_Model', FALSE ) ) {
				require_once(BASEPATH.'core'.DIRECTORY_SEPARATOR.'Model.php');
			}

			$class = config_item('subclass_prefix').'Model';
			if ( file_exists($app_path.$class.'.php' ) ) {
				require_once($app_path.$class.'.php');
				if ( !class_exists( $class, FALSE ) ) {
					throw new RuntimeException($app_path.$class.".php exists, but doesn't declare class ".$class);
				}
			}
		}

		$finder = ucfirst( $finder );
		if ( !class_exists( $finder, FALSE ) ) {
			foreach ( $this->_ci_model_paths as $mod_path ) {
				if ( !file_exists( $mod_path.'finders/'.$path.$finder.'.php' ) ) {
					continue;
				}

				require_once($mod_path.'finders/'.$path.$finder.'.php');
				if ( ! class_exists( $finder, FALSE ) ) {
					throw new RuntimeException($mod_path."finders/".$path.$finder.".php exists, but doesn't declare class ".$finder);
				}
				break;
			}

			if ( !class_exists( $finder, FALSE ) ){
				throw new RuntimeException('Unable to locate the finder you have specified: '.$finder);
			}
		} elseif ( !is_subclass_of( $finder, 'CI_Model' ) ) {
			throw new RuntimeException("Class ".$finder." already exists and doesn't extend CI_Model");
		}

		$this->_ci_models[] = $name;
		$CI->$name = new $finder();
		return $this;
	}
}

/* end of file */
