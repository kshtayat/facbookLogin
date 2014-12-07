<?php

/**
 * This is the model class for table "fbusers".
 *
 * The followings are the available columns in table 'fbusers':
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $user_hometown
 * @property string $user_email
 * @property integer $fb_user_id
 * @property string $login_time
 * @property string $password
 * @property string $registration_time
 */
class UserLogin extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'fbusers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
			array('first_name, user_email, fb_user_id', 'required'),
			array('user_email', 'email'),
			array('first_name, last_name, user_hometown,password,fb_user_id', 'length', 'max'=>300),
			array('user_email', 'length', 'max'=>320),
			array('login_time,registration_time', 'default','value'=> new CDbExpression('NOW()'), 'on'=>'insert'),
			// The following rule is used by search().
			array('user_id,fb_user_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id' => 'User',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'user_hometown' => 'User Hometown',
            'user_email' => 'User Email',
            'fb_user_id' => 'Fb User',
            'login_time' => 'Login Time',
        	'password' => 'Password',
            'registration_time' => 'Registration Time',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('fb_user_id',$this->fb_user_id);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Fbusers the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	public function beforeSave()
	{
		if(empty($this->password ))
			$this->password = $this->randomPassword();
		return parent::beforeSave();
	}
	private function randomPassword() {
		$str = "";
		$length = 5;
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str.$this->fb_user_id;
	}
}