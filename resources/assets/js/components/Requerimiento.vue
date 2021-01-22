<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Requerimientos
                    </div>
                    <!-- Listado-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="created_at">Fecha Creación</option>
                                      <option value="descripcion">Descripcion</option>
                                      <option value="nombre_cliente">Nombre del Cliente</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarSolicitud(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarSolicitud(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Fecha de la Venta</th>
                                        <th>Nombre del cliente</th>
                                        <th>Requerimiento</th>
                                        <th>Descripcion</th>
                                        <th>Tiempo de Instalación</th>
                                        <!-- <th>Estado</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="requerimiento in arrayRequerimientos" :key="requerimiento.id">
                                        <td>
                                            <button type="button" @click="abrirModal(requerimiento)" :class="[!requerimiento.tiempo_inst_softw ? 'btn btn-success btn-sm' : 'Disabled']">
                                            <i class="icon-eye"></i>
                                            </button>
                                        </td>
                                        <td v-text="requerimiento.fecha_hora"></td>
                                        <td v-text="requerimiento.nombre_cliente"></td>
                                        <td v-text="requerimiento.requerimiento"></td>
                                        <td v-text="requerimiento.descripcion"></td>
                                        <td v-if="!requerimiento.tiempo_inst_softw" v-text="'Tiempo no establecido'" class="text-danger font-weight-bold"></td>                     
                                        <td v-if="requerimiento.tiempo_inst_softw" v-text="requerimiento.tiempo_inst_softw" class="text-success font-weight-bold"></td>                     
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!--Fin Listado-->
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Requerimiento</label>
                                    <div class="col-md-9">
                                        <input disabled type="text" v-model="requerimiento" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input disabled type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Tiempo para la Instalación (*días)</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="tiempo_inst_softw" class="form-control" placeholder="Ingrese el tiempo en días">
                                    </div>
                                </div>
                                <div v-show="errorTiempo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjTiempo" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="establecerTiempo()">Establecer Tiempo</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        data (){
            return {
                requerimiento_id: 0,
                requerimiento: '',
                descripcion: '',
                nombre_cliente : '',
                tiempo_inst_softw : 0,
                arrayRequerimientos : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                errorTiempo : 0,
                errorMostrarMsjTiempo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'created_at',
                buscar : '',
 
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            // calcularTotal: function(){
            //     var resultado=0.0;
            //     for(var i=0;i<this.arrayDetalle.length;i++){
            //         resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)
            //     }
            //     return resultado;
            // }
        },
        methods : {
            listarRequerimientos(page,buscar,criterio){
                let me=this;
                var url= '/requerimiento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRequerimientos = respuesta.requerimientos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarSolicitudes(page,buscar,criterio);
            },
            ocultarDetalle(){
                this.listado=1;
            },
            abrirModal(requerimiento){
                let me=this;
                me.tituloModal = 'Añadir Tiempo de instalación de Software'
                me.requerimiento_id=requerimiento.id;
                me.requerimiento=requerimiento.requerimiento;
                me.descripcion=requerimiento.descripcion;
                
                me.modal = 1;
            },
            cerrarModal(requerimiento){
                let me=this;
                me.tituloModal = '';
                me.requerimiento_id= 0;
                me.requerimiento='';
                me.tituloModal=0;
                me.descripcion='';
                me.tiempo_inst_softw=0;
                
                me.modal = 0;
            },
            establecerTiempo(){
                if (this.validarTiempo()){
                    return;
                }
                let me=this;

                axios.put('/requerimiento/actualizar',{
                    'requerimiento_id': this.requerimiento_id,
                    'tiempo_inst_softw': this.tiempo_inst_softw,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarRequerimientos(1,'','created_at');
                }).catch(function (error) {
                    console.log(error);
                }); 
                
            },
            validarTiempo(){
                this.errorTiempo=0;
                this.errorMostrarMsjTiempo =[];
                if (!this.tiempo_inst_softw) this.errorMostrarMsjTiempo.push("El Tiempo no puede estar vacío.");

                if (this.errorMostrarMsjTiempo.length) this.errorTiempo = 1;

                return this.errorTiempo;
            }
        },
        mounted() {
            this.listarRequerimientos(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
    .Disabled{
  pointer-events: none;
  cursor: not-allowed;
  opacity: 0.65;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
  box-shadow: none;}

</style>
