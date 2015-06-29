

<head>


  <?php 
  Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/timeline/timeline.js');
  Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/timeline/timeline-locales.js');
  Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/underscore-min.js');
  Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/js/metricas-timeline.js', CClientScript::POS_END);

  Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/timeline/timeline.css'); 
  $browser = new EWebBrowser();
  ?>



</head>

<body  onload="drawVisualization();">

  <div class="titulo">
    <h1>Linea de tiempo</h1>
  </div>


  <?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>array(
      array('label'=>'Calendario', 'url'=>array('/metrica/calendar')),
      array('label'=>'Linea de tiempo', 'url'=>'#', 'active'=>true),
      array('label'=>'Evolución', 'url'=>array('/metrica/evolution')),

      ),
      )); ?>


      <div id="data-container">

        <div  id="planes-metricas" class="drop" ondrop="dropElement(this, event)" ondragenter="return false" ondragover="return false" > 


          <?php

          $criteria = new CDbCriteria();
          $criteria->order = 'anioPlan';
          $planes = Plan::model()->findAll($criteria); 


          echo '<ul class="lista">';
          foreach($planes as $item) {
            $codigo=$item->id;
            $anio=$item->anioPlan;
            $nombre=$item->carrera->nombreCarrera;
            if ($browser->getBrowser()=="Internet Explorer") 
                echo '<a href="#" onclick="return false;" draggable="true" class="plan" id="'.$codigo.'" ondragstart="dragElement(this, event,0)">'.$anio.' - '.$nombre.'</a>';
            else
                echo '<span draggable="true" class="plan" id="'.$codigo.'" ondragstart="dragElement(this, event,0)">'.$anio.' - '.$nombre.'</span>';
          }

          echo '</ul>';
          ?>



        </div>

        <div id="middle">

         <div id="buttons">
          <?php $this->widget('bootstrap.widgets.TbButton', array(
           'buttonType'=>'button',
           'type'=>'action',
           'icon'=>' icon-remove-sign',
           'label'=>'Limpiar',
           'htmlOptions'=>array('onclick' => 'reset()',  
            'title' => 'Elimina todos los elementos'),
            )); ?>


          <?php $this->widget('bootstrap.widgets.TbButton', array(
           'buttonType'=>'button',
           'type'=>'action',
           'label'=>'1° Cuat.',
           'htmlOptions'=>array('onclick' => 'go2FirstCuat()',
             'title' => 'Mostrar solo el primer cuatrimestre'),
             )); ?>
          <?php $this->widget('bootstrap.widgets.TbButton', array(
           'buttonType'=>'button',
           'type'=>'action',
           'label'=>'2° Cuat.',
           'htmlOptions'=>array('onclick' => 'go2SecondCuat()',
             'title' => 'Mostrar solo el segundo cuatrimestre'),
             )); ?>
         

          <?php $this->widget('bootstrap.widgets.TbButton', array(
           'buttonType'=>'button',
           'type'=>'action',
           'icon'=>' icon-resize-small',

           'htmlOptions'=>array('onclick' => 'restore()',
             'title' => 'Restarura la linea de tiempo al tamaño original'),
             )); ?>
              <?php $this->widget('bootstrap.widgets.TbButton', array(
           'buttonType'=>'button',
           'type'=>'action',

           'icon'=>'  icon-resize-full',

           'htmlOptions'=>array('onclick' => 'zoom()',
            'title' => 'Amplia la linea de tiempo'),
            )); ?>
           </div>

           <div id="wait_animation"><div class="circle"></div><div class="circle1"></div></div>
           

          Arrastra los elementos a la linea de tiempo
            <br/>       
            <div id="colorScale">
             <svg x="0" y="0" class="graph-legend" height="20" width="220">
            <g transform="">
              <rect width="20" height="20" x="0" class="r1" fill-opacity="1" fill="#81e62e">
                <!-- <title>Complejidad de examen 1</title> -->
              </rect>
              <rect width="20" height="20" x="22" class="r2" fill-opacity="1" fill="#a1e62e">
                <!-- <title>Complejidad de examen 2</title> -->
            </rect>
              <rect width="20" height="20" x="44" class="r3" fill-opacity="1" fill="#c0e62e">
                <!-- <title>Complejidad de examen 3</title> -->
            </rect>
              <rect width="20" height="20" x="66" class="r4" fill-opacity="1" fill="#e0e62e">
                <!-- <title>Complejidad de examen 4</title> -->
            </rect>
              <rect width="20" height="20" x="88" class="r5" fill-opacity="1" fill="#e6cb2e">
                <!-- <title>Complejidad de examen 5 </title> -->
            </rect>
              <rect width="20" height="20" x="110" class="r6" fill-opacity="1" fill="#e6ac2e">
                <!-- <title>Complejidad de examen 6</title> -->
            </rect>
              <rect width="20" height="20" x="132" class="r7" fill-opacity="1" fill="#e68c2e">
                <!-- <title>Complejidad de examen 7</title> -->
            </rect>
              <rect width="20" height="20" x="154" class="r8" fill-opacity="1" fill="#e66d2e">
                <!-- <title>Complejidad de examen 8</title> -->
            </rect>
              <rect width="20" height="20" x="176" class="r9" fill-opacity="1" fill="#e64d2e">
                <!-- <title>Complejidad de examen 9</title> -->
            </rect>
              <rect width="20" height="20" x="198" class="r10" fill-opacity="1" fill="#e62e2e">
                <!-- <title>Complejidad de examen 10 </title> -->
            </rect>
            </g>

          </svg>

        </div>
        <div id="target" class="drop " ondrop="dropElement(this, event)" ondragover="allowDrop(event)"> </div>

        <div id="target-area"></div>
      </div>


      <div  id="materias-metricas" class="drop" ondrop="dropElement(this, event)" ondragenter="return false" ondragover="return false" > 

        <!-- Generacion de las materias dropeables -->
        <?php

        $materias=Materia::model()->getTodasLasMaterias('nombreMateria'); 
        echo '<ul class="lista">';
        foreach($materias as $item) {
          $codigo=$item->id;
          $nombre=$item->nombreMateria;
          if ($browser->getBrowser()=="Internet Explorer") 
      echo '<a href="#" onclick="return false;" draggable="true" class="materia" id="'.$codigo.'" ondragstart="dragElement(this, event,1)">'.$nombre.' - '.$codigo.'</a>';
    else
      echo '<span draggable="true" class="materia" id="'.$codigo.'" ondragstart="dragElement(this, event,1)">'.$nombre.' - '.$codigo.'</span>';      
        }

        echo '</ul>';
        ?>
        <!-- FIN Generacion de las materias dropeables -->


      </div>
    </div>
  </body>

