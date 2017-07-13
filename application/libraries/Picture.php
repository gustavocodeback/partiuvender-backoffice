<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Classe para a manipulação de templates
class Picture {

    // instancia do codeigniter
    public $ci;

    // guarda os errors do ultimo upload
    public $errors;

    // guarda os dados do ultimo upload
    public $data;

    // guarda o caminho padrao do upload
    public $path = './uploads/';

    // método construtor
    public function __construct() {

        // pega a instancia do ci
        $this->ci =& get_instance();
    }

    //  faz o resize da imagem
    public function square( $file_name, $size ) {

        // verifica qual a referencia a ser usada
        $reference = min( [ $this->data['image_width'], $this->data['image_height'] ] );
        $ratio     = $reference / $size;

        // seta as configuracoes do resize
        $config_resize['image_library']  = 'gd2';
        $config_resize['source_image']   = $this->path.$file_name;
        $config_resize['create_thumb']   = FALSE;
        $config_resize['maintain_ratio'] = TRUE;
        $config_resize['width']          = $this->data['image_width']  / $ratio;
        $config_resize['height']         = $this->data['image_height'] / $ratio;

        // faz o resize
        $this->ci->load->library( 'image_lib', $config_resize );
        $this->ci->image_lib->resize();
        $errors = $this->ci->image_lib->display_errors();

        // configurações do crop
        $config['image_library']  = 'gd2';
        $config['source_image']   = $this->path.$file_name;
        $config['create_thumb']   = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['x_axis']         = 0;
        $config['y_axis']         = 0;
        $config['width']          = $size;
        $config['height']         = $size;

        //carrega a imagem
        $this->ci->image_lib->initialize( $config ); 
        if ( !$this->ci->image_lib->crop() ) {
            $this->errors = $this->ci->image_lib->display_errors();
            return false;
        }
        
        // volta true como padrao
        return true;
    }

    // deleta uma imagem
    public function delete( $name ) {

        // verifica se o arquivo existe
        if ( $name && file_exists( $this->path.$name ) ) {
            unlink( $this->path.$name );
        }
    }

    // faz o upload
    public function upload( $field, $params = array() ) {

        // configuração do upload
        $config['upload_path']   = $this->path;
        $config['allowed_types'] = 'jpg|png';
        $config['file_name']     = md5( uniqid( rand() * time() ) );

        // carrega a librarie de upload
        $this->ci->load->library('upload', $config);

        // se fizer o upload
        if ( !$this->ci->upload->do_upload( $field ) ) {
            $this->errors = $this->ci->upload->display_errors();
            return false;
        } else {
            $this->data = $this->ci->upload->data();
            
            // verifica se deve transformar a imagem em quadrado
            if ( isset( $params['square'] ) ) {
                if ( $this->square( $this->data['file_name'], $params['square'] ) ) {
                    return $this->data['file_name'];
                } else {
                    $this->errors = '<p>Não foi possivel fazer o upload dessa foto</p>';
                    $this->delete( $this->data['file_name'] );
                    return false;
                }
            }
            return $this->data['file_name'];
        }
    }
}
