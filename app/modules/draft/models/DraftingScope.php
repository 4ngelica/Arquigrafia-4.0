<?php

namespace App\modules\draft\models;

use \Illuminate\Database\Eloquent\Builder as Builder;

class DraftingScope implements \Illuminate\Database\Eloquent\ScopeInterface {

  public function apply(Builder $builder) {
    $column = $builder->getModel()->getQualifiedDraftColumn();
    $builder->whereNull($column);
    $this->addOnlyDrafts($builder);
  }

  public function remove(Builder $builder) {
    $column = $builder->getModel()->getQualifiedDraftColumn();
    $query = $builder->getQuery();
    foreach ((array) $query->wheres as $key => $where) {
      if ($this->isDraftingConstraint($where, $column)) {
        unset($query->wheres[$key]);
        $query->wheres = array_values($query->wheres);
      }
    }
  }

  protected function isDraftingConstraint(array $where, $column) {
    return $where['type'] == 'Null' && $where['column'] == $column;
  }

  protected function addOnlyDrafts(Builder $builder)
  {
    $builder->macro('onlyDrafts', function(Builder $builder)
    {
      $this->remove($builder);

      $builder->getQuery()->whereNotNull($builder->getModel()->getQualifiedDraftColumn());

      return $builder;
    });
  }

}
