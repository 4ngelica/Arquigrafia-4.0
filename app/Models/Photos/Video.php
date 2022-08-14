<?php

use modules\draft\traits\DraftingTrait;
use lib\date\Date;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

use modules\gamification\traits\LikableGamificationTrait;
use Illuminate\Database\Eloquent\Collection as Collection;
use modules\institutions\models\Institution;
use modules\collaborative\models\Like as Like;
use modules\evaluations\models\Evaluation as Evaluation;

class Video extends Eloquent {

	use DraftingTrait;
	use SoftDeletingTrait;
	use LikableGamificationTrait;

	protected $softDelete = true;

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'aditionalImageComments',
		'allowCommercialUses',
		'allowModifications',
		'cataloguingTime',
		'characterization',
		'city',
		'collection',
		'country',
		'dataCriacao',
		'dataUpload',
		'description',
		'district',
		'imageAuthor',
		'name',
		'nome_arquivo',
		'state',
		'street',
		'tombo',
		'user_id',
		'url',
		'workAuthor',
		'workdate',
	];

	static $allowModificationsList = [
		'YES' => ['Sim', ''],
		'YES_SA' => ['Sim, contanto que os outros compartilhem de forma semelhante', '-sa'],
		'NO' => ['Não', '-nd']
	];

	static $allowCommercialUsesList = [
		'YES' => ['Sim', ''],
		'NO' => ['Não', '-nc']
	];

	private static	$information_questions = [
		'city' => 'Deseja adicionar a cidade da obra?',
		'country' => 'Deseja adicionar o país da obra?',
		'dataCriacao' => 'Qual é a data desta imagem?',
		'description' => 'Deseja adicionar uma descrição para a imagem?',
		'district' => 'Qual é o bairro da obra?',
		'imageAuthor' => 'Quem é o autor desta imagem?',
		'name' => 'Qual é o nome desta obra?',
		'state' => 'Qual é o Estado desta arquitetura?',
		'street' => 'Qual é a rua desta obra?',
		'workAuthor' => 'Quem é o autor da obra?',
		'workdate' => 'Quando foi construída a obra?'
	];

	protected $date;

	public function __construct($attributes = array(), Date $date = null) {
		parent::__construct($attributes);
		$this->date = $date ?: new Date;
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function institution()
	{
		return $this->belongsTo('modules\institutions\models\Institution');
	}

	public function tags()
	{
		return $this->belongsToMany('modules\collaborative\models\Tag', 'tag_assignments');
	}

	public function authors()
	{
		return $this->belongsToMany('Author', 'photo_author');
	}

	public function comments()
	{
		return $this->hasMany('modules\collaborative\models\Comment');
	}

	public function albums()
	{
		return $this->belongsToMany('Album', 'album_elements');
	}

	public function evaluations()
	{
		return $this->hasMany('modules\evaluations\models\Evaluation');
	}

	public function evaluators()
	{
		return $this->belongsToMany('User', 'binomial_evaluation');
	}

	public function hasInstitution() {
		return ! is_null ($this->institution_id);
	}
}