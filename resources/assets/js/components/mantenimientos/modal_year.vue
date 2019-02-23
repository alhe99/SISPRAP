<template>
  <div class="row">
    <div class="col-md-12">
     <div class="row">
      <div class="col-md-12 loading text-center" v-if="loadSpinner">
      </div>
    </div>
    <div class="modal" id="modalChangeYear" tabindex="-4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document" style="margin-top: 60px;">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title text-center text-white" id="exampleModalLongTitle">Año del sistema</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"><br>
           <div class="row">
             <div class="col-md-12">
              <div class="select">
                <select width="100%" v-model="yearSelected" class="yearselect select-text" id="year" >
                </select>
                <label class="select-label">Seleccione un año</label>
              </div><br>
              <span class="font-weight-bold">Año Actual del sistema: {{ app_year }}</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="button red" data-target="#exampleModal" data-dismiss="modal"><i class="mdi mdi-close-box"></i>&nbsp;Cancelar</button>
          <button type="button"class="button blue disabled" id="btnSave" disabled @click="sendNewYear"><i class="mdi mdi-content-save"></i>&nbsp;Guardar</button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</template>
<script>
export default {
	props : ['app_year'],
  data (){
    return {
     modalYear: 0,
     loadSpinner: false,
     yearSelected: this.app_year
   }
 },
 methods:{
  sendNewYear() {
    let me = this;
    me.loadSpinner = true;
    var url = route('changeYearApp',me.yearSelected);
    axios.get(url).then(function(response) {
      me.loadSpinner = false;
      swal({
        position: "center",
        type: "success",
        title: "Año del sistema actualizado",
        showConfirmButton: true,
        width: '350px',
    }).then(function(result){
      window.location.href = route('main');
    }).catch(function(error) {
      console.log(error);
    });
  });
 }
},
mounted(){
  let me = this;
  /* alert(me.app_year); */
  $("#modalChangeYear").on('show.bs.modal',function() {
    $('#year').yearselect({
      start: 2018,
      end: new Date().getFullYear(),
    });
    $("#year option[value="+me.app_year+"]").attr('selected', 'selected');
    $("#year").on('change',function(){
      $("#btnSave").prop('disabled',false);
      $("#btnSave").removeClass('disabled');
    })
  });
}
};
</script>
<style>
/* select starting stylings ------------------------------*/
.select {
  font-family:
  'Roboto','Helvetica','Arial',sans-serif;
  position: relative;
  width: 100%;
}

.select-text {
  position: relative;
  font-family: inherit;
  background-color: transparent;
  width: 100%;
  padding: 10px 10px 10px 0;
  font-size: 18px;
  border-radius: 0;
  border: none;
  border-bottom: 1px solid rgba(0,0,0, 0.12);
}

/* Remove focus */
.select-text:focus {
  outline: none;
  border-bottom: 1px solid rgba(0,0,0, 0);
}

/* Use custom arrow */
.select .select-text {
  appearance: none;
  -webkit-appearance:none
}

.select:after {
  position: absolute;
  top: 18px;
  right: 10px;
  /* Styling the down arrow */
  width: 0;
  height: 0;
  padding: 0;
  content: '';
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 6px solid rgba(0, 0, 0, 0.12);
  pointer-events: none;
}


/* LABEL ======================================= */
.select-label {
  color: rgba(0,0,0, 0.26);
  font-size: 18px;
  font-weight: normal;
  position: absolute;
  pointer-events: none;
  left: 0;
  top: 10px;
  transition: 0.2s ease all;
}

/* active state */
.select-text:focus ~ .select-label, .select-text:valid ~ .select-label {
  color: #2F80ED;
  top: -20px;
  transition: 0.2s ease all;
  font-size: 14px;
}

/* BOTTOM BARS ================================= */
.select-bar {
  position: relative;
  display: block;
  width: 100%;
}

.select-bar:before, .select-bar:after {
  content: '';
  height: 2px;
  width: 0;
  bottom: 1px;
  position: absolute;
  background: #2F80ED;
  transition: 0.2s ease all;
}

.select-bar:before {
  left: 50%;
}

.select-bar:after {
  right: 50%;
}

/* active state */
.select-text:focus ~ .select-bar:before, .select-text:focus ~ .select-bar:after {
  width: 50%;
}

/* HIGHLIGHTER ================================== */
.select-highlight {
  position: absolute;
  height: 60%;
  width: 100px;
  top: 25%;
  left: 0;
  pointer-events: none;
  opacity: 0.5;
}
</style>