/**
* This is the content of the Moderation Tab
*/

<script>
import { mapActions } from 'vuex'
import FormTable from '../../components/general/FormTable.vue';
import ItemAcceptRejectSuggestion from '../../components/contributions/ItemAcceptRejectSuggestion.vue';
import store from './store';

export default {
  name: 'ModerationContent',
  store,
  props: {
    active: {
      type: Boolean,
      default: false,
      required: true,
    },
  },
  components: {
    FormTable,
    ItemAcceptRejectSuggestion,
  },
  methods: mapActions([
    'acceptRejectSuggestion',
    'createChat',
  ]),
}
</script>

<template>
  <div
    class="tab"
    v-bind:class="{ active: active }"
  >
    <FormTable
      :columns="['Foto', 'Nome da Foto', 'Campo', 'Dado Atual', 'Sugestão', 'Revisor', 'Ação']"
    >
      <ItemAcceptRejectSuggestion
        :photoID="1"
        photoName="Teste"
        fieldName="Nome"
        :user="{ id: 1, name: 'Usuario' }"
        currentFieldData="Nome Atual"
        suggestedData="Nome Sugerido"
        :suggestionID="10"
        :handleAccept="suggestionID => acceptRejectSuggestion({ suggestionID, type: 'accept' })"
        :handleReject="suggestionID => acceptRejectSuggestion({ suggestionID, type: 'reject' })"
        :handleCreateChat="userID => createChat({ userID })"
      />
    </FormTable>
  </div>
</template>
