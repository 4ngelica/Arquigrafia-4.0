<template>
    <div class="container-login">
      <h1>Login</h1>
      <h2>Entre com seu login ou e-mail e em seguida digite sua senha:</h2>
      <form method="POST" class="my-3" @submit.prevent="login($event)">
        <input type="hidden" name="_token" :value="csrf_token">

        <div class="form-group my-4 d-flex flex-column justify-content-center">
          <div class="col-12  mx-1">
              <label for="email">Login ou E-mail:</label>
              <input type="text" class="form-control" name="email" id="email" v-model="formData.email" />
              <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
          </div>

          <div class="col-12 mx-1">
              <label for="password">Senha:</label>
              <input type="password" class="form-control" name="password" id="password" v-model="formData.password" />
              <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
          </div>

        <button type="submit" class="btn btn-primary my-4 mx-1">Login</button>
        <a href="#">Esqueceu sua senha?</a>
      </div>
    </form>

    <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
      Login institucional
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <img src="/img/logo.png" class="row" width="200px">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
              <form @submit.prevent="institutionalLogin($event)" class="my-3 d-flex flex-column justify-content-center">

                <div class="form-group my-4">
                  <div class="col-12 mx-1">
                      <label for="institution">Acervo:</label>
                      <select class="form-select" aria-label="Default select example" name="institution" id="institution" v-model="formData.institution">
                        <option value="1">Acervo da Biblioteca da FAUUSP</option>
                        <option value="2">Acervo Quapá</option>
                        <option value="3">Museu Republicano Convenção de Itu</option>
                        <option value="4">Equipe Arquigrafia</option>
                      </select>
                      <div v-if="errors && errors.institution" class="text-danger">{{ errors.institution[0] }}</div>
                  </div>

                  <div class="col-12 mx-1">
                      <label for="institutional_login">Login ou E-mail:</label>
                      <input type="text" class="form-control" name="institutional_login" id="institutional_login" v-model="institutionalFormData.institutional_login" />
                      <div v-if="errors && errors.institutional_login" class="text-danger">{{ errors.institutional_login[0] }}</div>
                  </div>

                  <div class="col-12 mx-1">
                      <label for="institutional_password">Senha:</label>
                      <input type="password" class="form-control" name="institutional_password" id="institutional_password" v-model="institutionalFormData.institutional_password" />
                      <div v-if="errors && errors.institutional_password" class="text-danger">{{ errors.institutional_password[0] }}</div>
                  </div>

                <button type="submit" class="btn btn-primary my-4 mx-1">Login</button>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>


export default {
  props: ['csrf_token'],
  data () {
    return {
      formData: {
        email: '',
        password: ''
      },
      institutionalFormData: {
        institutional_login: '',
        institutional_password: ''
      },
      errors: {}
    }
  },
  methods: {
    login(e) {
        this.errors = null

        let formData = new FormData()

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })
        //
        // window.axios.post('/users/login', formData, {
        // }).then(response => {
        //   this.showForm = false
        //   this.$router.push('/home')
        // }).catch(err => {
        //   console.log(err)
        //   if (err.response.status === 422) {
        //     this.errors = []
        //     _.each(err.response.data.errors, error => {
        //       _.each(error, e => {
        //         this.errors.push(e)
        //       })
        //     })
        //
        //   }
        // });

        axios.get('/sanctum/csrf-cookie').then(response => {
          window.axios.post('/api/login', formData, {
          }).then(response => response.json())
          .then(data => {
            if(data.token){
              document.cookie = 'token='+data.token+';SameSite=Lax'
            }

          }).catch(err => {
            console.log(err)
            if (err.response.status === 422) {
              this.errors = []
              _.each(err.response.data.errors, error => {
                _.each(error, e => {
                  this.errors.push(e)
                })
              })
            }
          });
        });



        e.target.submit()
      },
      handleFileObject() {
        this.photo = this.$refs.file.files[0]
        this.photoName = this.photo.name
      }
    },
  mounted () {
    // this.get();
    // this.getFollowers('?limit=8');
    // this.getFollowing('?limit=8');
  }
};

</script>
