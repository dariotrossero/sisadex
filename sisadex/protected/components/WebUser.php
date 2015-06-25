<?php 
class WebUser extends CWebUser {
    // Store model to not repeat query.
    private $_model;
    // Return first name.
    // access it by Yii::app()->user->first_name
    
    
    function getRole(){
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->role;
    }

    function getUsername() {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user;
    }
    
   
    // This is a function that checks the field 'role'
    // in the User model to be equal to constant defined in our User class
    // that means it's admin
    // access it by Yii::app()->user->isAdmin()
    function isAdmin(){
        $user = $this->loadUser(Yii::app()->user->id);
        if ($user!==null)
            return intval($user->role) == Users::ROLE_ADMIN;
        else return false;
    }
    // Load user model.
    protected function loadUser($id=null) {
        if($this->_model===null)
        {
            if($id!==null)
            $this->_model=Users::model()->findByPk($id);
        }
        return $this->_model;
    }        
 
}