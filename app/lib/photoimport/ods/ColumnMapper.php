<?php namespace lib\photoimport\ods;

class ColumnMapper {

  protected $column_mapper = array(
    'characterization' => 'caracterizacao',
    'name' => 'nome',
    'country' => 'pais',
    'state' => 'estado',
    'city' => 'cidade',
    'district' => 'bairro',
    'street' => 'rua',
    'collection' => 'colecao',
    'imageAuthor' => 'autor_da_imagem',
    'dataCriacao' => 'data_da_imagem',
    'workAuthor' => 'autor_da_obra',
    'workdate' => 'data_da_obra',
    'description' => 'descricao',
    'aditionalImageComments' => 'observacoes',
    'cataloguingTime' => 'data_de_catalogacao',
    'tombo' => 'tombo'
  );

  public function getMapper() {
    return $this->column_mapper;
  }

  public function transform($attrs) {
    $attributes = array();
    foreach ($this->column_mapper as $column => $mapped_column) {
      $attributes[$column] = $attrs[$mapped_column];
    }
    $this->getPermissions($attrs['licenca'], $attributes);
    $this->getTags($attrs, $attributes);
    return $attributes;
  }

  public function getPermissions($license, &$attributes) {
    $license = trim(str_replace(' ', '', $license));
    $break_pos = strpos($license, ',');
    $break_pos = $break_pos === false ? strpos($license, '-') : $break_pos;
    $attributes['allowCommercialUses'] = substr($license, 0, $break_pos);
    $attributes['allowModifications'] = substr($license, $break_pos + 1);
  }

  public function getTags($attrs, &$attributes) {
    $tags_array = array(
      ( isset($attrs['tags_elementos']) ? $attrs['tags_elementos']: null ),
      ( isset($attrs['tags_materiais']) ? $attrs['tags_materiais']: null ),
      ( isset($attrs['tags_tipologia']) ? $attrs['tags_tipologia']: null )
    );
    $attributes['tags'] = implode(', ', array_filter($tags_array));
  }
}