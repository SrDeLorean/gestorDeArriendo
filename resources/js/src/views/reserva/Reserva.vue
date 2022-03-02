<template>
  <!-- TABLA CON HORARIOS !-->
  
  <b-card-code
    title="Gestionar reserva"
  >

    <user-list-add-new
      :is-add-new-user-sidebar-active.sync="isAddNewUserSidebarActive"
      :reserva="reserva"
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
            <b-td v-for="cancha in canchas" v-bind:key="cancha.id" v-if="ocupado(cancha.id, horario.id)===true" @click="actionTd(cancha.id, cancha.descripcion, horario.id, horario.descripcion)" variant="success">Libre</b-td>
            <b-td v-else-if="soyYo(cancha.id, horario.id)" @click="horarioNoDisponible(cancha.id, horario.descripcion)" v-b-modal.modal-primary variant="primary">Ocupado</b-td>
            <b-td v-else @click="horarioNoDisponible(cancha.id, horario.descripcion)" v-b-modal.modal-danger variant="danger">Ocupado</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <b-modal
      id="modal-danger"
      ok-only
      ok-variant="danger"
      ok-title="Cerrar"
      modal-class="modal-danger"
      centered
      title="Reserva ocupada"
    >
        Estimado cliente, esta cancha y hora se encuentran ocupadas por otro cliente, por favor intente reservar otra cancha o en otro horario.
    
    </b-modal>

    <b-modal
      id="modal-primary"
      ok-only
      ok-variant="primary"
      ok-title="Cerrar"
      modal-class="modal-primary"
      centered
      title="Reserva ocupada"
    >
        Estimado cliente, esta cancha y hora se encuentran reservadas por usted, si desea cancelar la reserva por favor diríjase a la vista de sus reservas.
    </b-modal>
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

import { BButton, BModal, VBModal } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'

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
    BButton,
    BModal,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
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
      dia: today.getFullYear() +'-'+today.getMonth()+1+'-'+today.getDate(),
      reserva: {
        idCancha: "",
        cancha: "",
        idHorario: "",
        horario: ""
      },
      canchas: [],
      horarios: [],
      listaRecervas: [],
      userData: JSON.parse(localStorage.getItem('userData')),
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
    actionTd($idCancha, $cancha, $idHorario, $horario){
      this.reserva.idCancha = $idCancha
      this.reserva.cancha = $cancha
      this.reserva.idHorario = $idHorario
      this.reserva.horario = $horario
      this.reserva.dia = this.dia
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
    soyYo($cancha, $horario){
      for (let element of this.listaRecervas) {
        if(element.idHorario == $horario){
          if(element.idCancha == $cancha){
            if(element.idUsuario == this.userData.id){
              return true
            }else{
              return false
            }
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
