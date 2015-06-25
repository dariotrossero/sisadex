
      function dragMateria(materia, event) {
        event.dataTransfer.setData('Materia', materia.id);
      }

      function dropMateria(target, event) {
        var materia = event.dataTransfer.getData('materia');
        
        target.appendChild(document.getElementById(materia));
        div_target=target.id;
      
      }


       
       