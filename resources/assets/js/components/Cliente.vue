<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Clientes
                    <button @click="abrirModal('clientes','registrar')" type="button" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="name">Nombre</option>
                                    <option value="clienteid">Id</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarCliente(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarCliente(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Cliente Id</th>
                            <th>Nombre</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="cliente in arrayClientes" :key="cliente.clienteid">
                            <td>
                                <button @click="abrirModal('clientes','actualizar', cliente)" type="button" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <button v-if="cliente.cantpedidos == 0" type="button" class="btn btn-danger btn-sm" @click="borrarCliente(cliente.clienteid)">
                                    <i class="icon-trash"></i>
                                </button>
                            </td>
                            <td v-text="cliente.clienteid"></td>
                            <td v-text="cliente.name"></td>
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
                                <label class="col-md-3 form-control-label">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre">
                                </div>
                            </div>
                            <div v-show="errorCliente" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjCliente" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion==1" @click="registrarCliente()">Guardar</button>
                        <button type="button" class="btn btn-primary" v-if="tipoAccion==2" @click="actualizarCliente()">Actualizar</button>
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
                cliente_id : 0,
                name : '',
                arrayClientes : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
                errorMostrarMsjCliente : [],
                pagination : {
                    'total' : 0,
                    'current_page'  : 0,
                    'per_page'  :  0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' :  0,
                },
                offset : 3,
                criterio : 'name',
                buscar : ''
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
            listarCliente(page,buscar,criterio){
                let me = this;
                var url = '/clientes?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayClientes = respuesta.clientes.data;
                    me.pagination = respuesta.pagination;
                })
                    .catch( function (error) {
                        console.log(error);
                    });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarCliente(page,buscar,criterio);
            },
            registrarCliente(){
                if(this.validarCliente()){
                    return;
                }
                let me = this;
                axios.post('/clientes/registrar',{
                    'name' : this.name,
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCliente(1, '', 'name');
                }).catch(function (error){
                    console.log(error);
                })
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case 'clientes':
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.name = '';
                                this.tituloModal = 'Registrar Cliente';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.cliente_id = data['clienteid'];
                                this.name = data['name'];
                                this.tituloModal = 'Actualizar Cliente';
                                this.tipoAccion = 2;
                                console.log(data);
                                break;
                            }
                        }
                    }
                }

            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
            },
            validarCliente(){
                this.errorCliente = 0;
                this.errorMostrarMsjCliente = [];
                if(!this.name.trim()) this.errorMostrarMsjCliente.push("El nombre del cliente no puede estar vacío.")
                if(this.errorMostrarMsjCliente.length) this.errorCliente = 1;
                return this.errorCliente;
            },
            actualizarCliente(){
                if(this.validarCliente()){
                    return;
                }
                let me = this;
                axios.put('/clientes/actualizar',{
                    'clienteid' : this.cliente_id,
                    'name' : this.name,
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCliente(1, '', 'name');
                }).catch(function (error){
                    console.log(error);
                })
            },
            borrarCliente(clienteid){
                const swalWithBootstrapButtons = swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                    title: 'Esta seguro de borrar el cliente?',
                    text: "Esta accion es irreversible",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/clientes/borrar',{
                            'clienteid' : clienteid
                        }).then(function (response){
                            me.listarCliente(1, '', 'name');
                            swalWithBootstrapButtons(
                                'Borrado',
                                'El cliente fue borrado.',
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
                            'No se borró ningun cliente',
                            'error'
                        )
                    }
                })
            },
        },
        mounted() {
            this.listarCliente(1, this.buscar, this.criterio);
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