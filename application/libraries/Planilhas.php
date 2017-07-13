<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Planilhas
*
* classe de manipulacao de planilhas
*
*/
class Planilhas {

    // instancia do ci
    public $ci;

    // erros
    public $errors;

    // ultimo arquivo carregado
    public $filename;

    // pega o header
    public $header;

    // linhas do arquivo
    public $linhas;

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {

        // pega a instancia do codeigniter
        $this->ci =& get_instance();
    }

    public function apply( $callback ) {

        // path url
        $path = './uploads/'.$this->filename;

        // pega o header do arquivo
        $header = [];

        // inicializa a linha
        $row = 1;
        if ( ( $handle = fopen( $path, "r" ) ) !== FALSE ) {
            while ( ( $data = fgetcsv($handle, 1000, "," ) ) !== FALSE ) {

                // verifica se é a primeira linha
                if ( $row == 1 ) {

                    // pega o cabecalho
                    $header = $data;
                } else {

                    // pega a quatidade de itens na linha
                    $num = count($data);

                    // array de resposta
                    $res = [];

                    // percorre cada um deles
                    for ( $c=0; $c < $num; $c++ ) {
                        $key = str_replace( ' ', '', $header[$c] );
                        $res[$key] = $data[$c];
                    }

                    // aplica o callback
                    $callback( $res, $row );
                }

                // soma a linha
                $row++;                
            }
            fclose($handle);
        }
    }

   /**
    * upload
    *
    * faz o upload da planilha
    *
    */
    public function upload() {

        // seta as configuracoes
        $config['file_name']     = md5( uniqid( rand() * time() ) );
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'csv';

        // carrega a lib de upload
        $this->ci->load->library( 'upload', $config );

        // tenta fazer o upload
        if ( !$this->ci->upload->do_upload( 'planilha' ) ) {

            // seta os erros
            $this->errors = [ 'error' => $this->ci->upload->display_errors() ];
            return false;
        } else {
            $data = $this->ci->upload->data();
            $this->filename = $data['file_name'];
            return $this;
        }
    }

   /**
    * excluir
    *
    * exclui a planilha
    *
    */
    public function excluir() {

        // path
        $path = './uploads/'.$this->filename;

        // verifica se o arquivo existe
        if ( file_exists( $path ) ) {
            return unlink( $path );
        } return false;
    }
}

/* end of file */
