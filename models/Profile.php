<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\models;

use Da\User\Model\Profile as BaseProfile;


/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string  $name
 * @property string  $public_email
 * @property string  $gravatar_email
 * @property string  $gravatar_id
 * @property string  $location
 * @property string  $website
 * @property string  $bio
 * @property string  $timezone
 * @property User    $user
 * @property integer $managerId
 * @property integer $departmentId
 * 
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class Profile extends BaseProfile
{
   

    /**
     * Returns avatar url or null if avatar is not set.
     * @param  int $size
     * @return string|null
     */
   

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = Parent::rules();
        $rules['manager'] = ['managerId','integer'];
        $rules['department'] =['departmentId','integer'];
        $rules['officeNoLength'] =['officeNo','string','max'=>20];
        $rules['mobileNoLength'] =['mobileNo','string','max'=>20];
        
        return $rules;
        return [
            'bioString'            => ['bio', 'string'],
            'timeZoneValidation'   => ['timezone', 'validateTimeZone'],
            'publicEmailPattern'   => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl'           => ['website', 'url'],
            'nameLength'           => ['name', 'string', 'max' => 255],
            'publicEmailLength'    => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength'  => ['gravatar_email', 'string', 'max' => 255],
            'locationLength'       => ['location', 'string', 'max' => 255],
            'websiteLength'        => ['website', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = Parent::attributeLabels();
        $labels['managerId'] = \Yii::t('usuario','Manager');
		$labels['departmentId'] = \Yii::t('usuario','Department');		
        $labels['officeNo'] = \Yii::t('usuario','Office Phone');	
        $labels['mobileNo'] = \Yii::t('usuario','Mobile Phone');	
        return $labels;
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::className(),['id' =>'departmentId']);

    }

    public function getManagerProfile()
    {
        return Profile::findOne($this->managerId);
        //return $this->hasOne(self::className(), ['user_id' => 'managerId'])->from(['man' => 'profile']);
      
    }
    
    

}
