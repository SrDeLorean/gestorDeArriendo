<template>
  <div>

    <!-- Alert: No item found -->
    <b-alert
      variant="danger"
      :show="userData === undefined"
    >
      <h4 class="alert-heading">
        Error fetching user data
      </h4>
      <div class="alert-body">
        No user found with this user id. Check
        <b-link
          class="alert-link"
          :to="{ name: 'apps-users-list'}"
        >
          User List
        </b-link>
        for other users.
      </div>
    </b-alert>

    <template v-if="userData">
      <!-- First Row -->
      <b-row>
        <b-col
          cols="12"
          xl="9"
          lg="8"
          md="7"
        >
          <user-view-user-info-card :user-data="userData" />
        </b-col>
      </b-row>

      <b-card-code
        title="Comprobantes"
        no-body
      >
        <b-table
          :fields="fields"
          :items="comprobantes"
          responsive
        >

          <!-- Column: Id -->
        <template #cell(id)="data">
          <b-link
            :to="{ name: 'reserva-show', params: { id: data.item.id }}"
            class="font-weight-bold"
          >
            #{{ data.value }}
          </b-link>
        </template>

        </b-table>

        <template #code>
          {{ codeFormatterCallback }}
        </template>

        
      </b-card-code>

    </template>

  </div>
</template>

<script>
import store from '@/store'
import router from '@/router'
import axios from 'axios'
import Swal from 'sweetalert2'

import { ref, onUnmounted } from '@vue/composition-api'
import {
  BRow, BCol, BAlert, BLink,
} from 'bootstrap-vue'
import InvoiceList from '@/views/apps/invoice/invoice-list/InvoiceList.vue'
import userStoreModule from '../userStoreModule'
import UserViewUserInfoCard from './UserViewUserInfoCard.vue'
import UserViewUserPlanCard from './UserViewUserPlanCard.vue'
import UserViewUserTimelineCard from './UserViewUserTimelineCard.vue'
import UserViewUserPermissionsCard from './UserViewUserPermissionsCard.vue'
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import { BTable} from 'bootstrap-vue'
import { codeFormatterCallback } from './code'

export default {
  components: {
    BRow,
    BCol,
    BAlert,
    BLink,
    BCardCode,
    BTable,

    // Local Components
    UserViewUserInfoCard,
    UserViewUserPlanCard,
    UserViewUserTimelineCard,
    UserViewUserPermissionsCard,

    InvoiceList,
  },
  model: {
    prop: 'isAddNewUserSidebarActive',
    event: 'update:is-add-new-user-sidebar-active',
  },
  setup() {
    const userData = ref(null)
    const comprobantes = ref(null)

    const USER_APP_STORE_MODULE_NAME = 'app-user'

    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, userStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    var config = {
            headers: {
                Authorization: 'Bearer ' + JSON.parse(localStorage.getItem('accessToken'))
            }
        }
    var url = 'http://127.0.0.1:8000/api/user/';
    axios.get(url+router.currentRoute.params.id, config)
      .then(response => { 
        userData.value = response.data.userData 
        comprobantes.value = response.data.comprobantes 
        console.log(response)
      }).catch(error => {
      if (error.response.status === 404) {
        userData.value = undefined
        comprobantes.value = undefined 
      }
    })

    return {
      userData,
      comprobantes,
      fields: [
          { key: 'id', label: '#', sortable: true },
          { key: 'dia', label: 'Dia', sortable: true },
          { key: 'bloques', label: 'Bloques', sortable: true },
          { key: 'inicio', label: 'Inicio', sortable: true },
          { key: 'termino', label: 'Termino', sortable: true },
          { key: 'estado', label: 'Estado', sortable: true },
          { key: 'total', label: 'Total', sortable: true, formatter: val => `$${val}` },
          { key: 'created_at', label: 'Registrada', sortable: true },
      ],
      items: [
      ],
      codeFormatterCallback,

    }
  },

}
</script>

<style>

</style>
