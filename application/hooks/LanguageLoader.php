<?php
class LanguageLoader
{
    function initialize() {
        $ci =& get_instance();
        $ci->load->helper('language');
		$ci->load->model('Admin_model');
        //$ci->load->model('Common_model');
		$currentclass=$ci->router->fetch_class();
		$currentmethod=$ci->router->fetch_method();
		$isswitch=$ci->session->userdata('switch');
		if($currentclass=='user' && $currentmethod=='login' && empty($isswitch))
		{
			$ci->session->unset_userdata('site_lang');
		}	
        $site_lang = $ci->session->userdata('site_lang');//$ci->Common_model->get_selectedLanguage();//
		//get default language from site setting
		$data=$ci->Admin_model->get_default_language();
		$defaultlanguage=$data['status'];
		if(isset($site_lang)&&!empty($site_lang)){
			$defaultlanguage = $site_lang;
		}
		
		$defaultlanguage=$data['status'];
        //$site_lang = 'english';// comment this part
        //$defaultlanguage='english'; //comment this part
        $ci->session->set_userdata('site_lang',$defaultlanguage);
        if (!empty($defaultlanguage) ) {            
                if(!file_exists('./application/language/'.$site_lang.'/'.'all_lang.php')){
                    //$ci->session->set_userdata('site_lang',$defaultlanguage); uncomment it 
                    $ci->lang->load('all',$defaultlanguage);
                    $rtl=$ci->Common_model->get_selectedLanguage();
                    //$ci->session->set_userdata('last_query',$this->db->last_query());
                    $ci->session->set_userdata('rtl_lang',$rtl);
					$ci->config->set_item('language', $defaultlanguage);
					//$ci->lang->load('form_validation','english');
                }
                else{
                    $ci->lang->load('all',$site_lang);
				$ci->config->set_item('language', $site_lang);
				}
        } else {
			//$ci->session->set_userdata('site_lang',$defaultlanguage); uncomment it 
            $rtl=$ci->Common_model->get_selectedLanguage();
            $ci->session->set_userdata('last_query',$this->db->last_query());
            $ci->session->set_userdata('rtl_lang',$rtl);
            $ci->lang->load('all',$defaultlanguage);
				$ci->config->set_item('language',$defaultlanguage);
        }
    }
}
?>