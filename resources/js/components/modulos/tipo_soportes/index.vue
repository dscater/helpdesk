<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tipos de soporte</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-lightblue">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 v-html="textoTitulo"></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label
                                            :class="{
                                                'text-danger': errors.nombre,
                                            }"
                                            >Nombre*</label
                                        >
                                        <el-input
                                            placeholder="Nombre"
                                            :class="{
                                                'is-invalid': errors.nombre,
                                            }"
                                            v-model="oTipoSoporte.nombre"
                                            @keypress.enter.native="
                                                enviarRegistro
                                            "
                                            clearable
                                            maxlength="50"
                                            show-word-limit
                                        >
                                        </el-input>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.nombre"
                                            v-text="errors.nombre[0]"
                                        ></span>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label
                                            :class="{
                                                'text-danger':
                                                    errors.descripcion,
                                            }"
                                            >Descripción</label
                                        >
                                        <el-input
                                            type="textarea"
                                            maxlength="30"
                                            show-word-limit
                                            placeholder="Descripción"
                                            :class="{
                                                'is-invalid':
                                                    errors.descripcion,
                                            }"
                                            v-model="oTipoSoporte.descripcion"
                                            @keypress.enter.native="
                                                enviarRegistro
                                            "
                                            :autosize="{
                                                minRows: 2,
                                                maxRows: 4,
                                            }"
                                            clearable
                                        >
                                        </el-input>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.descripcion"
                                            v-text="errors.descripcion[0]"
                                        ></span>
                                    </div>
                                    <div class="col-md-6">
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-block"
                                            data-dismiss="modal"
                                            @click="limpiaTipoSoporte"
                                        >
                                            Reiniciar
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <el-button
                                            type="primary"
                                            class="bg-lightblue w-100"
                                            :loading="enviando"
                                            @click="enviarRegistro()"
                                            >{{ textoBoton }}</el-button
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card border-primary">
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
                                                thead-class="bg-lightblue"
                                                responsive
                                                :current-page="currentPage"
                                                :per-page="perPage"
                                                @filtered="onFiltered"
                                                empty-text="Sin resultados"
                                                empty-filtered-text="Sin resultados"
                                                :filter="filter"
                                            >
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
                                                                eliminaTipoSoporte(
                                                                    row.item.id,
                                                                    row.item
                                                                        .nombre
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            search: "",
            listRegistros: [],
            errors: [],
            showOverlay: false,
            accion: "nuevo",
            enviando: false,
            fields: [
                {
                    key: "id",
                    label: "Código",
                    sortable: true,
                },
                { key: "nombre", label: "Nombre", sortable: true },
                {
                    key: "descripcion",
                    label: "Descripción",
                    sortable: true,
                },
                { key: "accion", label: "Acción" },
            ],
            loading: true,
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            oTipoSoporte: {
                id: 0,
                nombre: "",
                descripcion: "",
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
    computed: {
        textoBoton() {
            if (this.accion == "nuevo") {
                return "Registrar";
            } else {
                return "Actualizar";
            }
        },
        textoTitulo() {
            if (this.accion == "nuevo") {
                return "AGREGAR REGISTRO";
            } else {
                let aux = this.oTipoSoporte.nombre;
                return (
                    "MODIFICAR REGISTRO: <strong>" +
                    this.oTipoSoporte.id +
                    "</strong>"
                );
            }
        },
    },
    mounted() {
        this.loadingWindow.close();
        this.getTipoSoportes();
    },
    methods: {
        // Seleccionar Opciones de Tabla
        editarRegistro(item) {
            this.accion = "edit";
            this.oTipoSoporte.id = item.id;
            this.oTipoSoporte.nombre = item.nombre ? item.nombre : "";
            this.oTipoSoporte.descripcion = item.descripcion
                ? item.descripcion
                : "";
        },

        // Listar TipoSoportes
        getTipoSoportes() {
            this.showOverlay = true;
            let url = "/admin/tipo_soportes";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.tipo_soportes;
                    this.totalRows = res.data.total;
                });
        },
        enviarRegistro() {
            this.enviando = true;
            try {
                this.textoBtn = "Enviando...";
                let url = "/admin/tipo_soportes";
                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };
                let formdata = new FormData();

                formdata.append(
                    "nombre",
                    this.oTipoSoporte.nombre ? this.oTipoSoporte.nombre : ""
                );
                formdata.append(
                    "descripcion",
                    this.oTipoSoporte.descripcion
                        ? this.oTipoSoporte.descripcion
                        : ""
                );
                if (this.accion == "edit") {
                    url = "/admin/tipo_soportes/" + this.oTipoSoporte.id;
                    formdata.append("_method", "PUT");
                }
                axios
                    .post(url, formdata, config)
                    .then((res) => {
                        this.enviando = false;
                        Swal.fire({
                            icon: "success",
                            title: res.data.msj,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.limpiaTipoSoporte();
                        this.getTipoSoportes();
                        this.errors = [];
                        if (this.accion == "edit") {
                            this.textoBtn = "Actualizar";
                        } else {
                            this.textoBtn = "Registrar";
                        }
                    })
                    .catch((error) => {
                        this.enviando = false;
                        if (this.accion == "edit") {
                            this.textoBtn = "Actualizar";
                        } else {
                            this.textoBtn = "Registrar";
                        }
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            }
                        }
                    });
            } catch (e) {
                this.enviando = false;
                console.log(e);
            }
        },
        eliminaTipoSoporte(id, descripcion) {
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
                        .post("/admin/tipo_soportes/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getTipoSoportes();
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
        abreModal(tipo_accion = "nuevo", tipo_soporte = null) {
            if (tipo_soporte) {
                this.oTipoSoporte = tipo_soporte;
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        limpiaTipoSoporte() {
            this.oTipoSoporte.nombre = "";
            this.oTipoSoporte.descripcion = "";
            this.accion = "nuevo";
        },
    },
};
</script>

<style></style>
