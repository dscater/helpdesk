<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>FAQ's</h1>
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
                                                'text-danger':
                                                    errors.tipo_soporte_id,
                                            }"
                                            >Tipo de soporte*</label
                                        >
                                        <el-select
                                            class="w-100"
                                            :class="{
                                                'is-invalid':
                                                    errors.tipo_soporte_id,
                                            }"
                                            v-model="oFaq.tipo_soporte_id"
                                            clearable
                                        >
                                            <el-option
                                                v-for="(
                                                    item, index
                                                ) in listTipoSoportes"
                                                :key="index"
                                                :value="item.id"
                                                :label="item.nombre"
                                            >
                                            </el-option>
                                        </el-select>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.tipo_soporte_id"
                                            v-text="errors.tipo_soporte_id[0]"
                                        ></span>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label
                                            :class="{
                                                'text-danger': errors.pregunta,
                                            }"
                                            >Pregunta*</label
                                        >
                                        <el-input
                                            type="textarea"
                                            maxlength="200"
                                            :autosize="{
                                                minRows: 2,
                                                maxRows: 4,
                                            }"
                                            placeholder="Pregunta"
                                            :class="{
                                                'is-invalid': errors.pregunta,
                                            }"
                                            v-model="oFaq.pregunta"
                                            @keypress.enter.native="
                                                enviarRegistro
                                            "
                                            clearable
                                            show-word-limit
                                        >
                                        </el-input>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.pregunta"
                                            v-text="errors.pregunta[0]"
                                        ></span>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label
                                            :class="{
                                                'text-danger': errors.respuesta,
                                            }"
                                            >Respuesta*</label
                                        >
                                        <el-input
                                            type="textarea"
                                            maxlength="200"
                                            show-word-limit
                                            placeholder="Respuesta"
                                            :class="{
                                                'is-invalid': errors.respuesta,
                                            }"
                                            v-model="oFaq.respuesta"
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
                                            v-if="errors.respuesta"
                                            v-text="errors.respuesta[0]"
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
                                                                        .pregunta
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
                    label: "Nro.",
                    sortable: true,
                },
                {
                    key: "tipo_soporte.nombre",
                    label: "Tipo de Soporte",
                    sortable: true,
                },
                { key: "pregunta", label: "Pregunta", sortable: true },
                {
                    key: "respuesta",
                    label: "Respuesta",
                    sortable: true,
                },
                { key: "accion", label: "Acción" },
            ],
            loading: true,
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            oFaq: {
                id: 0,
                tipo_soporte_id: "",
                pregunta: "",
                respuesta: "",
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
            listTipoSoportes: [],
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
                let aux = this.oFaq.pregunta;
                return (
                    "MODIFICAR REGISTRO: <strong>" + this.oFaq.id + "</strong>"
                );
            }
        },
    },
    mounted() {
        this.loadingWindow.close();
        this.getFaqs();
        this.getTipoSoportes();
    },
    methods: {
        // OBTENER TIPOS DE SOPORTES
        getTipoSoportes() {
            axios.get("/admin/tipo_soportes").then((response) => {
                this.listTipoSoportes = response.data.tipo_soportes;
            });
        },
        // Seleccionar Opciones de Tabla
        editarRegistro(item) {
            this.accion = "edit";
            this.oFaq.id = item.id;
            this.oFaq.tipo_soporte_id = item.tipo_soporte_id
                ? item.tipo_soporte_id
                : "";
            this.oFaq.pregunta = item.pregunta ? item.pregunta : "";
            this.oFaq.respuesta = item.respuesta ? item.respuesta : "";
        },

        // Listar TipoSoportes
        getFaqs() {
            this.showOverlay = true;
            let url = "/admin/faqs";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.faqs;
                    this.totalRows = res.data.total;
                });
        },
        enviarRegistro() {
            this.enviando = true;
            try {
                this.textoBtn = "Enviando...";
                let url = "/admin/faqs";
                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };
                let formdata = new FormData();
                formdata.append(
                    "tipo_soporte_id",
                    this.oFaq.tipo_soporte_id ? this.oFaq.tipo_soporte_id : ""
                );
                formdata.append(
                    "pregunta",
                    this.oFaq.pregunta ? this.oFaq.pregunta : ""
                );
                formdata.append(
                    "respuesta",
                    this.oFaq.respuesta ? this.oFaq.respuesta : ""
                );
                if (this.accion == "edit") {
                    url = "/admin/faqs/" + this.oFaq.id;
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
                        this.getFaqs();
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
        eliminaTipoSoporte(id, respuesta) {
            Swal.fire({
                title: "¿Quierés eliminar este registro?",
                html: `<strong>${respuesta}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#05568e",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/faqs/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getFaqs();
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
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        limpiaTipoSoporte() {
            this.oFaq.tipo_soporte_id = "";
            this.oFaq.pregunta = "";
            this.oFaq.respuesta = "";
            this.accion = "nuevo";
        },
    },
};
</script>

<style></style>
