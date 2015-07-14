<?php
require_once ("secure_area.php");
//require_once ("interfaces/idata_controller.php");
class Contactus extends Secure_area/* implements iData_controller*/
{
	function __construct()
	{
		parent::__construct('contactus');
	}

	function index()
	{
		$data['controller_name']=strtolower(get_class());
		$user_info = $this->Employee->get_logged_in_employee_info();
		$data['user_info'] = $user_info;
		$this->load->view('contactus/manage' , $data);
	}

	function send_message()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$phone_number = $this->input->post('phone_number');
		$msg = $this->input->post('msg');

		$addr_mail = $this->Order->from_addr_mail();

		$mail_subject = $this->lang->line('contactus_mail_subject');
		$message = "from : ".$user_info->email;
		$message .= "\r\nphone number : ".$phone_number;
		$message .= "\r\nusername : ".$user_info->username;
		$message .= "\r\n\r\n";
		$message .= $msg;

 		$this->email->from($addr_mail['seller_mail_addr'] , $addr_mail['company_name']);
		$this->email->to($addr_mail['seller_mail_addr']);
		$this->email->cc($user_info->email);
		$this->email->subject($mail_subject);
		$this->email->message($message);
		if(!$this->email->send())
			echo -1;
		else echo 1;

	}
}
?>