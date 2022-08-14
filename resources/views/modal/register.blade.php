@extends('layouts.default')

@section('content')



  <div class="container">

    <div id="registration">
    	<div class="three columns offset-by-four">

    	</div>	
    	<!-- <div class="container">
        <div class="twelve columns">
          <div class="message">
          	<strong>Cadastro realizado com sucesso!</strong><br>
      		Em instantes, você receberá um e-mail para ativar a sua conta.
          </div>
        </div>
      </div> -->


          
          	<div class="four columns offset-by-four">
          		 <br><br> <br><br>
          		 @if($msgType == "sendEmail")
          		 <div class="message">
          			<p><strong>Cadastro realizado com sucesso!</strong><br><br>
      					Em instantes, você receberá um e-mail para ativar a sua conta.</p>

      			</div>
      			@else      			
      			<div class="message">
          			<p><strong>Código de verificação incorreto!</strong><br><br>
      					Por favor, verifique no seu e-mail o link enviado a você após seu registro e
      					clique de novo, para ativar a sua conta.
      				</p>
      				<p>Se você ainda tiver problemas para ativar sua conta,
      				 envie um e-mail para: arquigrafiabr@gmail.com </p>
      			</div>
      			@endif
      		   <p>&nbsp;</p> 
               <p>&nbsp;</p> 
               <p>&nbsp;</p> 
          </div>
       <br>
   
      
      
    </div>
    
  </div>
    
@stop