<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tickets - Editar incidencia</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <router-link
                            :to="{ name: 'tickets.index' }"
                            class="btn btn-primary bg-lightblue btn-block mb-3"
                            >Lista de tickets</router-link
                        >
                        <navegacion></navegacion>
                    </div>

                    <div class="col-md-9">
                        <div class="card card-primary bg-dark">
                            <div class="card-header">
                                <h3 class="card-title">Modificar incidencia de ticket</h3>
                            </div>

                            <div class="card-body">
                                <div
                                    class="form-group"
                                    v-if="oSolucionTicket != 'REGISTRO'"
                                >
                                    <label
                                        :class="{
                                            'text-danger': errors.estado,
                                        }"
                                        >Estado del ticket*</label
                                    >
                                    <el-select
                                        class="w-100 d-block"
                                        :class="{
                                            'is-invalid': errors.estado,
                                        }"
                                        v-model="oSolucionTicket.estado"
                                        clearable
                                    >
                                        <el-option
                                            v-for="(item, index) in listEstados"
                                            :key="index"
                                            :value="item"
                                            :label="item"
                                        >
                                        </el-option>
                                    </el-select>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.estado"
                                        v-text="errors.estado[0]"
                                    ></span>
                                </div>
                                <div class="form-group">
                                    <label
                                        :class="{
                                            'text-danger': errors.asunto,
                                        }"
                                        >Asunto*</label
                                    >
                                    <el-input
                                        placeholder="Asunto"
                                        :class="{
                                            'is-invalid': errors.asunto,
                                        }"
                                        v-model="oSolucionTicket.asunto"
                                        @keypress.enter.native="enviarRegistro"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.asunto"
                                        v-text="errors.asunto[0]"
                                    ></span>
                                </div>
                                <div class="form-group">
                                    <label
                                        :class="{
                                            'text-danger': errors.descripcion,
                                        }"
                                        >Descripción del problema*</label
                                    >
                                    <el-input
                                        type="textarea"
                                        maxlength="2000"
                                        show-word-limit
                                        placeholder="Descripción"
                                        :class="{
                                            'is-invalid': errors.descripcion,
                                        }"
                                        v-model="oSolucionTicket.descripcion"
                                        @keypress.enter.native="enviarRegistro"
                                        :autosize="{
                                            minRows: 3,
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
                                <div class="form-group">
                                    <el-upload
                                        class="upload-demo text-center"
                                        drag
                                        action="/admin/archivos"
                                        :headers="{ 'X-CSRF-TOKEN': csrf }"
                                        :on-preview="handlePreview"
                                        :on-remove="handleRemove"
                                        :on-success="archivoCargado"
                                        :on-error="errorUpload"
                                        :file-list="listArchivos"
                                        multiple
                                    >
                                        <i class="el-icon-upload"></i>
                                        <div class="el-upload__text">
                                            Suelta tu archivo aquí o
                                            <em>haz clic para cargar</em>
                                        </div>
                                        <!-- <div slot="tip" class="el-upload__tip">
                                            Tamaño maximo de los archivos 32MB
                                        </div> -->
                                    </el-upload>
                                    <div
                                        class="text-danger text-center"
                                        v-if="errors.listArchivos"
                                        v-text="errors.listArchivos[0]"
                                    ></div>
                                    <div
                                        class="text-danger text-center"
                                        v-if="error_upload"
                                    >
                                        {{ error_upload.msj }}
                                    </div>
                                    <template v-if="errors">
                                        <template
                                            v-for="(
                                                item, index
                                            ) in listArchivos"
                                        >
                                            <div
                                                :key="index"
                                                class="text-danger text-center"
                                                v-if="
                                                    errors[
                                                        `listArchivos.${index}`
                                                    ]
                                                "
                                                v-text="
                                                    errors[
                                                        `listArchivos.${index}`
                                                    ][0]
                                                "
                                            ></div>
                                        </template>
                                    </template>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button
                                            type="button"
                                            class="btn btn-primary bg-lightblue btn-flat btn-block"
                                            v-loading="enviando"
                                            @click="actualizaTicket"
                                        >
                                            <i class="far fa-envelope"></i>
                                            Actualizar incidencia ticket
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <router-link
                                            class="btn btn-default btn-flat btn-block"
                                            v-loading="enviando"
                                            :to="{
                                                name: 'tickets.incidencias',
                                                params: {
                                                    id: oSolucionTicket.ticket_id,
                                                },
                                            }"
                                        >
                                            <i class="fa fa-arrow-left"></i>
                                            Volver
                                        </router-link>
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
import navegacion from "./parcial/Navegacion.vue";
export default {
    components: {
        navegacion,
    },
    props: ["id"],
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),

            listTipoSoportes: [],
            errors: [],
            error_upload: null,
            oSolucionTicket: {
                id: 0,
                ticket_id: 0,
                asunto: "",
                descripcion: "",
                tipo_incidencia: "",
                user: {},
                archivos: [],
            },
            listEstados: ["PENDIENTE", "SOLUCIONADO"],
            listArchivos: [],
            enviando: false,
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
    mounted() {
        this.getTicket();
        this.loadingWindow.close();
    },
    methods: {
        getTicket() {
            axios.get("/admin/solucion_tickets/" + this.id).then((response) => {
                this.oSolucionTicket = response.data;
                let self = this;
                this.oSolucionTicket.archivos.forEach((element) => {
                    self.listArchivos.push({
                        name: element.nombre_original,
                        url: element.url,
                        url_destroy: element.url_destroy,
                    });
                });
            });
        },
        // MOSTRAR PREVIEW ARCHIVO
        handlePreview(file) {
            console.log(file);
        },
        // ACCION DESPUES DE ELIMINAR ACHIVO
        handleRemove(file, fileList) {
            if (file.url_destroy) {
                axios
                    .post(file.url_destroy, {
                        _method: "DELETE",
                    })
                    .then((res) => {
                        this.listArchivos = fileList;
                    });
            } else {
                this.listArchivos = fileList;
            }
        },
        // DESPUES DE CARGAR EL ARCHIVO
        archivoCargado(response, file, fileList) {
            this.error_upload = null;
            this.listArchivos = fileList;
        },
        errorUpload(err, file, fileList) {
            this.error_upload = { msj: "" };
            if (err.status == 422) {
                this.error_upload.msj = JSON.parse(err.message).errors.msj;
            } else {
                this.error_upload.msj =
                    "Ocurrió un error en el sistema, si el problema persiste intente mas tarde.";
            }
        },
        // REGISTRAR TICKET
        actualizaTicket() {
            this.enviando = true;
            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };
            let formData = new FormData();
            formData.append("asunto", this.oSolucionTicket.asunto);
            formData.append("descripcion", this.oSolucionTicket.descripcion);
            formData.append("_method", "PUT");
            if (this.listArchivos.length > 0) {
                this.listArchivos.forEach((element) => {
                    if (!element.url_destroy) {
                        formData.append("listArchivos[]", element.raw);
                    }
                });
            }
            axios
                .post("/admin/solucion_tickets/" + this.id, formData, config)
                .then((response) => {
                    Swal.fire({
                        icon: "success",
                        title: response.data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    this.enviando = false;
                    this.limpiarTicket();
                    setTimeout(() => {
                        this.$router.push({
                            name: "tickets.incidencias",
                            params: {
                                id: this.oSolucionTicket.ticket_id,
                            },
                        });
                    }, 1000);
                })
                .catch((error) => {
                    this.enviando = false;
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    }
                });
        },
        limpiarTicket() {
            this.oSolucionTicket.tipo_soporte_id = "";
            this.oSolucionTicket.prioridad = "";
            this.oSolucionTicket.asunto = "";
            this.oSolucionTicket.descripcion = "";
            this.fileList = [];
        },
    },
};
</script>

<style></style>
