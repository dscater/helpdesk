<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Usuarios</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button
                                            v-if="
                                                permisos.includes(
                                                    'usuarios.create'
                                                )
                                            "
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                            @click="
                                                abreModal('nuevo');
                                                limpiaUsuario();
                                            "
                                        >
                                            <i class="fa fa-plus"></i>
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <b-col lg="10" class="my-1">
                                        <b-form-group
                                            label="Buscar"
                                            label-for="filter-input"
                                            label-cols-sm="3"
                                            label-align-sm="right"
                                            label-size="sm"
                                            class="mb-0"
                                        >
                                            <b-input-group size="sm">
                                                <b-form-input
                                                    id="filter-input"
                                                    v-model="filter"
                                                    type="search"
                                                    placeholder="Buscar"
                                                ></b-form-input>

                                                <b-input-group-append>
                                                    <b-button
                                                        class="bg-lightblue"
                                                        variant="primary"
                                                        :disabled="!filter"
                                                        @click="filter = ''"
                                                        >Borrar</b-button
                                                    >
                                                </b-input-group-append>
                                            </b-input-group>
                                        </b-form-group>
                                    </b-col>
                                    <div class="col-md-12">
                                        <b-overlay
                                            :show="showOverlay"
                                            rounded="sm"
                                        >
                                            <b-table
                                                :fields="fields"
                                                :items="listRegistros"
                                                show-empty
                                                stacked="md"
                                                responsive
                                                :current-page="currentPage"
                                                :per-page="perPage"
                                                @filtered="onFiltered"
                                                empty-text="Sin resultados"
                                                empty-filtered-text="Sin resultados"
                                                :filter="filter"
                                            >
                                                <template #cell(foto)="row">
                                                    <b-avatar
                                                        :src="
                                                            row.item.path_image
                                                        "
                                                        size="3rem"
                                                    ></b-avatar>
                                                </template>

                                                <template
                                                    #cell(fecha_registro)="row"
                                                >
                                                    {{
                                                        formatoFecha(
                                                            row.item
                                                                .fecha_registro
                                                        )
                                                    }}
                                                </template>

                                                <template #cell(accion)="row">
                                                    <div
                                                        class="row justify-content-between"
                                                    >
                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-warning"
                                                            class="btn-flat btn-block"
                                                            title="Editar registro"
                                                            @click="
                                                                editarRegistro(
                                                                    row.item
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-edit"
                                                            ></i>
                                                        </b-button>
                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-danger"
                                                            class="btn-flat btn-block"
                                                            title="Eliminar registro"
                                                            @click="
                                                                eliminaUsuario(
                                                                    row.item.id,
                                                                    row.item
                                                                        .full_name
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-trash"
                                                            ></i>
                                                        </b-button>
                                                    </div>
                                                </template>
                                            </b-table>
                                        </b-overlay>
                                        <div class="row">
                                            <b-col
                                                sm="6"
                                                md="2"
                                                class="ml-auto my-1"
                                            >
                                                <b-form-select
                                                    align="right"
                                                    id="per-page-select"
                                                    v-model="perPage"
                                                    :options="pageOptions"
                                                    size="sm"
                                                ></b-form-select>
                                            </b-col>
                                            <b-col
                                                sm="6"
                                                md="2"
                                                class="my-1 mr-auto"
                                                v-if="perPage"
                                            >
                                                <b-pagination
                                                    v-model="currentPage"
                                                    :total-rows="totalRows"
                                                    :per-page="perPage"
                                                    align="left"
                                                ></b-pagination>
                                            </b-col>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <Nuevo
            :muestra_modal="muestra_modal"
            :accion="modal_accion"
            :usuario="oUsuario"
            @close="muestra_modal = false"
            @envioModal="getUsuarios"
        ></Nuevo>
    </div>
</template>

<script>
import Nuevo from "./Nuevo.vue";
export default {
    components: {
        Nuevo,
    },
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            search: "",
            listRegistros: [],
            showOverlay: false,
            fields: [
                {
                    key: "usuario",
                    label: "Usuario",
                    sortable: true,
                },
                { key: "full_name", label: "Nombre", sortable: true },
                { key: "full_ci", label: "C.I.", sortable: true },
                {
                    key: "informacion_usuario.dir",
                    label: "Dirección",
                    sortable: true,
                },
                {
                    key: "informacion_usuario.correo",
                    label: "Correo",
                    sortable: true,
                },
                {
                    key: "informacion_usuario.cel",
                    label: "Celular",
                    sortable: true,
                },
                {
                    key: "informacion_usuario.fono",
                    label: "Teléfono",
                    sortable: true,
                },
                { key: "tipo", label: "Rol", sortable: true },
                { key: "foto", label: "Foto" },
                {
                    key: "fecha_registro",
                    label: "Fecha de registro",
                    sortable: true,
                },
                { key: "accion", label: "Acción" },
            ],
            loading: true,
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            muestra_modal: false,
            modal_accion: "nuevo",
            oUsuario: {
                id: 0,
                nombre: "",
                paterno: "",
                materno: "",
                ci: "",
                ci_exp: "",
                dir: "",
                correo: "",
                fono: "",
                cel: "",
                tipo: "",
                foto: null,
            },
            currentPage: 1,
            perPage: 5,
            pageOptions: [
                { value: 5, text: "Mostrar 5 Registros" },
                { value: 10, text: "Mostrar 10 Registros" },
                { value: 25, text: "Mostrar 25 Registros" },
                { value: 50, text: "Mostrar 50 Registros" },
                { value: 100, text: "Mostrar 100 Registros" },
                { value: this.totalRows, text: "Mostrar Todo" },
            ],
            totalRows: 10,
            filter: null,
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getUsuarios();
    },
    methods: {
        // Seleccionar Opciones de Tabla
        editarRegistro(item) {
            this.oUsuario.id = item.id;
            this.oUsuario.nombre = item.informacion_usuario.nombre
                ? item.informacion_usuario.nombre
                : "";
            this.oUsuario.paterno = item.informacion_usuario.paterno
                ? item.informacion_usuario.paterno
                : "";
            this.oUsuario.materno = item.informacion_usuario.materno
                ? item.informacion_usuario.materno
                : "";
            this.oUsuario.ci = item.informacion_usuario.ci
                ? item.informacion_usuario.ci
                : "";
            this.oUsuario.ci_exp = item.informacion_usuario.ci_exp
                ? item.informacion_usuario.ci_exp
                : "";
            this.oUsuario.dir = item.informacion_usuario.dir
                ? item.informacion_usuario.dir
                : "";
            this.oUsuario.correo = item.informacion_usuario.correo
                ? item.informacion_usuario.correo
                : "";
            this.oUsuario.fono = item.informacion_usuario.fono
                ? item.informacion_usuario.fono
                : "";
            this.oUsuario.cel = item.informacion_usuario.cel
                ? item.informacion_usuario.cel
                : "";
            this.oUsuario.tipo = item.tipo ? item.tipo : "";
            this.modal_accion = "edit";
            this.muestra_modal = true;
        },

        // Listar Usuarios
        getUsuarios() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/usuarios";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.usuarios;
                    this.totalRows = res.data.total;
                });
        },
        eliminaUsuario(id, descripcion) {
            Swal.fire({
                title: "¿Quierés eliminar este registro?",
                html: `<strong>${descripcion}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#05568e",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/usuarios/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getUsuarios();
                            this.filter = "";
                            Swal.fire({
                                icon: "success",
                                title: res.data.msj,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        });
                }
            });
        },
        abreModal(tipo_accion = "nuevo", usuario = null) {
            this.muestra_modal = true;
            this.modal_accion = tipo_accion;
            if (usuario) {
                this.oUsuario = usuario;
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        limpiaUsuario() {
            this.oUsuario.nombre = "";
            this.oUsuario.paterno = "";
            this.oUsuario.materno = "";
            this.oUsuario.ci = "";
            this.oUsuario.ci_exp = "";
            this.oUsuario.dir = "";
            this.oUsuario.correo = "";
            this.oUsuario.fono = "";
            this.oUsuario.cel = "";
            this.oUsuario.tipo = "";
            this.oUsuario.foto = null;
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD [de] MMMM [del] YYYY");
        },
    },
};
</script>

<style></style>
