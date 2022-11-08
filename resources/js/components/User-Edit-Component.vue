<template>
    <div class="container edit-profile">
      <h1>Edição de perfil do usuário</h1>
      <img v-if="user.photo" :src="user.photo" alt="" width="80" height="80">
      <img v-else src="/img/avatar-48.png" alt="" width="80" height="80">

      <form @submit.prevent="submit" class="my-3">

        <div class="form-group">
            <label for="customFile">Alterar foto</label>
            <input type="file" class="custom-file-input" id="customFile" ref="file" @change="handleFileObject()">
            <div v-if="errors && errors.photo" class="text-danger">{{ errors.photo[0] }}</div>
        </div>

        Campos obrigatórios (*)

        <div class="form-group d-flex flex-wrap flex-md-nowrap justify-content-between my-4">
          <div class="col-12 col-md-4 mx-1">
              <label for="name">Nome *</label>
              <input type="text" class="form-control" name="name" id="name" v-model="formData.name" />
              <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="lastName">Sobrenome *</label>
              <input type="text" class="form-control" name="lastName" id="lastName" v-model="formData.lastName" />
              <div v-if="errors && errors.lastName" class="text-danger">{{ errors.lastName[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="login">Login *</label>
              <input type="text" class="form-control" name="login" id="login" v-model="formData.login" />
              <div v-if="errors && errors.login" class="text-danger">{{ errors.login[0] }}</div>
          </div>
        </div>

        <div class="form-group d-flex flex-wrap flex-md-nowrap justify-content-between">
          <div class="col-12 col-md-4 mx-1">
              <label for="email">Email *</label>
              <input type="email" class="form-control" name="email" id="email" value="email" v-model="formData.email" />
              <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>

              <label for="visibleEmail">Visível no perfil público</label>
              <input type="checkbox" class="form-check-input" name="visibleEmail" id="visibleEmail" :value="formData.visibleEmail" v-model="formData.visibleEmail" />
              <div v-if="errors && errors.visibleEmail" class="text-danger">{{ errors.visibleEmail[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="birthday">Data de nascimento</label>
              <input type="text" class="form-control" name="birthday" id="birthday" value="birthday" v-model="formData.birthday" />
              <div v-if="errors && errors.birthday" class="text-danger">{{ errors.birthday[0] }}</div>

              <label for="visibleBirthday">Visível no perfil público</label>
              <input type="checkbox" class="form-check-input" name="visibleBirthday" id="visibleBirthday" :value="formData.visibleBirthday" v-model="formData.visibleBirthday" />
              <div v-if="errors && errors.visibleBirthday" class="text-danger">{{ errors.visibleBirthday[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="gender">Gênero *</label>
              <br>
              <input type="radio" class="form-check-input" id="female" value="female" v-model="formData.gender" />
              <label for="female">Feminino</label>
              <input type="radio" class="form-check-input" id="male" value="male" v-model="formData.gender" />
              <label for="male">Masculino</label>
          </div>
        </div>

        <div class="form-group d-flex flex-wrap flex-md-nowrap justify-content-between">
          <div class="col-12 col-md-4 mx-1">
              <label for="country">País *</label>
              <input type="text" class="form-control" name="country" id="country" v-model="formData.country" />
              <div v-if="errors && errors.country" class="text-danger">{{ errors.country[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="state">Estado *</label>
              <input type="text" class="form-control" name="state" id="state" v-model="formData.state" />
              <div v-if="errors && errors.state" class="text-danger">{{ errors.state[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="city">Cidade *</label>
              <input type="text" class="form-control" name="city" id="city" v-model="formData.city" />
              <div v-if="errors && errors.city" class="text-danger">{{ errors.city[0] }}</div>
          </div>
        </div>

        <div class="form-group d-flex flex-wrap flex-md-nowrap justify-content-between">
          <div class="col-12 col-md-4 mx-1">
              <label for="scholarity">Escolaridade *</label>
              <input type="text" class="form-control" name="scholarity" id="scholarity" v-model="formData.scholarity" />
              <div v-if="errors && errors.scholarity" class="text-danger">{{ errors.scholarity[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="institution">Instituição *</label>
              <input type="text" class="form-control" name="institution" id="institution" v-model="formData.institution" />
              <div v-if="errors && errors.institution" class="text-danger">{{ errors.institution[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="occupation">Profissão *</label>
              <input type="text" class="form-control" name="occupation" id="occupation" v-model="formData.occupation" />
              <div v-if="errors && errors.occupation" class="text-danger">{{ errors.occupation[0] }}</div>
          </div>
        </div>

        <div class="form-group d-flex flex-wrap flex-md-nowrap justify-content-between">
          <div class="col-12 col-md-4 mx-1">
              <label for="site">Site pessoal *</label>
              <input type="text" class="form-control" name="site" id="site" v-model="formData.site" />
              <div v-if="errors && errors.site" class="text-danger">{{ errors.site[0] }}</div>
          </div>
        </div>

        <div class="form-group d-flex flex-wrap flex-md-nowrap justify-content-between">
          <div class="col-12 col-md-4 mx-1">
              <label for="old_password">Senha atual*</label>
              <input type="password" class="form-control" name="old_password" id="old_password" v-model="formData.old_password" />
              <div v-if="errors && errors.old_password" class="text-danger">{{ errors.old_password[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="user_password">Nova senha *</label>
              <input type="password" class="form-control" name="user_password" id="user_password" v-model="formData.user_password" />
              <div v-if="errors && errors.user_password" class="text-danger">{{ errors.user_password[0] }}</div>
          </div>

          <div class="col-12 col-md-4 mx-1">
              <label for="user_password_confirmation">Confirmar nova senha *</label>
              <input type="password" class="form-control" name="user_password_confirmation" id="user_password_confirmation" v-model="formData.user_password_confirmation" />
              <div v-if="errors && errors.user_password_confirmation" class="text-danger">{{ errors.user_password_confirmation[0] }}</div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary my-4 mx-1">Editar</button>

        <div v-if="success" class="alert alert-success mt-3">
            Perfil editado com sucesso!
        </div>
    </form>
    </div>
</template>

<script>

import carousel from 'vue-owl-carousel'

const count = 20;

export default {
  props: ['user', 'auth'],
  components: { carousel },
  data () {
    return {
      formData: {
        name: this.$props.user.name ?? '',
        login: this.$props.user.login ?? '',
        email: this.$props.user.email ?? '',
        scholarity: this.$props.user.scholarity ?? '',
        lastName: this.$props.user.lastName ?? '',
        site: this.$props.user.site ?? '',
        birthday: this.$props.user.birthday ?? '',
        country: this.$props.user.country ?? '',
        state: this.$props.user.state ?? '',
        city: this.$props.user.city ?? '',
        photo: '',
        gender: this.$props.user.gender ?? '',
        institution: this.$props.user.institution ?? '',
        occupation: this.$props.user.occupation ?? '',
        visibleBirthday: this.$props.user.visibleBirthday ?? '',
        visibleEmail: this.$props.user.visibleEmail ?? '',
        old_password: '',
        user_password: '',
        user_password_confirmation: '',
      },
      errors: {},
      success: false,
      loaded: true,
      photo: '',
    }
  },
  methods: {
    submit() {
        this.errors = null

        let formData = new FormData()
        formData.append('photo', this.photo)
        formData.append("_method", "put");

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })

        console.log(formData);

        window.axios.post('/api/users/' + this.$props.user._id, formData, {
            headers: {
              'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
            }
          }
        ).then(response => {
          this.showForm = false
          this.user = response.data.data
        }).catch(err => {
          if (err.response.status === 422) {
            this.errors = []
            _.each(err.response.data.errors, error => {
              _.each(error, e => {
                this.errors.push(e)
              })
            })

          }
        });
      },
      handleFileObject() {
        this.photo = this.$refs.file.files[0]
      }
    },
  mounted () {

  }
};

</script>
