<template>
  <b-sidebar
    id="add-new-user-sidebar"
    :visible="isAddNewUserSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-user-sidebar-active', val)"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
        <h5 class="mb-0">
          Registrar reserva
        </h5>

        <feather-icon
          class="ml-1 cursor-pointer"
          icon="XIcon"
          size="16"
          @click="hide"
        />

      </div>

      <!-- BODY -->
      <validation-observer
        #default="{ handleSubmit }"
        ref="refFormObserver"
      >
        <!-- Form -->
        <b-form
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
        >

        <b-form-group
            label="Cancha"
            label-for="cancha"
          >
            <b-form-input
              id="cancha"
              v-model="dataRecerva.cancha"
              autofocus
              trim
              disabled 
              v-bind:value="reserva.cancha"
              v-bind:placeholder="reserva.cancha"
            />

          </b-form-group>

        <b-form-group
            label="Horario"
            label-for="horario"
          >
            <b-form-input
              id="horario"
              v-model="dataRecerva.horario"
              autofocus
              trim
              disabled 
              v-bind:value="reserva.horario"
              v-bind:placeholder="reserva.horario"
            />
          </b-form-group>
          <b-form-group
            label="Tiempo de juego"
            label-for="bloque"
          >
            <b-button
              v-ripple.400="'rgba(255, 159, 67, 0.15)'"
              variant="outline-warning"
              pill
              :disabled='disponible60'
              :pressed.sync="boton60"
              @click="clicBoton(1)"
            >
              60°
            </b-button>

            <b-button
              v-ripple.400="'rgba(113, 102, 240, 0.15)'"
              variant="outline-primary"
              pill
              :disabled='disponible90'
              :pressed.sync="boton90"
              @click="clicBoton(2)"
            >
              90°
            </b-button>
            <b-button
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="outline-success"
              pill
              :disabled='disponible120'
              :pressed.sync="boton120"
              @click="clicBoton(3)"
            >
              120°
            </b-button>
           </b-form-group>

          <!-- Form Actions -->
          <div class="d-flex mt-2">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mr-2"
              type="submit"
              :disabled='accion'
            >
              Registrar
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="button"
              variant="outline-secondary"
              @click="hide"
            >
              Cancelar
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </template>
  </b-sidebar>
</template>

<script>
import { BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref } from '@vue/composition-api'
import { required, alphaNum, email } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import countries from '@/@fake-db/data/other/countries'
import store from '@/store'
import axios from 'axios'

export default {
  components: {
    BButton,
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    vSelect,


    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  methods: {
    obtenerReservaDisponible(){
      console.log(this.reserva.idCancha, this.reserva.idHorario, this.reserva.dia);
      var url = 'http://127.0.0.1:8000/api/reservaDisponible';
        axios.post(url, {
          "idCancha": this.reserva.idCancha,
          "idHorario": this.reserva.idHorario,
          "dia": this.reserva.dia
        })
          .then(response => { 
            if(response.data.reservas.r60.length==1){
              this.disponible60 = true
              this.disponible90 = true
              this.disponible120 = true
              this.accion = true
              return
            }else{
              this.disponible60 = false
              this.accion = false
            }
            if(response.data.reservas.r90.length==1){
              this.disponible90 = true
              this.disponible120 = true
              return
            }else{
              this.disponible90 = false
            }
            if(response.data.reservas.r120.length==1){
              this.disponible120 = true
            }else{
              this.disponible120 = false
            }
          }).catch(error => {
            console.log(error)
          
        })
    },
    clicBoton($id){
      if($id==1){
        this.boton60= true,
        this.boton90= false,
        this.boton120= false, 
        this.bloque=2
      }else if($id==2){
        this.boton60= false,
        this.boton90= true,
        this.boton120= false, 
        this.bloque=3
      }else{
        this.boton60= false,
        this.boton90= false,
        this.boton120= true,
        this.bloque=4
      }
    },
    onSubmit(){
      var url = 'http://127.0.0.1:8000/api/reserva';
        axios.post(url, {
          "idCancha": this.reserva.idCancha,
          "idHorario": this.reserva.idHorario,
          "dia": this.reserva.dia,
          "bloque": this.bloque,
          "idUsuario": this.userData.id
        })
          .then(response => { 
            console.log(response)
          }).catch(error => {
            console.log(error)
          
        })
    }
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isAddNewUserSidebarActive',
    event: 'update:is-add-new-user-sidebar-active',
  },
  props: {
    isAddNewUserSidebarActive: {
      type: Boolean,
      required: true,
    },
    reserva: {
      type: Object,
      required: true,
    },
  },
  watch:{
    'isAddNewUserSidebarActive'(){
      if(this.isAddNewUserSidebarActive){
        this.obtenerReservaDisponible();
      }
    }
        },
  data() {
    return {
      required,
      alphaNum,
      email,
      countries,
      dataRecerva: [],
      disponible60: false,
      disponible90: false,
      disponible120: false,
      accion: false,
      bloque: 1,
      boton60: false,
      boton90: false,
      boton120: false,
      bloque: "",
      userData: JSON.parse(localStorage.getItem('userData'))
    }
  },
  setup(props, { emit }) {
    const blankUserData = {
      fullName: '',
      username: '',
      email: '',
      role: null,
      currentPlan: null,
      company: '',
      country: '',
      contact: '',
    }

    const userData = ref(JSON.parse(JSON.stringify(blankUserData)))
    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(blankUserData))
    }

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetuserData)

    return {
      userData,

      refFormObserver,
      getValidationState,
      resetForm,
    }
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';

#add-new-user-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }
}
</style>
