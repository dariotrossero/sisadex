<?php
/**
 * This is the model class for table "Materia".
 *
 * The followings are the available columns in table 'Materia':
 * @property integer $id
 * @property string $nombreMateria
 *
 * The followings are the available model relations:
 * @property Plan[] $plans
 * @property TipoExamen[] $tipoExamens
 * @property Examen[] $examens
 */
class Materia extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Materia';
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
                'numerical',
                'message' => '{attribute} debe ser un numero.',
                'min' => 1,
                'integerOnly' => true
                ),
            array(
                'id',
                'unique'
                ),
            array(
                'id',
                'required',
                'message' => '{attribute} no puede ser vacío.'
                ),
            array(
                'nombreMateria',
                'required',
                'message' => '{attribute} no puede ser vacío.'
                ),
            array(
                'nombreMateria',
                'length',
                'max' => 70
                ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, nombreMateria',
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
                self::MANY_MANY,
                'Plan',
                'Materia_has_Plan(Materia_codigoMateria, Plan_codigoPlan)'
                ),
            'tipoExamens' => array(
                self::HAS_MANY,
                'TipoExamen',
                'Materia_codigoMateria'
                ),
            'examens' => array(
                self::HAS_MANY,
                'Examen',
                'Materia_codigoMateria'
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
            'nombreMateria' => 'Materia'
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
        $criteria->compare('nombreMateria', $this->nombreMateria, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
            ));
    }
    public function searchSinDefault()
    {
        $criteria            = new CDbCriteria;
        $criteria->condition = 'id!=:arg1';
        $criteria->params    = array(
            ':arg1' => -1
            );
        $criteria->compare('id', $this->id, true);
        $criteria->compare('nombreMateria', $this->nombreMateria, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'nombreMateria ASC'
                ),
            'pagination' => array(
                'pageSize' => 15
                )
            ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Materia the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function getConcatened()
    {
        return $this->id . ' - ' . $this->nombreMateria;
    }
    public function getTodasLasMaterias($orderBy)
    {
        return $this->findAll(array(
            "condition" => "id !=-1",
            'order' => $orderBy
            ));
    }


    public function existsInDatabase($nombreMateria) {
            $criteria = new CDbCriteria();
            $criteria->select = 'nombreMateria';
            $criteria->condition = 'LOWER(nombreMateria)=:nombreMateria';
            $criteria->params = array(
            ':nombreMateria' => strtolower($nombreMateria),
             );
            $records = Materia::model()->find($criteria);
            return (count($records) > 0);
    }
}

