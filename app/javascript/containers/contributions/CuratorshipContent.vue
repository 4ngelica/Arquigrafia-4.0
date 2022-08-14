/**
* This is the Curatorship Tab component.
*/

<script>
import { mapActions } from 'vuex';
import FormTable from '../../components/general/FormTable.vue';
import ItemCuratorshipExposition from '../../components/contributions/ItemCuratorshipExposition.vue';
import CreateExpoAction from '../../components/contributions/CreateExpoAction.vue';
import store from './store';

export default {
  name: 'CuratorshipContent',
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
    ItemCuratorshipExposition,
    CreateExpoAction,
  },
  methods: mapActions([
    'editExpo',
    'removeExpo',
    'createExpo',
  ]),
}
</script>

<template>
  <div
    class="tab"
    v-bind:class="{ active: active }"
  >
    <CreateExpoAction
      :handleCreateExpo="createExpo"
    />
    <FormTable
      :columns="['Capa', 'Nome', 'Descrição', 'Ação']"
    >
      <ItemCuratorshipExposition
        imageURL="http://www.vitruvius.com.br/media/images/magazines/grid_9/4b25282ad814_artigas01.jpg"
        :expositionID="1"
        name="FAU USP"
        description="A Faculdade de Arquitetura e Urbanismo da Universidade de São Paulo é responsável pelo ensino de graduação, pós-graduação, pesquisa e extensão de serviços à sociedade nas áreas de Design, Arquitetura e Urbanismo."
        :handleEdit="expoID => editExpo({ expoID })"
        :handleRemove="expoID => removeExpo({ expoID })"
      />
      <ItemCuratorshipExposition
        imageURL="http://www.vitruvius.com.br/media/images/magazines/grid_9/4b25282ad814_artigas01.jpg"
        :expositionID="2"
        name="FAU USP"
        description="A Faculdade de Arquitetura e Urbanismo da Universidade de São Paulo é responsável pelo ensino de graduação, pós-graduação, pesquisa e extensão de serviços à sociedade nas áreas de Design, Arquitetura e Urbanismo."
        :handleEdit="expoID => editExpo({ expoID })"
        :handleRemove="expoID => removeExpo({ expoID })"
      />
    </FormTable>
  </div>
</template>
