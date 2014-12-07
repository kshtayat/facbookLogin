<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

	private $_id;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	
	public function authenticate()
	{
		$users=UserLogin::model()->find('user_email=:username and password=:password',array("username"=>$this->username,"password"=>$this->password));
		
		if(!isset($users->user_email))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
		{
			$this->errorCode=self::ERROR_NONE;
			$this->_id = $users->user_id;
		}
		return !$this->errorCode;
	}

	public function getId()
	{
		return $this->_id;
	}

}