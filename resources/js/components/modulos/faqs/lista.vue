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
                    <div class="col-md-12">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group row col-lg-10">
                                        <label
                                            for="staticEmail"
                                            class="col-sm-3 col-form-label text-right"
                                            >Tipo de soporte</label
                                        >
                                        <div class="col-sm-9">
                                            <el-select
                                                class="w-100"
                                                v-model="tipo_soporte_id"
                                                clearable
                                                @change="getFaqs()"
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
                                        </div>
                                    </div>
                                    <b-col lg="10" class="my-1">
                                        <b-form-group
                                            label="Buscar"
                                            label-for="filter-input"
                                            label-cols-sm="3"
                                            label-align-sm="right"
                                            class="mb-0"
                                        >
                                            <b-input-group>
                                                <b-form-input
                                                    id="filter-input"
                                                    v-model="search"
                                                    type="search"
                                                    placeholder="Buscar"
                                                ></b-form-input>

                                                <b-input-group-append>
                                                    <b-button
                                                        :disabled="!search"
                                                        @click="search = ''"
                                                        >Borrar</b-button
                                                    >
                                                </b-input-group-append>
                                            </b-input-group>
                                        </b-form-group>
                                    </b-col>
                                    <div class="col-md-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="col-md-12"
                        v-for="(item, index) in filtraRegistros"
                        :key="index"
                    >
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h4>{{ item.pregunta }}</h4>
                            </div>
                            <div class="card-body">
                                <p v-text="item.respuesta"></p>
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
            listTipoSoportes: [],
            tipo_soporte_id: "",
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
        };
    },
    computed: {
        filtraRegistros() {
            return this.listRegistros.filter((item) => {
                return (
                    item.pregunta
                        .toLowerCase()
                        .indexOf(this.search.toLowerCase()) != -1
                );
            });
        },
    },
    mounted() {
        this.getTipoSoportes();
        this.getFaqs();
        this.loadingWindow.close();
    },
    methods: {
        // OBTENER TIPOS DE SOPORTES
        getTipoSoportes() {
            axios.get("/admin/tipo_soportes").then((response) => {
                this.listTipoSoportes = response.data.tipo_soportes;
            });
        },
        getFaqs() {
            let url = "/admin/faqs/lista";
            axios
                .get(url, {
                    params: {
                        per_page: this.per_page,
                        tipo_soporte_id: this.tipo_soporte_id,
                    },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.faqs;
                    this.totalRows = res.data.total;
                });
        },
    },
};
</script>

<style></style>
