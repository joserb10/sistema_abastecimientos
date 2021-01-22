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
                        <i class="fa fa-align-justify"></i> Seguimiento Compras
                    </div>
                    <!-- Listado-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="created_at">Fecha Creación</option>
                                      <option value="estado_entrega">Estado de Entrega</option>
                                      <option value="fecha_entrega">Fecha de Entrega</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarSeguimiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarSeguimiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código Venta</th>
                                        <th>Nombre de Cliente</th>
                                        <th>Fecha final proceso adquisición</th>
                                        <th>Fecha final proceso empaquetamiento</th>
                                        <th>Fecha final proceso instalación</th>
                                        <th>Fecha de Entrega</th>
                                        <th>Fecha de Entrega Real</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="seguimiento in arraySeguimientos" :key="seguimiento.id">
                                        <td>
                                            <button type="button" @click="entregarVenta(seguimiento.id)" :class="[seguimiento.fecha_entrega_real == null ? 'btn btn-success btn-sm' : 'Disabled']">
                                            <i class="fa fa-check-circle-o"></i>
                                            </button>
                                            <button type="button" @click="abrirModal(seguimiento)" style="color:#fff; border-color:#31a4d5; border-radius: 3px; background-color:#31a4d5">
                                            <i class="fa fa-commenting-o"></i>
                                            </button>
                                        </td>
                                        <td><span v-text="seguimiento.detalle_venta_id"></span></td>
                                        <td><span v-text="seguimiento.nombre_cliente"></span></td>
                                        <td v-if="seguimiento.fecha_proceso_proveedor"><span v-text="'Finaliza el: '+seguimiento.fecha_proceso_proveedor" class="badge badge-info"></span></td>
                                        <td v-if="!seguimiento.fecha_proceso_proveedor"><span v-text="'No posee'" class="badge badge-dark"></span></td>
                                        <td><span v-text="'Finaliza el: '+seguimiento.fecha_proceso_almacen" class="badge badge-info"></span></td>
                                        <td v-if="seguimiento.fecha_proceso_inst_softw"><span v-text="'Finaliza el: '+seguimiento.fecha_proceso_inst_softw" class="badge badge-info"></span></td>
                                        <td v-if="!seguimiento.fecha_proceso_inst_softw"><span v-text="'No posee'" class="badge badge-dark"></span></td>
                                        <td><span v-text="'Fecha límite: '+seguimiento.fecha_entrega" class="badge badge-success"></span></td>
                                        <td v-if="seguimiento.fecha_entrega_real" ><span v-text="'Se entregó el: '+seguimiento.fecha_entrega_real" class="badge badge-warning"></span></td>
                                        <td v-if="!seguimiento.fecha_entrega_real"><span v-text="'Aún no se ha entregado'" class="badge badge-primary"></span></td>
                                        <td><span v-text="seguimiento.estado_entrega" :class="[seguimiento.fecha_entrega_real == null ? 'badge badge-danger' : 'badge badge-success']"></span></td>
                                        <!-- <td v-text="solicitud.estado" v-bind:class= "[solicitud.estado == 'en espera' ? 'text-danger font-weight-bold' : 'text-success font-weight-bold']"></td>                      -->
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

                </div>
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
                                    <label class="col-md-2 form-control-label" for="email-input">Id Venta</label>
                                    <div class="col-md-10">
                                        <input disabled type="text" v-model="detalle_venta_id" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="email-input">Nombre Cliente</label>
                                    <div class="col-md-10">
                                        <input disabled type="text" v-model="nombre_cliente" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="email-input">Descripción Observación</label>
                                    <div class="col-md-10">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorObservacion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjObservacion" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="agregarObservacion()">Aceptar</button>
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
                seguimiento_id: 0,
                detalle_venta_id: 0,
                descripcion: '',
                estado: '',
                nombre_cliente: '',
                arraySeguimientos : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                errorObservacion : 0,
                errorMostrarMsjObservacion : [],
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
        },
        methods : {
            listarSeguimiento(page,buscar,criterio){
                let me=this;
                var url= '/seguimiento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySeguimientos = respuesta.seguimientos.data;
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
            entregarVenta(id){
                let me=this;
                // me.listado=2;
                
                var url= '/seguimiento/actualizar';
                
                axios.put(url,{
                    'id': id
                }).then(function (response) {
                    me.listarSeguimiento(1,'','created_at');
                    swal(
                    'Enviado!',
                    'La venta ha sido enviada al cliente.',
                    'success'
                    )
                }).catch(function (error) {
                    console.log(error);
                });
              
            },
            abrirModal(seguimiento){
                this.modal = 1;
                this.tituloModal = 'Añadir Observación a la venta';
                this.seguimiento_id = seguimiento.id;
                this.detalle_venta_id = seguimiento.detalle_venta_id;
                this.nombre_cliente = seguimiento.nombre_cliente;

            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.seguimiento_id = 0;
                this.detalle_venta_id = 0;
                this.descripcion = '';
                this.nombre_cliente = '';
            },
            agregarObservacion(){
                if(this.validarObservacion()){
                    return;
                }

                let me = this;
                url = '/observacion/registrar'
                axios.post(url,{
                    'detalle_venta_id': this.detalle_venta_id,
                    'descripcion': this.descripcion

                }).then(function (response) {
                    
                    me.listarSeguimiento(1,'','created_at');
                    swal(
                    'Observación añadida!',
                    'La observación se encuentra pendiente.',
                    'success'
                    )
                    me.modal = 0;
                    me.tituloModal = '';
                    me.seguimiento_id = 0;
                    me.descripcion = '';
                    me.nombre_cliente = '';
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarObservacion(){
                this.errorObservacion=0;
                this.errorMostrarMsjObservacion =[];

                if (!this.descripcion) this.errorMostrarMsjObservacion.push("La descripcion de la observación no puede estar vacía.");

                if (this.errorMostrarMsjObservacion.length) this.errorObservacion = 1;

                return this.errorObservacion;
            }
        },
        mounted() {
            this.listarSeguimiento(1,this.buscar,this.criterio);
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
