<template>
    <div class="card">
        <div class="card-body p-0">
            <div class="mailbox-read-info">
                <h5>
                    {{ oTicket.asunto }}
                    <span
                        class="mailbox-read-time float-right"
                        v-html="getEstadoEnvio"
                    ></span>
                </h5>
                <span class="mailbox-read-time float-right">{{
                    formatoFecha(oTicket.created_at)
                }}</span>
                <h6>
                    Prioridad:
                    <span
                        class="badge"
                        :class="{
                            'badge-danger': oTicket.prioridad == 'ALTO',
                            'badge-warning': oTicket.prioridad == 'MEDIO',
                            'badge-primary': oTicket.prioridad == 'BAJO',
                        }"
                        v-text="oTicket.prioridad"
                    ></span>
                </h6>
                <h6>
                    Registrado por:
                    <span v-html="registradoPor"></span>
                </h6>
                <h6>
                    Estado:
                    <span
                        class="badge"
                        :class="{
                            'badge-success': oTicket.estado == 'SOLUCIONADO',
                            'badge-warning': oTicket.estado == 'PENDIENTE',
                        }"
                        v-text="oTicket.estado"
                    ></span>
                </h6>
            </div>
            <div class="mailbox-read-message">
                {{ oTicket.descripcion }}
            </div>
        </div>

        <div class="card-footer" v-if="oTicket.archivos.length > 0">
            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                <li v-for="item in oTicket.archivos" :key="item.id">
                    <span
                        class="mailbox-attachment-icon"
                        v-html="obtieneIcono(item)"
                    ></span>
                    <div class="mailbox-attachment-info">
                        <a
                            href="#"
                            @click.prevent="descargar(item)"
                            class="mailbox-attachment-name"
                            ><i class="fas fa-paperclip"></i>
                            {{ item.nombre_original }}</a
                        >
                        <span class="mailbox-attachment-size clearfix mt-1">
                            <span>{{ item.size }}</span>
                            <button
                                @click="descargar(item)"
                                class="btn btn-default btn-sm float-right"
                            >
                                <i class="fas fa-cloud-download-alt"></i>
                            </button>
                        </span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ["ticket"],
    data() {
        return {
            oTicket: this.ticket,
        };
    },
    watch: {
        ticket: function (newVal) {
            this.oTicket = newVal;
        },
    },
    computed: {
        registradoPor() {
            if (this.oTicket.id != 0) {
                if (this.oTicket.user.informacion_usuario) {
                    return this.oTicket.user.informacion_usuario.full_name;
                } else {
                    return this.oTicket.user.usuario;
                }
            } else {
                return "Cargando...";
            }
        },
        getEstadoEnvio() {
            if (this.oTicket.id != 0) {
                if (this.oTicket.envio == "ENVIADO") {
                    return `<span>Enviado</span> <i class="fa fa-check"></i>`;
                } else {
                    return `<span class="text-primary">Recibido</span> <i class="fa fa-check text-primary"></i>`;
                }
            }
            return "";
        },
    },
    methods: {
        obtieneIcono(archivo) {
            let array = archivo.archivo.split(".");
            if (array[1] == "docx" || array[1] == "doc") {
                return '<i class="far fa-file-word"></i>';
            }
            if (array[1] == "pdf") {
                return '<i class="far fa-file-pdf"></i>';
            }
            if (
                array[1] == "png" ||
                array[1] == "jpg" ||
                array[1] == "jpeg" ||
                array[1] == "webpdm" ||
                array[1] == "gif"
            ) {
                return `<img src="${archivo.url}" alt="Imagen">`;
            }
            return '<i class="fas fa-paperclip"></i>';
        },
        obtieneIconoDescarga(archivo) {
            let array = archivo.archivo.split(".");
            if (
                array[1] == "png" ||
                array[1] == "jpg" ||
                array[1] == "jpeg" ||
                array[1] == "webpdm" ||
                array[1] == "gif"
            ) {
                return '<i class="fas fa-camera"></i>';
            }
            return '<i class="fas fa-paperclip"></i>';
        },
        descargar(archivo) {
            axios
                .get(archivo.url_descarga, { responseType: "blob" })
                .then((response) => {
                    const blob = new Blob([response.data]);
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = archivo.archivo;
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(console.error);
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD [de] MMMM [del] YYYY");
        },
    },
};
</script>

<style></style>
