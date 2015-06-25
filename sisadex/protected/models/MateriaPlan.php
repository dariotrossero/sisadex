<?php
/**
 * This is the model class for table "Materia_has_Plan".
 *
 * The followings are the available columns in table 'Materia_has_Plan':
 * @property integer $Materia_id
 * @property integer $Plan_id
 * @property integer $anio
 * @property integer $cuatrimestre
 */
class MateriaPlan extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Materia_has_Plan';
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'Plan_id',
                'required'
                ),
            array(
                'Plan_id, anio, cuatrimestre',
                'numerical',
                'integerOnly' => true
                ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'Materia_id, Plan_id, anio, cuatrimestre',
                'safe',
                'on' => 'search'
                )
            );
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'Materia_id' => 'Materia',
            'Plan_id' => 'Plan',
            'anio' => 'Anio',
            'cuatrimestre' => 'Cuatrimestre'
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
        $criteria = new CDbCriteria;
        $criteria->compare('Materia_id', $this->Materia_id);
        $criteria->compare('Plan_id', $this->Plan_id);
        $criteria->compare('anio', $this->anio);
        $criteria->compare('cuatrimestre', $this->cuatrimestre);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
            ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MateriaPlan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    function refreshRecords($materia, $plan, $anio, $cuat)
    {
        /*
        Now to your question. I would create a function in the UsersRoles model named something like refreshRoles($user, $roles). Where $user is the user id you are selecting roles for, and $roles is an array of roles that should be set. The function would retrieve the list of Roles by id_users, and compare it to $roles, deleting roles that are missing from $roles, and adding roles that are missing from the UsersRoles table.
        
        
        */
        /*$userRoles = $this->findAllByAttribute(array('id_users' => $user));
        foreach ($userRoles as $userRole) {
        if (!in_array($userRole->id_roles, $roles)) {
        $userRole->delete();
        }
        else {
        $key = array_search($userRole->id_roles, $roles));
        unset($roles[$key]);
    }*/
    $model               = new MateriaPlan();
    $model->Materia_id   = $materia;
    $model->Plan_id      = $plan;
    $model->anio         = $anio;
    $model->cuatrimestre = $cuat;
    $model->save(false);
}
}
