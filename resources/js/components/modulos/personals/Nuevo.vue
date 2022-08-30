<template>
    <div
        class="modal fade"
        :class="{ show: bModal }"
        id="modal-default"
        aria-modal="true"
        role="dialog"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click="cierraModal"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.nombre,
                                    }"
                                    >Nombre*</label
                                >
                                <el-input
                                    placeholder="Nombre"
                                    :class="{ 'is-invalid': errors.nombre }"
                                    v-model="usuario.nombre"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.nombre"
                                    v-text="errors.nombre[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.paterno,
                                    }"
                                    >Ap. Paterno*</label
                                >

                                <el-input
                                    placeholder="Ap. Paterno"
                                    :class="{ 'is-invalid': errors.paterno }"
                                    v-model="usuario.paterno"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.paterno"
                                    v-text="errors.paterno[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.materno,
                                    }"
                                    >Ap. Materno</label
                                >
                                <el-input
                                    placeholder="Ap. Materno"
                                    :class="{ 'is-invalid': errors.materno }"
                                    v-model="usuario.materno"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.materno"
                                    v-text="errors.materno[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.ci,
                                    }"
                                    >C.I.*</label
                                >
                                <el-input
                                    placeholder="Número de C.I."
                                    :class="{ 'is-invalid': errors.ci }"
                                    v-model="usuario.ci"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.ci"
                                    v-text="errors.ci[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.ci_exp,
                                    }"
                                    >Expedido*</label
                                >
                                <el-select
                                    class="w-100 d-block"
                                    :class="{
                                        'is-invalid': errors.ci_exp,
                                    }"
                                    v-model="usuario.ci_exp"
                                    clearable
                                >
                                    <el-option
                                        v-for="(item, index) in listExpedido"
                                        :key="index"
                                        :value="item.value"
                                        :label="item.label"
                                    >
                                    </el-option>
                                </el-select>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.ci_exp"
                                    v-text="errors.ci_exp[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.dir,
                                    }"
                                    >Dirección*</label
                                >
                                <el-input
                                    placeholder="Dirección"
                                    :class="{ 'is-invalid': errors.dir }"
                                    v-model="usuario.dir"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.dir"
                                    v-text="errors.dir[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.correo,
                                    }"
                                    >Correo*</label
                                >
                                <el-input
                                    placeholder="Correo"
                                    :class="{ 'is-invalid': errors.correo }"
                                    v-model="usuario.correo"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.correo"
                                    v-text="errors.correo[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.fono,
                                    }"
                                    >Teléfono*</label
                                >
                                <el-input
                                    placeholder="Teléfono"
                                    :class="{ 'is-invalid': errors.fono }"
                                    v-model="usuario.fono"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.fono"
                                    v-text="errors.fono[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.cel,
                                    }"
                                    >Celular*</label
                                >
                                <el-input
                                    placeholder="Celular"
                                    :class="{ 'is-invalid': errors.cel }"
                                    v-model="usuario.cel"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.cel"
                                    v-text="errors.cel[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.foto,
                                    }"
                                    >Foto</label
                                >
                                <input
                                    type="file"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.foto,
                                    }"
                                    @change="cargaImagen"
                                />
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.foto"
                                    v-text="errors.foto[0]"
                                ></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                        @click="cierraModal"
                    >
                        Cerrar
                    </button>
                    <el-button
                        type="primary"
                        class="bg-lightblue"
                        :loading="enviando"
                        @click="setRegistroModal()"
                        >{{ textoBoton }}</el-button
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        muestra_modal: {
            type: Boolean,
            default: false,
        },
        accion: {
            type: String,
            default: "nuevo",
        },
        usuario: {
            type: Object,
            default: {
                id: 0,
                nombre: "",
                paterno: "",
                materno: "",
                ci: "",
                ci_exp: "",
                dir: "",
                fono: "",
                cel: "",
                foto: null,
            },
        },
    },
    watch: {
        muestra_modal: function (newVal, oldVal) {
            this.errors = [];
            if (newVal) {
                this.bModal = true;
            } else {
                this.bModal = false;
            }
        },
    },
    computed: {
        tituloModal() {
            if (this.accion == "nuevo") {
                return "AGREGAR REGISTRO";
            } else {
                return "MODIFICAR REGISTRO";
            }
        },
        textoBoton() {
            if (this.accion == "nuevo") {
                return "Registrar";
            } else {
                return "Actualizar";
            }
        },
    },
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            bModal: this.muestra_modal,
            enviando: false,
            listExpedido: [
                { value: "LP", label: "La Paz" },
                { value: "CB", label: "Cochabamba" },
                { value: "SC", label: "Santa Cruz" },
                { value: "CH", label: "Chuquisaca" },
                { value: "OR", label: "Oruro" },
                { value: "PT", label: "Potosi" },
                { value: "TJ", label: "Tarija" },
                { value: "PD", label: "Pando" },
                { value: "BN", label: "Beni" },
            ],
            listRoles: ["ADMINISTRADOR", "TÉCNICO"],
            errors: [],
        };
    },
    mounted() {
        this.bModal = this.muestra_modal;
    },
    methods: {
        setRegistroModal() {
            this.enviando = true;
            try {
                this.textoBtn = "Enviando...";
                let url = "/admin/usuarios";
                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };
                let formdata = new FormData();
                formdata.append(
                    "nombre",
                    this.usuario.nombre ? this.usuario.nombre : ""
                );
                formdata.append(
                    "paterno",
                    this.usuario.paterno ? this.usuario.paterno : ""
                );
                formdata.append(
                    "materno",
                    this.usuario.materno ? this.usuario.materno : ""
                );
                formdata.append("ci", this.usuario.ci ? this.usuario.ci : "");
                formdata.append(
                    "ci_exp",
                    this.usuario.ci_exp ? this.usuario.ci_exp : ""
                );
                formdata.append(
                    "dir",
                    this.usuario.dir ? this.usuario.dir : ""
                );
                formdata.append(
                    "correo",
                    this.usuario.correo ? this.usuario.correo : ""
                );
                formdata.append(
                    "fono",
                    this.usuario.fono ? this.usuario.fono : ""
                );
                formdata.append(
                    "cel",
                    this.usuario.cel ? this.usuario.cel : ""
                );
                formdata.append(
                    "foto",
                    this.usuario.foto ? this.usuario.foto : ""
                );
                formdata.append("tipo", "PERSONAL");
                if (this.accion == "edit") {
                    url = "/admin/usuarios/" + this.usuario.id;
                    formdata.append("_method", "PUT");
                }
                // ENVIO REGISTRO USUARIO
                axios
                    .post(url, formdata, config)
                    .then((res) => {
                        // NUEVO
                        if (this.accion == "nuevo") {
                            // ENVIO REGISTRO PERSONAL
                            let nuevo_usuario = res.data.usuario;
                            axios
                                .post("admin/personals", {
                                    user_id: nuevo_usuario.id,
                                })
                                .then((res) => {
                                    this.enviando = false;
                                    Swal.fire({
                                        icon: "success",
                                        title: res.data.msj,
                                        showConfirmButton: false,
                                        timer: 1500,
                                    });
                                    this.limpiaUsuario();
                                    this.$emit("envioModal");
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
                                            this.errors =
                                                error.response.data.errors;
                                        }
                                    }
                                });
                        } else {
                            // ACTUALIZACIÓN
                            this.enviando = false;
                            Swal.fire({
                                icon: "success",
                                title: res.data.msj,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.limpiaUsuario();
                            this.$emit("envioModal");
                            this.errors = [];
                            if (this.accion == "edit") {
                                this.textoBtn = "Actualizar";
                            } else {
                                this.textoBtn = "Registrar";
                            }
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
        cargaImagen(e) {
            this.usuario.foto = e.target.files[0];
        },
        // Dialog/modal
        cierraModal() {
            this.bModal = false;
            this.$emit("close");
        },
        limpiaUsuario() {
            this.errors = [];
            this.usuario.nombre = "";
            this.usuario.paterno = "";
            this.usuario.materno = "";
            this.usuario.ci = "";
            this.usuario.ci_exp = "";
            this.usuario.dir = "";
            this.usuario.correo = "";
            this.usuario.fono = "";
            this.usuario.cel = "";
            this.usuario.foto = null;
        },
    },
};
</script>

<style></style>
