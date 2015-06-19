<?php

 /*
 * Default home controller for the website
 */
 
class Home extends Controller {

	public $data = array();
	
	function Home()
	{
		parent::Controller();	
	}
	
	function Index()
	{
		$this->data['page_css'] = '/theme/css/home.css';
		$this->data['person'] = 'World!';
		
		switch(rand(0, 5))
		{
			case 0:
				$this->data['easyCounter'] = ['A', 'B', 'C']; 
				break;
			case 1:
				$this->data['easyCounter'] = ['پ', 'ب', 'ا']; 
				break;
			case 2:
				$this->data['easyCounter'] = ['I', 'II', 'III']; 
				break;
			default:
				$this->data['easyCounter'] = [1, 2, 3]; 
		}
		
		$this->load->view('home');
	}
}

?>