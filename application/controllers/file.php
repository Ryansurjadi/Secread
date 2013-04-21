<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author Emeraldy
 */
class File extends CI_Controller {

    //private $path = '';
    private $total = 0;

    function add() {
        $this->load->library("form_validation");
        // $this->form_validation->set_rules("name", "Nama", "callback_filename_check");
        $this->form_validation->set_rules("name", "Nama", "required|trim");
        $this->form_validation->set_rules("content", "Konten", "trim");

        if ($this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key);
            }
            $sanitizedFileName = $this->security->sanitize_filename($data['name']);
            $path = $this->session->userdata('path');
            $fileList = get_filenames($path);
            $boolean = TRUE;
            foreach ($fileList as $string => $nama) {
                if (strcmp($sanitizedFileName, $nama) == 0) {
                    $boolean &= FALSE;
                }
            }
            if ($boolean) {
                $filePath = $path . $sanitizedFileName;
                //die($filePath);
                $fileContent = $data['content'];
                $is_success = write_file($filePath, $fileContent, 'w+');
            } else {
                $is_success = FALSE;
            }

            if ($is_success) {
                $this->session->set_flashdata("message", "File berhasil disimpan!");
            } else {
                $this->session->set_flashdata("message", " Maaf, file tidak berhasil disimpan");
            }
            redirect("file");
        } else {
            $this->load->view("file_form");
        }
    }

    function edit($name) {
        $this->load->library("form_validation");
        $this->form_validation->set_rules("name", "Nama", "required|trim");
        $this->form_validation->set_rules("content", "Konten", "trim");
        $path = $this->session->userdata('path');
        if ($this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key);
            }
            $decodedname = urldecode($name);
            $sanitizedFileName = $this->security->sanitize_filename($data['name']);
            
            if (strcmp($decodedname, $sanitizedFileName)==0) {
                $newPath = $path . DIRECTORY_SEPARATOR . $decodedname;
                $newContent = $data['content'];
                $is_success = write_file($newPath, $newContent, 'w+');
            } else {
                $fileList = get_filenames($path);
                $boolean = TRUE;
                foreach ($fileList as $string => $nama) {
                    if (strcmp($sanitizedFileName, $nama) == 0) {
                        $boolean &= FALSE;
                    }
                }
                if ($boolean) {
                    $oldPath = $path . DIRECTORY_SEPARATOR . $decodedname;
                    rename($oldPath, $sanitizedFileName);
                    $filePath = $path . DIRECTORY_SEPARATOR . $sanitizedFileName;
                    $fileContent = $data['content'];
                    $is_success = write_file($filePath, $fileContent, 'w+');
                } else {
                    $is_success = FALSE;
                }
            }

            if ($is_success) {
                $this->session->set_flashdata("message", "File berhasil diubah!");
            } else {
                $this->session->set_flashdata("message", "Maaf, file tidak berhasil diubah");
            }
            redirect("file");
        } else {
            $decodedname = urldecode($name);
            $decodedpath = $path . DIRECTORY_SEPARATOR . $decodedname;
            $fileList = get_filenames($path);
            $boolean = TRUE;
            foreach ($fileList as $string => $nama) {
                if (strcmp($decodedname, $nama) == 0) {
                    $boolean &= FALSE;
                }
            }
            if (!$boolean) {
                $data = array(
                    'name' => $decodedname,
                    'content' => read_file($decodedpath),
                );
                $this->load->view("file_form", $data);
            }else{
                show_404();
            }
        }
    }

    function delete($name) {
        $path = $this->session->userdata('path');
        $decodedname = urldecode($name);
        $decodedpath = $path . DIRECTORY_SEPARATOR . $decodedname;
        $is_success = unlink($decodedpath);

        if ($is_success) {
            $this->session->set_flashdata("message", "File berhasil dihapus!");
        } else {
            $this->session->set_flashdata("message", "Maaf, file gagal dihapus!");
        }

        redirect("file");
    }

    function index($offset = 0) {
        // VALID USER CREDENTIALS
        //$sss = read_file('../resource/accc.xml');
        $doc = new DOMDocument();
        $doc->load( './resource/accc.xml' );//xml file loading here

        $user_credentials = array();
        $accs = $doc->getElementsByTagName( "user_credentials" );
        foreach( $accs as $acc )
        {
            $uname = $acc->getElementsByTagName( "user_name" )->item(0)->nodeValue;
            $password = $acc->getElementsByTagName( "password" )->item(0)->nodeValue;
            if(strcmp($uname, $this->session->userdata('logged_user')) == 0){
                $user_credentials[$uname] = array(
                    'user_name' => $uname,
                    'password' => $password // password
                );
                break;
            }
        }
        
	if(strcmp($this->session->userdata('logged_user'), $user_credentials[$uname]['user_name']) == 0){
            $path = './resource/' . $user_credentials[$uname]['password'] . '/';
            
            $this->session->set_userdata('path', $path);
            //echo $path;
            $param['offset'] = $offset;
            $param["data"] = get_dir_file_info($path);

            foreach ($param['data'] as $file => $isi) {
                $this->total += 1;
            }
            $param['total'] = $this->total;
            $this->load->view("file_table", $param);
        
        }
        else{
            $this->session->set_flashdata('message', 'Session Invalid');
            //$this->session->sess_destroy();
            //$this->load->view('welcome_message');
            redirect('welcome/index');
        }
        
    }
    
    public function logout(){
        $this->session->unset_userdata('logged_user');
        $this->session->unset_userdata('path');
        $this->session->sess_destroy();
        redirect('welcome/index/');
    }

}

?>
