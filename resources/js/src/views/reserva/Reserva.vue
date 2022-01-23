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
      v-model="dia"
      class="mb-1"
    />
    <p>dia: '{{ dia }}'</p>
  </div>

    <label for="table-simple">2. Seleccione el horario que desea jugar: </label>
    <b-table-simple
      id="table"
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
            {{cancha.descripcion}}
          </b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="horario in horarios" v-bind:key="horario.id">
          <b-th v-if="horario.descripcion.split(':')[1]==='00'" rowspan="2">
            {{horario.descripcion}}
          </b-th>
          <b-th class="text-right">
            {{horario.descripcion}}
          </b-th>
            <b-td v-for="cancha in canchas" v-bind:key="cancha.id" v-if="ocupado(cancha.id, horario.descripcion)===true" @click="actionTd(cancha.id, horario.descripcion)" variant="success">Libre</b-td>
            <b-td v-else @click="horarioNoDisponible(cancha.id, horario.descripcion)" variant="danger">Ocupado</b-td>
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
import axios from 'axios'

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
      dia: '',
      recerva: {
        cancha: "",
        horario: ""
      },
      canchas: [],
      horarios: []
      
    }
  },
  created() {
    this.obtenerCanchas()
    this.obtenerReservas()
    this.obtenerHorarios()
  },
  watch: {
    dia() {
      this.recargarReservas();
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
      console.log("ocupado")
      if($cancha==1 || $horario=="9:00"){
        return true
      }
      return false
    },
    horarioNoDisponible($cancha, $horario) {
      console.log("el horario de la cancha:", $cancha, " horario:", $horario, " se encuentra ocupado")
    },
    obtenerHorarios(){
      var url = 'http://127.0.0.1:8000/api/horario';
        axios.get(url)
          .then(response => { 
            console.log(response.data)
            this.horarios = response.data.horarios
          }).catch(error => {
          if (error.response.status === 404) {
            console.log(error)
          }
        })
    },
    obtenerCanchas(){
      var url = 'http://127.0.0.1:8000/api/cancha';
        axios.get(url)
          .then(response => { 
            console.log(response.data)
            this.canchas = response.data.canchas
          }).catch(error => {
          if (error.response.status === 404) {
            console.log(error)
          }
        })
    },
    obtenerReservas(){
      var url = 'http://127.0.0.1:8000/api/reservaPorDia';
        axios.post(url,{
          "dia": this.dia
        })
          .then(response => { 
            console.log(response.data)
          }).catch(error => {
          if (error.response.status === 404) {
            console.log(error)
          }
        })
    },
    recargarReservas(){
      console.log("holaaaaaaaa")
    },
  },
}
</script>
