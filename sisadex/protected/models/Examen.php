<?php
/**
 * This is the model class for table "Examen".
 *
 * The followings are the available columns in table 'Examen':
 * @property integer $codigoExamen
 * @property string $fechaExamen
 * @property integer $TipoExamen_idTipoExamen
 * @property integer $Materia_idMateria
 * @property string $descripcionExamen
 *
 * The followings are the available model relations:
 * @property TipoExamen $tipoExamenidTipoExamen
 * @property Materia $materiaidMateria
 */
class Examen extends CActiveRecord
{
    public $TipoExamenPersonalizado = "";
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Examen';
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
                'TipoExamenPersonalizado',
                'checkEndDate'
                ),
            array(
                'fechaExamen',
                'required',
                'message' => 'Seleccione una fecha'
                ),
            array(
                'materia_id',
                'required',
                'message' => 'Seleccione una materia'
                ),
            array(
                'diasPreparacion',
                'required'
                ),
            array(
                'tipoexamen_id',
                'required',
                'message' => 'Seleccione un tipo de examen'
                ),
            array(
                'materia_id',
                'numerical',
                'integerOnly' => true,
                'min' => 0,
                'message' => 'Seleccione una materia'
                ),
            array(
                'tipoexamen_id',
                'numerical'
                ),
            array(
                'diasPreparacion',
                'numerical',
                'integerOnly' => true,
                'min' => 1,
                'message' => '{attribute} debe ser un número',
                'tooSmall' => '{attribute} debe ser un número mayor que 1'
                ),
            array(
                'descripcionExamen',
                'length',
                'max' => 160
                ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, fechaExamen, tipoexamen_id, materia_id, descripcionExamen',
                'safe',
                'on' => 'search'
                ),
            array(
                'fechaExamen',
                'type',
                'type' => 'date',
                'message' => '{attribute}: no es una fecha valida!',
                'dateFormat' => 'dd-mm-yyyy'
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
            'tipoexamen' => array(
                self::BELONGS_TO,
                'Tipoexamen',
                'tipoexamen_id'
                ),
            'materia' => array(
                self::BELONGS_TO,
                'Materia',
                'materia_id'
                )
            );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Código de Examen',
            'fechaExamen' => 'Fecha de Examen',
            'tipoexamen_id' => 'Tipo de Examen',
            'materia_id' => 'Materia',
            'descripcionExamen' => 'Descripción',
            'TipoExamenPersonalizado' => 'Tipo de examen personalizado',
            'diasPreparacion' => 'Días de preparación estimados'
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
        $criteria->compare('fechaExamen', $this->fechaExamen, true);
        $criteria->compare('tipoexamen_id', $this->tipoexamen_id);
        $criteria->compare('materia_id', $this->materia_id);
        $criteria->compare('descripcionExamen', $this->descripcionExamen, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'fechaExamen ASC'
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
     * @return Examen the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    protected function beforeSave()
    {
        // convert to storage format
        $this->fechaExamen = date('Y-m-d', strtotime($this->fechaExamen));
        return parent::beforeSave();
    }
    public function getFormattedDate()
    {
        return date('d-M-Y', strtotime($this->fechaExamen));
    }
    public function getCountExamsByPlan()
    {
        $sql = 'SELECT ex.id,nombreCarrera,anioPlan, anioPlan || " - " || nombreCarrera as plan   ,count(ex.id) as cant  FROM carrera c inner join examen ex inner join Materia_has_plan mp inner join Plan p 
        on ex.materia_id=mp.Materia_id and p.id = mp.Plan_id  and p.carrera_id = c.id
        group by plan_id order by c.nombreCarrera
        ';
        return new CSqlDataProvider($sql);
    }

    /**
     * Obtiene todos los proximos 15 examenes
     * @return CActiveDataProvider
     */
    public function getAllNextExams()
    {
        $currentYear     = date("Y");
        $criteria        = new CDbCriteria;
        $criteria->limit = 15;
        $criteria->addBetweenCondition('fechaExamen', date("Y-m-d", strtotime("+1 day", strtotime($this->fechaExamen))), $currentYear . '-12-31');
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'fechaExamen ASC'
                ),
            'pagination' => false
            ));
    }

    /**
     * Obtiene los ultimos 5 pròximos examenes de una materia
     * @return CActiveDataProvider
     */
    public function getNextExams()
    {
        $currentYear         = date("Y");
        $criteria            = new CDbCriteria;
        $criteria->condition = 'materia_id=:materia_id';
        $criteria->params    = array(
            ':materia_id' => $this->materia_id
            );
        $criteria->limit     = 5;
        $criteria->addBetweenCondition('fechaExamen', date("Y-m-d", strtotime("+1 day", strtotime($this->fechaExamen))), $currentYear . '-12-31');
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'fechaExamen ASC'
                ),
            'pagination' => false
            ));
    }
    public function checkEndDate($attributes, $params)
    {
        if ($this->tipoexamen_id == -1)
            if ($this->TipoExamenPersonalizado = "")
                $this->addError('TipoExamenPersonalizado', 'Error Message');
        }
        public function getExamsFromAndTo($fromDate, $untilDate)
        {
            $criteria = new CDbCriteria;
            $criteria->addBetweenCondition('fechaExamen', $fromDate, $untilDate);
            $count = Examen::model()->count($criteria);
            return $count;
        }
    //Future use???
        public function getExamsFirstCuat()
        {
            $currentYear = date("Y");
            return $this->getExamsFromAndTo($currentYear . '-03-01', $currentYear . '-07-31');
        }
        public function getExamsSecondCuat()
        {
            $currentYear = date("Y");
            return $this->getExamsFromAndTo($currentYear . '-08-01', $currentYear . '-12-31');
        }
    }
