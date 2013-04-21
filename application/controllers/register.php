<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {
    public function index(){
        $this->load->view('registration');
        
        // LOAD LIBRARIES
        $this->load->library(array('encrypt', 'form_validation', 'session'));

        // LOAD HELPERS
        $this->load->helper(array('form','url','file','string','xml'));

        // SET VALIDATION RULES
        $this->form_validation->set_rules('user_name', 'Username', 'trim|required|min_length[4]|xss_clean');
        $this->form_validation->set_rules('user_pass', 'Password', 'trim|required|min_length[8]|xss_clean|matches[confirm_pass]|sha1');
        $this->form_validation->set_rules('confirm_pass', 'Password Confirmation', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<em>','</em>');
        
        
        if($this->input->post('register'))
        {
            if($this->form_validation->run())
            {   
                // VALID USER CREDENTIALS
                //$sss = read_file('../resource/accc.xml');
                $doc = new DOMDocument();
                $doc->load( './resource/accc.xml' );//xml file loading here

                $user_credentials = array();
                $accs = $doc->getElementsByTagName( "user_credentials" );
                foreach( $accs as $acc )
                {
                    $user_name = $acc->getElementsByTagName( "user_name" )->item(0)->nodeValue;
                    $password = $acc->getElementsByTagName( "password" )->item(0)->nodeValue;
                    $user_credentials[$user_name] = array(
                    'user_name' => $user_name,
                    'user_pass' => $password // password
                    );
                }
                
                $user_name = $this->security->xss_clean($this->input->post('user_name'));
                $user_pass = $this->security->xss_clean($this->input->post('user_pass'));
                $session_id = $this->session->userdata('session_id');

                if(array_key_exists($user_name, $user_credentials))
                {
                    $this->session->set_flashdata('message', 'User already existed.');
                    redirect('register/index/');
                    
//                    // continue processing form (validate password)
//                    if($user_pass == $this->encrypt->decode($user_credentials[$user_name]['user_pass']))
//                    {
//                        // user has been logged in
//                        die("USER LOGGED IN!");
//                        //$this->load->controller('welcome/index');
//                    }
//                    else
//                    {
//                        $this->session->set_flashdata('message', 'Wrong Password');
//                        redirect('welcome/index/');
//                    }
                }
                else
                {
//                    $this->session->set_flashdata('message', 'A user does not exist for the username specified.');
//                    redirect('welcome/index/');
                    
                    $secread = $doc->getElementsByTagName('secread')->item(0);
                    
                    $uscre = xml_add_child($secread, 'user_credentials');
 
                    xml_add_child($uscre, 'user_name', $user_name);
                    xml_add_child($uscre, 'password', $user_pass);
                    xml_add_child($uscre, 'sessionID', $session_id);

                    //xml_print($doc);
                    
                    if ( !write_file('./resource/accc.xml', $doc->saveXML()))
                    {
                         echo 'Unable to write the file';
                    }
                    else
                    {
                         //echo 'File written!';
                         mkdir('./resource/' . $user_pass);
                        // LOAD VIEW PAGE
                        redirect('welcome/index');
                    }
                    
                    
                }
            }
        }
        
        // LOAD VIEW PAGE
        //$this->load->view('registration');
    }
    
    //public function salting(){
    //    $range_start = 48;
    //    $range_end   = 122;
    //    $random_string = "";
    //    $random_string_length = 10;

    //    for ($i = 0; $i < $random_string_length; $i++) {
    //      $ascii_no = round( mt_rand( $range_start , $range_end ) ); // generates a number within the range
          // finds the character represented by $ascii_no and adds it to the random string
          // study **chr** function for a better understanding
    //      $random_string .= chr( $ascii_no );
     //   }

     //   echo $random_string;
    //}
}
