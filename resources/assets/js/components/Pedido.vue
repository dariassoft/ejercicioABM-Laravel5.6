<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Pedidos
                    <button @click="abrirModal('pedidos','registrar')" type="button" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="descripcion">descripcion</option>
                                    <option value="pedidoid">Id</option>
                                    <option value="clienteid">ClienteId</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarPedido(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarPedido(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Pedido Id</th>
                            <th>Cliente</th>
                            <th>Descripcion</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="pedido in arrayPedidos" :key="pedido.pedidoid">
                            <td>
                                <button @click="abrirModal('pedidos','actualizar', pedido)" type="button" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <button type="button" class="btn btn-danger btn-sm" @click="borrarPedido(pedido.pedidoid)">
                                    <i class="icon-trash"></i>
                                </button>
                            </td>
                            <td v-text="pedido.pedidoid"></td>
                            <td v-text="pedido.clienteid + ' - ' + pedido.name"></td>
                            <td v-text="pedido.descripcion"></td>
                        </tr>
                        </tbody>
                    </table>
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
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                <label class="col-md-3 form-control-label">Cliente</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="cliente_id">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="cliente in arrayClientes" :key="cliente.clienteid" :value="cliente.clienteid" v-text="cliente.name"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Descripción del Pedido</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Descripción">
                                </div>
                            </div>
                            <div v-show="errorPedido" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPedido" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion==1" @click="registrarPedido()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion==2" @click="actualizarPedido()">Actualizar</button>
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
    export default {
        data () {
            return {
                pedido_id : 0,
                cliente_id : 0,
                nombre_cliente : '',
                descripcion : '',
                arrayPedidos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPedido : 0,
                errorMostrarMsjPedido : [],
                pagination : {
                    'total' : 0,
                    'current_page'  : 0,
                    'per_page'  :  0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' :  0,
                },
                offset : 3,
                criterio : 'descripcion',
                buscar : '',
                arrayClientes : [],
            }
        },
        computed : {
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

            }
        },
        methods : {
            listarPedido(page,buscar,criterio){
                let me = this;
                var url = '/pedidos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPedidos = respuesta.pedidos.data;
                    me.pagination = respuesta.pagination;
                })
                    .catch( function (error) {
                        console.log(error);
                    });
            },
            selectCliente(){
                let me=this;
                var url= '/clientes/selectCliente';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayClientes = respuesta.clientes;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarPedido(page,buscar,criterio);
            },
            registrarPedido(){
                if(this.validarPedido()){
                    return;
                }
                let me = this;
                axios.post('/pedidos/registrar',{
                    'clienteid' : this.cliente_id,
                    'descripcion' : this.descripcion,
                }).then(function (response){
                    me.cerrarModal();
                    me.listarPedido(1, '', 'descripcion');
                }).catch(function (error){
                    console.log(error);
                })
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case 'pedidos':
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.cliente_id = 0;
                                this.descripcion = '';
                                this.tituloModal = 'Registrar Pedidos';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.pedido_id = data['pedidoid'];
                                this.cliente_id = data['clienteid'];
                                this.descripcion = data['descripcion'];
                                this.tituloModal = 'Actualizar Pedido';
                                this.tipoAccion = 2;
                                console.log(data);
                                break;
                            }
                        }
                    }
                }
                this.selectCliente();
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.descripcion = '';
            },
            validarPedido(){
                this.errorPedido = 0;
                this.errorMostrarMsjPedido = [];
                if(!this.cliente_id) this.errorMostrarMsjPedido.push("Debe seleccionar un cliente.")
                if(!this.descripcion.trim()) this.errorMostrarMsjPedido.push("La descripcion no puede estar vacío.")
                if(this.errorMostrarMsjPedido.length) this.errorPedido = 1;
                return this.errorPedido;
            },
            actualizarPedido(){
                if(this.validarPedido()){
                    return;
                }
                let me = this;
                axios.put('/pedidos/actualizar',{
                    'pedido_id' : this.pedido_id,
                    'descripcion' : this.descripcion,
                    'cliente_id' : this.cliente_id,
                }).then(function (response){
                    me.cerrarModal();
                    me.listarPedido(1, '', 'descripcion');
                }).catch(function (error){
                    console.log(error);
                })
            },
            borrarPedido(pedidoid){
                const swalWithBootstrapButtons = swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                    title: 'Esta seguro de borrar el pedido?',
                    text: "Esta accion es irreversible",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/pedidos/borrar',{
                            'pedidoid' : pedidoid
                        }).then(function (response){
                            me.listarPedido(1, '', 'descripcion');
                            swalWithBootstrapButtons(
                                'Borrado',
                                'El pedido fue borrado.',
                                'success'
                            )
                        }).catch(function (error){
                            console.log(error);
                        });
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons(
                            'Cancelado',
                            'No se borró ningun pedido',
                            'error'
                        )
                    }
                })
            },
        },
        mounted() {
            this.listarPedido(1, this.buscar, this.criterio);
            //console.log('Component mounted.')
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

</style>