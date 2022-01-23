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
      :min="min"
      :max="max"
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
            <b-td v-for="cancha in canchas" v-bind:key="cancha.id" v-if="ocupado(cancha.id, horario.id)===true" @click="actionTd(cancha.descripcion, horario.descripcion)" variant="success">Libre</b-td>
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
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import {
  BFormDatepicker, BTableSimple, BThead, BTr, BTh, BTd, BTbody, BTfoot,
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
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    // 15th two months prior
    const minDate = new Date(today)
    minDate.setMonth(minDate.getMonth())
    minDate.setDate(minDate.getDate())
    // 15th in two months
    const maxDate = new Date(today)
    maxDate.setMonth(maxDate.getMonth() + 2)
    maxDate.setDate(minDate.getDate())
    return {
      min: minDate,
      max: maxDate,
      isAddNewUserSidebarActive,
      codeSimple,
      dia: "2022-02-16",
      recerva: {
        cancha: "",
        horario: ""
      },
      canchas: [],
      horarios: [],
      listaRecervas: []
      
    }
  },
  created() {
    this.obtenerCanchas()
    this.obtenerReservas()
    this.obtenerHorarios()
  },
  watch: {
    dia() {
      this.obtenerReservas();
    }
  },
  methods: {
    actionTd($cancha, $horario){
      this.recerva.cancha = $cancha
      this.recerva.horario = $horario
      this.isAddNewUserSidebarActive=true
    },
    ocupado($cancha, $horario) {
      for (let element of this.listaRecervas) {
        if(element.idHorario == $horario){
          if(element.idCancha == $cancha){
            return false
          }
        }
      }
      return true
     
    },
    horarioNoDisponible($cancha, $horario) {
      console.log("el horario de la cancha:", $cancha, " horario:", $horario, " se encuentra ocupado")
    },
    obtenerHorarios(){
      var url = 'http://127.0.0.1:8000/api/horario';
        axios.get(url)
          .then(response => { 
            this.horarios = []
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
            this.canchas = []
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
            this.listaRecervas = []
            this.listaRecervas = response.data.reservas
          }).catch(error => {
          if (error.response.status === 404) {
            console.log(error)
          }
        })
    }
  },
}
</script>
