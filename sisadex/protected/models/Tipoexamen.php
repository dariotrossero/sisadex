<?php
/**
 * This is the model class for table "Tipo_Examen".
 *
 * The followings are the available columns in table 'Tipo_Examen':
 * @property string $nombreTipoExamen
 * @property integer $Materia_id
 * @property integer $id
 */
class Tipoexamen extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Tipo_Examen';
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
                'nombreTipoExamen',
                'required',
                'message' => '{attribute} no puede ser vacío.'
                ),
            // array('Materia_id', 'numerical', 'integerOnly'=>true),
            array(
                'nombreTipoExamen',
                'length',
                'max' => 45
                ),
            array(
                'complejidad',
                'required',
                'message' => '{attribute} no puede ser vacío.'
                ),
            array(
                'complejidad',
                'numerical',
                'integerOnly' => true,
                'min' => 1,
                'max' => 10,
                'message' => '{attribute} debe ser un numero entre 1 y 10.'
                ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'nombreTipoExamen, Materia_id, id',
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
            'nombreTipoExamen' => 'Tipo de Examen',
            'Materia_id' => 'Materia',
            'id' => 'ID',
            'complejidad' => 'Complejidad'
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
        $criteria->compare('nombreTipoExamen', $this->nombreTipoExamen, true);
        $criteria->compare('Materia_id', $this->Materia_id);
        $criteria->compare('id', $this->id);
        $criteria->order = 'nombreTipoExamen';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
            ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Tipoexamen the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function searchDefault()
    {
        $criteria            = new CDbCriteria;
        $criteria->condition = 'Materia_id=:arg1';
        $criteria->order     = 'nombreTipoExamen';
        $criteria->params    = array(
            ':arg1' => -1
            );
        $criteria->compare('nombreTipoExamen', $this->nombreTipoExamen, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
            ));
    }
    public function searchPorMaterias($id)
    {
        $criteria            = new CDbCriteria;
        $criteria->condition = 'Materia_id=:arg1';
        $criteria->params    = array(
            ':arg1' => $id
            );
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
            ));
    }
    public function getTiposExamenes($id)
    {
        $criteria            = new CDbCriteria;
        $criteria->condition = 'Materia_id=:arg1 OR Materia_id=:arg2';
        $criteria->params    = array(
            ':arg1' => -1,
            ':arg2' => $id
            );
        $criteria->order     = 'nombreTipoExamen';
        return $this->findAll($criteria);
    }
    public function insertWithoutFail($materia_id, $nombreTipoExamen)
    {
        $criteria            = new CDbCriteria();
        $criteria->select    = 'id';
        $criteria->condition = 'LOWER(nombreTipoExamen)=:nombreTipoExamen AND Materia_id=:Materia_id';
        $criteria->params    = array(
            ':nombreTipoExamen' => strtolower($nombreTipoExamen),
            ':Materia_id' => $materia_id
            );
        $result              = $this->find($criteria);
        if ($result != null)
            return $result->id; //ya existe no lo agrego y devuelvo el id
        else {
            $tipo                   = new Tipoexamen;
            $tipo->nombreTipoExamen = $nombreTipoExamen;
            $tipo->Materia_id       = $materia_id;
            $tipo->save();
            return $tipo->id;
        }
    }

        public function existsInDatabase($nombreTipoExamen, $Materia_id, $id) {
            
            $criteria = new CDbCriteria();
            $criteria->select = 'nombreTipoExamen, Materia_id';
            $criteria->condition = 'LOWER(nombreTipoExamen)=:nombreTipoExamen AND Materia_id=:Materia_id AND id!=:id';
            $criteria->params = array(
            ':nombreTipoExamen' => strtolower($nombreTipoExamen),
            ':Materia_id' => $Materia_id,
            ':id' => $id
                );
            $records = Tipoexamen::model()->find($criteria);
            return (count($records) > 0);
        }

}
