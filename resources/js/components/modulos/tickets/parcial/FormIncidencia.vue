<template>
    <div class="col-md-12 ml-auto mr-auto mt-0">
        <div class="card card-primary card-outline bg-dark">
            <div class="card-header">
                <h3 class="card-title" v-html="tituloForm"></h3>
            </div>

            <div class="card-body">
                <template v-if="user.tipo == 'ADMINISTRADOR'">
                    <el-radio-group v-model="oTicket.tipo_incidencia">
                        <el-radio label="REGISTRO"></el-radio>
                        <el-radio label="SOLUCION"></el-radio>
                    </el-radio-group>
                </template>

                <div
                    class="form-group"
                    v-if="
                        user.tipo != 'PERSONAL' &&
                        oTicket != 'REGISTRO' &&
                        oTicket.tipo_incidencia == 'SOLUCION'
                    "
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
                        v-model="oTicket.estado"
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
                        v-model="oTicket.asunto"
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
                        >Descripción*</label
                    >
                    <el-input
                        type="textarea"
                        maxlength="2000"
                        show-word-limit
                        placeholder="Descripción"
                        :class="{
                            'is-invalid': errors.descripcion,
                        }"
                        v-model="oTicket.descripcion"
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
                        multiple
                        ref="upload"
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
                    <div class="text-danger text-center" v-if="error_upload">
                        {{ error_upload.msj }}
                    </div>
                    <template v-if="errors">
                        <template v-for="(item, index) in listArchivos">
                            <div
                                :key="index"
                                class="text-danger text-center"
                                v-if="errors[`listArchivos.${index}`]"
                                v-text="errors[`listArchivos.${index}`][0]"
                            ></div>
                        </template>
                    </template>
                </div>
            </div>

            <div class="card-footer">
                <div class="col-md-6 ml-auto mr-auto">
                    <button
                        type="button"
                        class="btn btn-primary bg-lightblue btn-flat btn-block"
                        v-loading="enviando"
                        @click="registrarIncidencia"
                    >
                        <i class="far fa-envelope"></i>
                        Enviar
                    </button>
                </div>
                <!-- <button type="reset" class="btn btn-default">
                                    <i class="fas fa-times"></i> Descartar
                                </button> -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["ticket"],
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            user: JSON.parse(localStorage.getItem("user")),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),

            listTipoSoportes: [],
            listEstados: ["PENDIENTE", "SOLUCIONADO"],
            errors: [],
            error_upload: null,
            oTicket: {
                estado: "PENDIENTE",
                prioridad: "",
                asunto: "",
                descripcion: "",
                tipo_incidencia: "REGISTRO",
            },
            listPrioridad: ["ALTO", "MEDIO", "BAJO"],
            listArchivos: [],
            enviando: false,
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
    computed: {
        tituloForm() {
            if (this.oTicket.tipo_incidencia == "REGISTRO") {
                return 'Nueva incidencia - <span class="badge badge-primary text-md">Registro</span>';
            }
            return 'Nueva incidencia - <span class="badge badge-info text-md">Solución</span>';
        },
    },
    mounted() {
        this.getTipoSoportes();
        this.loadingWindow.close();
        setTimeout(() => {
            this.oTicket.estado = this.ticket.estado;
            this.oTicket.tipo_incidencia =
                this.user.tipo == "TÉCNICO" ? "SOLUCION" : "REGISTRO";
        }, 500);
    },
    methods: {
        // MOSTRAR PREVIEW ARCHIVO
        handlePreview(file) {
            console.log(file);
        },
        // ACCION DESPUES DE ELIMINAR ACHIVO
        handleRemove(file, fileList) {
            this.listArchivos = fileList;
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
        // OBTENER TIPOS DE SOPORTES
        getTipoSoportes() {
            axios.get("/admin/tipo_soportes").then((response) => {
                this.listTipoSoportes = response.data.tipo_soportes;
            });
        },
        // REGISTRAR TICKET
        registrarIncidencia() {
            this.enviando = true;
            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            };
            let formData = new FormData();
            formData.append("estado", this.oTicket.estado);
            formData.append("asunto", this.oTicket.asunto);
            formData.append("ticket_id", this.ticket.id);
            formData.append("descripcion", this.oTicket.descripcion);
            formData.append("tipo_incidencia", this.oTicket.tipo_incidencia);
            if (this.listArchivos.length > 0) {
                this.listArchivos.forEach((element) => {
                    formData.append("listArchivos[]", element.raw);
                });
            }
            axios
                .post("/admin/solucion_tickets", formData, config)
                .then((response) => {
                    this.$emit("enviado");
                    this.$refs.upload.clearFiles();
                    Swal.fire({
                        icon: "success",
                        title: response.data.msj,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    this.enviando = false;
                    this.limpiarTicket();
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
            this.oTicket.estado = "";
            this.oTicket.asunto = "";
            this.oTicket.descripcion = "";
            this.fileList = [];
        },
    },
};
</script>

<style></style>
