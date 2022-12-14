<template>
  <div class="container">
    <h1>Sugestões ({{suggestions.length}})</h1>
    <div v-if="suggestions.length > 0" class="table table-responsive">
      <thead>
        <tr>
          <th scope="col">Foto</th>
          <th scope="col">Nome da Foto</th>
          <th scope="col">Campo</th>
          <th scope="col">Dado Atual</th>
          <th scope="col">Sugestão</th>
          <th scope="col">Revisor</th>
          <th scope="col">Ação</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="suggestions" v-for="(suggestion, index) in suggestions" :key="index">
          <td>
            <a :href="'/photos/' + suggestion.photo[0].id" target="_blank">
              <img class="suggestion_photo" :src="'/arquigrafia-images/'+ suggestion.photo[0].id +'_home.jpg'"/>
            </a>
          </td>
          <td>{{ suggestion.photo[0].name }}</td>
          <td>{{attributeNames[suggestion.attribute_type].name}}</td>
          <td>{{suggestion.photo[0][attributeNames[suggestion.attribute_type].field] }}</td>
          <td>{{ suggestion.text }}</td>
          <td><a :href="'/users/'+ suggestion.user[0].id" target="_blank">{{ suggestion.user[0].name }}</a></td>
          <td>
            <div class="suggestion-button thumbs-up thumbs-link" data-id="295" v-on:click="action(1, suggestion.photo[0].id, attributeNames[suggestion.attribute_type].field, suggestion.id, suggestion.text)">
              <!-- Form for THUMBS UP -->
                <span>Aceitar</span>
            </div>

          </td>
          <td>
            <div class="suggestion-button thumbs-down thumbs-link" data-id="295" v-on:click="action(0, suggestion.photo[0].id, attributeNames[suggestion.attribute_type].field, suggestion.id)">
              <!-- Form for THUMBS DOWN -->
                <span>Rejeitar</span>

            </div>
          </td>
        </tr>
      </tbody>
    </div>
  </div>




</template>

<script>

export default {
  props: ['auth', 'suggestions'],
  data () {
    return {
      // suggestions: this.$props.reviews,
      attributeNames: {
        1: {'name':'Cidade', 'field':'city'},
        2: {'name':'País', 'field':'country'},
        3: {'name':'Descrição', 'field':'description'},
        4: {'name':'Bairro', 'field':'district'},
        5: {'name':'Autor da imagem', 'field':'imageAuthor'},
        6: {'name':'Estado', 'field':'state'},
        7: {'name':'Rua', 'field':'street'},
        8: {'name':'Nome', 'field':'name'},
        9: {'name':'Autor do projeto', 'field':'authors'},
        10: {'name':'Data da obra', 'field':'workDate'}
      }
    }
  },
  methods: {
    action(option, id, field, suggestion_id, value) {
      let formData = new FormData()
      formData.append('user_id', this.$props.auth._id)
      formData.append('option', option)
      formData.append('field', field)
      formData.append('suggestion_id', suggestion_id)
      formData.append('value', value)


      window.axios.post('/api/suggestions/' + id + '/action', formData).then(response => {

      }).catch(err => {

      });
    },
  },
  mounted () {
  }
};

</script>
