<?php

/**
 * This is the model class for table "Carrera".
 *
 * The followings are the available columns in table 'Carrera':
 * @property integer $id
 * @property string $nombreCarrera
 *
 * The followings are the available model relations:
 * @property Plan[] $plans
 */
class Carrera extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Carrera the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Carrera';
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
                'id',
                'required',
                'message' => '{attribute} no puede ser vacío.'
                ),
            array(
                'nombreCarrera',
                'required',
                'message' => '{attribute} no puede ser vacío.'
                ),
            array(
               'id, nombreCarrera',
                'unique'
                ),
            array(
                'id',
                'numerical',
                'integerOnly' => true,
                'message' => '{attribute} debe ser un numero.'
                ),
            array(
                'nombreCarrera',
                'length',
                'max' => 70
                ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, nombreCarrera',
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
        return array(
            'plans' => array(
                self::HAS_MANY,
                'Plan',
                'Carrera_codigoCarrera'
                )
            );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Código',
            'nombreCarrera' => 'Carrera'
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
        $criteria->compare('id', $this->id);
        $criteria->compare('nombreCarrera', $this->nombreCarrera, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id ASC'
                )
            ));
    }


    public function existsInDatabase($nombreCarrera) {
            $criteria = new CDbCriteria();
            $criteria->select = 'nombreCarrera';
            $criteria->condition = 'LOWER(nombreCarrera)=:nombreCarrera';
            $criteria->params = array(
            ':nombreCarrera' => strtolower($nombreCarrera),
             );
            $records = Carrera::model()->find($criteria);
            return (count($records) > 0);
    }
}
