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
          Registrar recerva
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
              v-bind:value="recerva.cancha"
              v-bind:placeholder="'c '+recerva.cancha"
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
              v-bind:value="recerva.horario"
              v-bind:placeholder="recerva.horario"
            />
          </b-form-group>
          <b-form-group
            label="Tiempo de juego"
            label-for="tiempo"
          >
            <b-button
              v-ripple.400="'rgba(255, 159, 67, 0.15)'"
              variant="outline-warning"
              pill
            >
              60°
            </b-button>

            <b-button
              v-ripple.400="'rgba(113, 102, 240, 0.15)'"
              variant="outline-primary"
              pill
            >
              90°
            </b-button>
            <b-button
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              variant="outline-success"
              pill
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
    recerva: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      required,
      alphaNum,
      email,
      countries,
      dataRecerva: [],
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

    const onSubmit = () => {
      store.dispatch('app-user/addUser', userData.value).then(() => {
        emit('refetch-data')
        emit('update:is-add-new-user-sidebar-active', false)
      })
    }

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetuserData)

    return {
      userData,
      onSubmit,

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
