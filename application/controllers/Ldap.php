<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ldap extends CI_Controller {


  public function index()
  {
    $this->load->view('login');
  }

  public function email()
  {
    $email = 'muhammad.julianto@mahadasha.co.id';

    $email_baru = explode("@", $email);

    echo $email_baru[0]; echo "<br>";
    echo '@'.$email_baru[1];
  }

	public function check_ldap()
	{
  	// $ldap_dn = "CN=".$this->input->post('username').",DC=MAHADASHA,DC=TMT,DC=CO,DC=ID";
    // $ldap_password = $this->input->post('password');

    // $ldap_dn = "DC=MAHADASHA,DC=TMT,DC=CO,DC=ID";

    $email = $this->input->post('email');
    $password = $this->input->post('password');

    //explode string untuk mendapatkan username
    $email_explode = explode("@", $email);
    $username = $email_explode[0];

    $ldap_tree = "DC=MAHADASHA,DC=TMT,DC=CO,DC=ID"; //bisa langsung ditambah OU=CJ

  	$ldap_con = ldap_connect('LDAP://MAHADASHA.tmt.co.id');
    ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
  	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
    //ldap_set_option($ldapConnect, LDAP_OPT_REFERRALS, 0);

  	if(@ldap_bind($ldap_con,$username.'@MAHADASHA.tmt.co.id',$password))
  		{
        echo "LDAP Bind successfull <br><br>";

        // $ADFilter = "mailNickname=".$username;
        $ADFilter = "samaccountname=".$username; //difilter menggunakan email

        $ADAttributes = array(
                        "cn",
                        "givenname",
                        "sn",
                        "mail",
                        "name",
                        "mailnickname",
                        "employeeid"
                );


        $result = ldap_search($ldap_con, $ldap_tree, $ADFilter, $ADAttributes);
        $data = ldap_get_entries($ldap_con, $result);

        // print_r($data);

        for ($i=0; $i<$data["count"]; $i++)
        {
          //mendapatkan value user dari LDAP
          echo "User: ". $data[$i]["cn"][0] ."<br />";
          echo "Givenname: ". $data[$i]["givenname"][0] ."<br />";
          echo "SN: ". $data[$i]["employeeid"][0] ."<br />";
          echo "Email: ". $data[$i]["mail"][0] ."<br />";
          echo "Name: ". $data[$i]["name"][0] ."<br />";
          echo "Username: ". $data[$i]["mailnickname"][0] ."<br />";
        }

  	  } else {
  		  echo "LDAP Bind Failed";
      }
  }
}
