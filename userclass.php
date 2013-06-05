 <?php


/**
* To Manage Users
*
*
*/

class User extends DataBoundObject{

  protected $FirstName;
	protected $LastName;
	protected $Password;
	protected $Username;
	protected $EmailAddress;
	protected $DateLastLogin;
	protected $TimeLastLogin;
	protected $DateAccountCreated;
	protected $TimeAccountCreated;

	protected function DefineTableName(){
		return ("system_user");
	}

	protected function DefineRelationMap(){
		return (array(
			"id" => "ID",	
			"first_name" => "FirstName",	
			"last_name" => "LastName",	
			"md5_pw" => "Password",	
			"username" => "Username",	
			"email_address" => "EmailAddress",	
			"date_last_login" => "DateLastLogin",	
			"time_last_login" => "TimeLastLogin",	
			"date_account_created" => "DateAccountCreated",	
			"time_account_created" => "TimeAccountCreated",	
		));
	}
}
