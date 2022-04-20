<template>
  <b-card>

    <b-row>

      <!-- User Info: Left col -->
      <b-col
        cols="21"
        xl="6"
        class="d-flex justify-content-between flex-column"
      >
        <!-- User Avatar & Action Buttons -->
        <div class="d-flex justify-content-start">
          <b-avatar
            :src="userData.avatar"
            :text="avatarText(userData.fullname)"
            :variant="`light-${resolveUserRoleVariant(userData.role)}`"
            size="104px"
            rounded
          />
          <div class="d-flex flex-column ml-1">
            <div class="mb-1">
              <h4 class="mb-0">
                {{ userData.fullname }}
              </h4>
              <span class="card-text">{{ userData.email }}</span>
            </div>
            <div class="d-flex flex-wrap">
              <b-button
                :to="{ name: 'user-edit', params: { id: userData.id } }"
                variant="primary"
              >
                Editar
              </b-button>
              <b-button v-if="userData.deleted_at==null"
                variant="outline-danger"
                class="ml-1"
                @click="eliminar(userData.id)"
              >
                Eliminar
              </b-button>
              <b-button v-else
                variant="outline-success"
                class="ml-1"
                @click="habilitar(userData.id)"
              >
                Habilitar
              </b-button>
            </div>
          </div>
        </div>


      </b-col>

      <!-- Right Col: Table -->
      <b-col
        cols="12"
        xl="6"
      >
        <table class="mt-2 mt-xl-0 w-100">
          <tr>
            <th class="pb-50">
              <feather-icon
                icon="UserIcon"
                class="mr-75"
              />
              <span class="font-weight-bold">Username</span>
            </th>
            <td class="pb-50">
              {{ userData.email }}
            </td>
          </tr>
          <tr>
            <th class="pb-50">
              <feather-icon
                icon="CheckIcon"
                class="mr-75"
              />
              <span class="font-weight-bold">Status</span>
            </th>
            <td v-if="userData.deleted_at==null" class="pb-50 text-capitalize">
              Activo
            </td>
            <td v-else class="pb-50 text-capitalize">
              Eliminado
            </td>
          </tr>
          <tr>
            <th class="pb-50">
              <feather-icon
                icon="StarIcon"
                class="mr-75"
              />
              <span class="font-weight-bold">Role</span>
            </th>
            <td class="pb-50 text-capitalize">
              {{ userData.role }}
            </td>
          </tr>

            
        </table>
      </b-col>
    </b-row>
  </b-card>
</template>

<script>
import {
  BCard, BButton, BAvatar, BRow, BCol,
} from 'bootstrap-vue'
import { avatarText } from '@core/utils/filter'
import useUsersList from '../users-list/useUsersList'

import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  components: {
    BCard, BButton, BRow, BCol, BAvatar,
  },
  props: {
    userData: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const { resolveUserRoleVariant } = useUsersList()
    return {
      avatarText,
      resolveUserRoleVariant,
    }
  },
  methods:{
    eliminar(id){
        var config = {
            headers: {
                Authorization: 'Bearer ' + JSON.parse(localStorage.getItem('accessToken'))
            }
        }
        var url = 'http://127.0.0.1:8000/api/user/'+id;
        axios.delete(url, config)
          .then(response => { 
          console.log(response)
          Swal.fire({
            title: "Deshabilitar usuario",
            text: "Se a deshabilitado el usuario con exito",
            icon: "success",
            confirmButtonText: "Ok",
          });
        }).catch(error => {
          Swal.fire({
            icon: "Error",
            title: "Error al deshabilitar usuario",
            text: error.response.data.message,
          });
        })
    },
    habilitar(id){
      var config = {
            headers: {
                Authorization: 'Bearer ' + JSON.parse(localStorage.getItem('accessToken'))
            }
        }
        var url = 'http://127.0.0.1:8000/api/user/restore/'+id;
        axios.post(url,"" ,config)
          .then(response => { 
          Swal.fire({
            title: "Habilitar usuario",
            text: "Se a restaurado al usuario",
            icon: "success",
            confirmButtonText: "Ok",
          });
        }).catch(error => {
          Swal.fire({
            icon: "Error",
            title: "Error al restaurar usuario",
            text: error.response.data.message,
          });
        })
    }
  }
}
</script>

<style>

</style>
