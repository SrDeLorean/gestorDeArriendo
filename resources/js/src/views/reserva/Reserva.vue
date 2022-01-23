<template>
  <!-- TABLA CON HORARIOS !-->
  
  <b-card-code
    title="Gestionar recerva"
  >

    <user-list-add-new
      :is-add-new-user-sidebar-active.sync="isAddNewUserSidebarActive"
      :recerva="recerva"
    />

    <div>
    <label for="example-datepicker">1. Seleccione el dia en que decea jugar:</label>
    <b-form-datepicker
      id="example-datepicker"
      v-model="value"
      class="mb-1"
    />
    <p>Value: '{{ value }}'</p>
  </div>

    <label for="table-simple">2. Seleccione el horario que desea jugar: </label>
    <b-table-simple
      hover
      caption-top
      responsive
      class="rounded-bottom mb-0"
    >
      <b-thead head-variant="light">
        <b-tr>
          <b-th colspan="2">
            Horario
          </b-th>
          <b-th v-for="cancha in canchas" v-bind:key="cancha.id" colspan="1">
            {{cancha.nombre}}
          </b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="horario in horarios" v-bind:key="horario.id">
          <b-th v-if="horario.nombre.split(':')[1]==='00'" rowspan="2">
            {{horario.nombre}}
          </b-th>
          <b-th class="text-right">
            {{horario.nombre}}
          </b-th>
            <b-td v-for="cancha in canchas" v-bind:key="cancha.id" v-if="ocupado(cancha.id, horario.nombre)===true" @click="actionTd(cancha.id, horario.nombre)" variant="success">Libre</b-td>
            <b-td v-else @click="horarioNoDisponible(cancha.id, horario.nombre)" variant="danger">Ocupado</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <template #code>
      {{ codeSimple }}
    </template>
  </b-card-code>
</template>

<script>
import { BFormDatepicker } from 'bootstrap-vue'
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import {
  BTableSimple, BThead, BTr, BTh, BTd, BTbody, BTfoot,
} from 'bootstrap-vue'
import { codeSimple } from './code'

import UserListAddNew from './UserListAddNew.vue'

    const isAddNewUserSidebarActive = false


export default {
  components: {
    UserListAddNew,
    BCardCode,
    BTableSimple,
    BThead,
    BTr,
    BTh,
    BTd,
    BTbody,
    BTfoot,
    BFormDatepicker,
  },
  data() {
    return {
      isAddNewUserSidebarActive,
      codeSimple,
      value: '',
      recerva: {
        cancha: "",
        horario: ""
      },
      canchas: [
        {
          id: 1,
          nombre: 'cancha 1'
        },
        {
          id: 2,
          nombre: 'cancha 2'
        },
        {
          id: 3,
          nombre: 'cancha 3'
        },
        {
          id: 4,
          nombre: 'cancha 4'
        }
      ],
      horarios: [
        {
          id: 1,
          nombre: '8:00'
        },
        {
          id: 2,
          nombre: '8:30'
        },
        {
          id: 3,
          nombre: '9:00'
        },
        {
          id: 4,
          nombre: '9:30'
        }
      ]
      
    }
  },
  methods: {
    actionTd($cancha, $horario){
      this.recerva.cancha = $cancha
      this.recerva.horario = $horario
      console.log(this.recerva)
      this.isAddNewUserSidebarActive=true
    },
    ocupado($cancha, $horario) {
        if($cancha==1 || $horario=="9:00"){
          return true
        }
        return false
      },
      horarioNoDisponible($cancha, $horario) {
        console.log("el horario de la cancha:", $cancha, " horario:", $horario, " se encuentra ocupado")
      }
  },
}
</script>
