<?php

namespace App\Traits\Drafts;

use App\Models\Drafts\DraftingScope;

trait DraftingTrait {

  public static function bootDraftingTrait() {
    static::addGlobalScope(new DraftingScope);
  }

  public static function onlyDrafts()
  {
    $instance = new static;

    $column = $instance->getQualifiedDraftColumn();

    return $instance->newQueryWithoutScope(new DraftingScope)->whereNotNull($column);
  }

  public function getQualifiedDraftColumn() {
    return $this->getTable() . '.draft';
  }

  public function draft() {
    $this->draft = $this->freshTimestampString();
  }

  public function removeDraft() {
    $this->draft = null;
  }

}
