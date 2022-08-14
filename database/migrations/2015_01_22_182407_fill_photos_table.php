<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('photos', function(Blueprint $table)
    {
      /*
      Photo::create(['user_id'=>1,'name'=>'Lorem Ipsum', 'description'=>'Fotografia incrível.', 'nome_arquivo'=>'65_view.jpg','state'=>'SP','street'=>'Rua Castro Alves', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'180_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2,'name'=>'Lorem Ipsum', 'description'=>'Fotografia incrível.', 'nome_arquivo'=>'730_view.jpg','state'=>'SP','street'=>'Rua Castro Alves', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>3 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'809_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Uma fotografia incrível.', 'nome_arquivo'=>'917_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Donec mollis purus sapien', 'description'=>'Mais uma fotografia bela.', 'nome_arquivo'=>'924_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>3 ,'name'=>'Curabitur in sem eget', 'description'=>'Mais uma fotografia fantástica.', 'nome_arquivo'=>'1246_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>3 ,'name'=>'Dolor Amet', 'description'=>'Uma fotografia de arquitetura.', 'nome_arquivo'=>'1267_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Quisque id velit dui', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'1369_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Uma fotografia bela.', 'nome_arquivo'=>'2035_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Quisque id velit dui', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'2488_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Dolor Amet', 'description'=>'Novamente uma fotografia fantástica.', 'nome_arquivo'=>'2522_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'2852_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>3 ,'name'=>'Curabitur in sem eget', 'description'=>'Mais uma fotografia fantástica.', 'nome_arquivo'=>'2954_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Novamente uma fotografia bela.', 'nome_arquivo'=>'3170_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia fantástica.', 'nome_arquivo'=>'3345_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'3452_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Dolor Amet', 'description'=>'Uma fotografia fantástica.', 'nome_arquivo'=>'3589_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>3 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia bela.', 'nome_arquivo'=>'3600_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia fantástica.', 'nome_arquivo'=>'3601_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Novamente uma fotografia incrível.', 'nome_arquivo'=>'3602_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Dolor Amet', 'description'=>'Uma fotografia fantástica.', 'nome_arquivo'=>'3607_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia bela.', 'nome_arquivo'=>'3608_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'3886_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>2 ,'name'=>'Dolor Amet', 'description'=>'Novamente uma fotografia incrível.', 'nome_arquivo'=>'3888_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia bela.', 'nome_arquivo'=>'4579_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>3 ,'name'=>'Dolor Amet', 'description'=>'Mais uma fotografia incrível.', 'nome_arquivo'=>'4785_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>1 ,'name'=>'Dolor Amet', 'description'=>'Novamente uma fantástica fotografia.', 'nome_arquivo'=>'4786_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      Photo::create(['user_id'=>3 ,'name'=>'Dolor Amet', 'description'=>'Uma fotografia incrível.', 'nome_arquivo'=>'4791_view.jpg','state'=>'SP','street'=>'Rua Ponta Porã', 'tombo'=>'2', 'workAuthor'=>'Nome do autor da obra', 'workdate'=>'10-10-10', 'dataUpload'=>'11-11-11', 'dataCriacao'=>'12-12-12', 'country'=>'Brasil', 'collection'=>'Coleção', 'city'=>'São Paulo']);
      */      
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
