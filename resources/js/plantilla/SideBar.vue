<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-white bg-lightblue elevation-4">
        <!-- Brand Logo -->
        <router-link
            exact
            :to="{ name: 'inicio' }"
            class="brand-link bg-lightblue"
        >
            <img
                :src="configuracion.path_image"
                alt="Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8"
            />
            <span
                class="brand-text font-weight-light"
                v-text="configuracion.nombre_sistema"
            ></span>
        </router-link>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="user.path_image"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <router-link
                        exact
                        :to="{
                            name: 'usuarios.perfil',
                            params: { id: user.id },
                        }"
                        class="d-block"
                        v-text="user.full_name"
                    ></router-link>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input
                        class="form-control form-control-sidebar bg-white"
                        type="search"
                        placeholder="Buscar Modulo"
                        aria-label="Search"
                    />
                    <div class="input-group-append">
                        <button class="btn btn-sidebar bg-white">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column text-sm nav-flat"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <router-link
                            exact
                            :to="{ name: 'inicio' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-home"></i>
                            <p>Inicio</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-header"
                        v-if="
                            permisos.includes('proyectos.index') ||
                            permisos.includes('anticipos.index')
                        "
                    >
                        PROYECTOS
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('proyectos.index')"
                    >
                        <router-link
                            exact
                            :to="{ name: 'proyectos.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Proyectos</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('anticipos.index')"
                    >
                        <router-link
                            :to="{ name: 'anticipos.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Solicitud de Anticipos</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-header"
                        v-if="
                            permisos.includes('usuarios.index') ||
                            permisos.includes('personals.index') ||
                            permisos.includes('tipo_soportes.index') ||
                            permisos.includes('tickets.index') ||
                            permisos.includes('faqs.index') ||
                            permisos.includes('faqs.index') ||
                            permisos.includes('configuracion.index')
                        "
                    >
                        ADMINISTRACIÓN
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('usuarios.index')"
                    >
                        <router-link
                            exact
                            :to="{ name: 'usuarios.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('personals.index')"
                    >
                        <router-link
                            exact
                            :to="{ name: 'personals.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-users"></i>
                            <p>Personal</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('tipo_soportes.index')"
                    >
                        <router-link
                            :to="{ name: 'tipo_soportes.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-list-alt"></i>
                            <p>Tipos de soporte</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('tickets.index')"
                    >
                        <router-link
                            :to="{ name: 'tickets.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon el-icon-notebook-2"></i>
                            <p>Tickets</p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="permisos.includes('faqs.index')">
                        <router-link
                            :to="{ name: 'faqs.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-list"></i>
                            <p>FAQ's</p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="permisos.includes('faqs.lista')">
                        <router-link
                            :to="{ name: 'faqs.lista' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-list"></i>
                            <p>FAQ's</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('configuracion.index')"
                    >
                        <router-link
                            :to="{ name: 'configuracion' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Configuración</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-header"
                        v-if="
                            permisos.includes('reportes.usuarios') ||
                            permisos.includes('reportes.registros') ||
                            permisos.includes('reportes.soluciones') ||
                            permisos.includes('reportes.historial_tickets') ||
                            permisos.includes('reportes.atencion_tickets') ||
                            permisos.includes('reportes.estado_tickets')
                        "
                    >
                        REPORTES
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.usuarios')"
                    >
                        <router-link
                            :to="{ name: 'reportes.usuarios' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Lista de Usuarios</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.registros')"
                    >
                        <router-link
                            :to="{ name: 'reportes.registros' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Registro de incidencias</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.soluciones')"
                    >
                        <router-link
                            :to="{ name: 'reportes.soluciones' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Solución de incidencias</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.historial_tickets')"
                    >
                        <router-link
                            :to="{ name: 'reportes.historial_tickets' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Historial de tickets</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.atencion_tickets')"
                    >
                        <router-link
                            :to="{ name: 'reportes.atencion_tickets' }"
                            class="nav-link"
                        >
                            <i class="el-icon-data-analysis nav-icon"></i>
                            <p>Atención de tickets</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.estado_tickets')"
                    >
                        <router-link
                            :to="{ name: 'reportes.estado_tickets' }"
                            class="nav-link"
                        >
                            <i class="el-icon-data-analysis nav-icon"></i>
                            <p>Estado de tickets</p>
                        </router-link>
                    </li>
                    <li class="nav-header">PERFIL</li>
                    <li class="nav-item">
                        <router-link
                            exact
                            :to="{
                                name: 'usuarios.perfil',
                                params: { id: user.id },
                            }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-user"></i>
                            <p>Ver Perfil</p>
                        </router-link>
                    </li>
                    <li class="nav-header">SALIR</li>
                    <li class="nav-item">
                        <a
                            href="#"
                            class="nav-link"
                            @click.prevent="logout()"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="fas fa-power-off nav-icon"></i>
                            <p>Cerrar sesión</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
export default {
    props: ["user", "configuracion"],
    data() {
        return {
            fullscreenLoading: false,
            permisos: localStorage.getItem("permisos"),
        };
    },
    methods: {
        logout() {
            this.fullscreenLoading = true;
            axios.post("/logout").then((res) => {
                setTimeout(function () {
                    localStorage.clear();
                    location.reload();
                    this.$router.push({ name: "login" });
                }, 500);
            });
        },
    },
};
</script>

<style>
.user-panel .info {
    display: flex;
    height: 100%;
    align-items: center;
}
.user-panel .info a {
    font-size: 0.8em;
}
</style>
