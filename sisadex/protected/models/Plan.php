<?php
/**
 * This is the model class for table "Plan".
 *
 * The followings are the available columns in table 'Plan':
 * @property string $anioPlan
 * @property integer $Carrera_id
 * @property integer $id
 */
class Plan extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Plan';
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
                'anioPlan, Carrera_id',
                'required'
                ),
            array(
                'Carrera_id',
                'numerical',
                'message' => 'Seleccione una carrera.',
                'integerOnly' => true
                ),
            array(
                'anioPlan',
                'numerical',
                'message' => 'Seleccione una fecha.',
                'integerOnly' => true
                ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'anioPlan, Carrera_id, id',
                'safe',
                'on' => 'search'
                ),
            array(
                'anioPlan',
                'ext.UniqueAttributesValidator',
                'with' => 'Carrera_id',
                'message' => 'Ya existe un plan de la carrera en el año ingresado.'
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
        return array(
            'materias' => array(
                self::MANY_MANY,
                'Materia',
                'Materia_has_Plan(Plan_id, Materia_id)'
                ),
            'carrera' => array(
                self::BELONGS_TO,
                'Carrera',
                'Carrera_id'
                )
            );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'anioPlan' => 'Año',
            'Carrera_id' => 'Carrera',
            'id' => 'ID'
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
        $criteria->compare('anioPlan', $this->anioPlan, true);
        $criteria->with[] = 'carrera';
        $criteria->addSearchCondition("carrera.nombreCarrera", $this->Carrera_id);
        $criteria->compare('id', $this->id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'anioPlan ASC'
                )
            ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Plan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
