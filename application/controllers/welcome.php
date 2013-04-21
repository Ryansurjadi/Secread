<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
                // LOAD LIBRARIES
                $this->load->library(array('encrypt', 'form_validation', 'session'));

                // LOAD HELPERS
                $this->load->helper(array('form','url','file','string'));
                                
                // SET VALIDATION RULES
                $this->form_validation->set_rules('user_name', 'username', 'trim|required|xss_clean|min_length[4]');
                $this->form_validation->set_rules('user_pass', 'password', 'trim|required|xss_clean|min_length[8]|sha1');
                $this->form_validation->set_error_delimiters('<em>','</em>');

                // has the form been submitted and with valid form info (not empty values)
                if($this->input->post('login'))
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

                        if(array_key_exists($user_name, $user_credentials))
                        {
                            // continue processing form (validate password)
                            if(strcmp($user_pass, $user_credentials[$user_name]['user_pass']) == 0)
                            {
                                // user has been logged in
                                //die("USER LOGGED IN!");
                                $this->session->set_userdata('logged_user',$user_name);
                                //die($this->session->userdata('logged_user'));
                                redirect('file/index');
                            }
                            else
                            {
                                $this->session->set_flashdata('message', 'Wrong Password');
                                redirect('welcome/index/');
                            }
                        }
                        else
                        {
                            $this->session->set_flashdata('message', 'A user does not exist for the username specified.');
                            redirect('welcome/index/');
                        }
                    }
                }
                
                // LOAD VIEW PAGE
		$this->load->view('welcome_message');
                
                
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */