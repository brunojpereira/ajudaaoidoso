<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
 <script src="includes/jquery-3.2.1.min.js"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="bootstrap/css/font-awesome.min.css" rel="stylesheet">
	<link href="bootstrap/css/w3.css" rel="stylesheet">
	<title>Document</title>
	
	<style>
		* {margin:0; padding:0;
			font-family: arial;
		}
		body {background: #ffffff;}
		#ola {
			font-size: 50px;
			color: #fff055;
			text-align: center;
			text-shadow: -1px -1px 0 #ccc; margin: 50px 0 30px}
		#transcription {
			width: 50%;
			border-radius: 5px;
			height: 100px;
			margin: 0 auto;
			display: block;
			font-size: 16px;
			padding: 11px;
			color: #666;
			background: #fff;
		}
		#gravar {
			border: none;
			background: transparent;
			font-size: 40px;
			color: #fff;
			width: 100%;
			outline-color: transparent;
			padding-top: 20px;
		}
		#gravar i { cursor: pointer;
		width: 80px;
		height: 80px;
		line-height: 80px;
		border-radius: 100%;
		box-shadow: inset 0 0 0 transparent;
		-webkit-transition: all 0.5s linear;
		-moz-transition: all 0.5s linear;
		-ms-transition: all 0.5s linear;
		-o-transition: all 0.5s linear;
		transition: all 0.5s linear;
	margin-bottom: 15px;}
		#gravar i:hover {
			box-shadow: inset 0 0 20px #fff;
		}
		#gravar i:active {box-shadow: inset 0 0 20px 100px #fff; color:#E81D62;  }
		#status {color: #fff; text-align: center; display: block}
		#status span {font-weight: bold;}
		#status span.gravando {color: rgb(70, 232, 29);}
		#status span.pausado {color: rgb(173, 115, 229);}
		.hidden {display: none;}
		#ws-unsupported {
				font-size: 60px;
				position: fixed;
				width: 140%;
				text-align: center;
				height: 100px;
				background: red;
				color: #000;
				-webkit-transform: rotateZ(-30deg);
				-ms-transform: rotateZ(-30deg);
				-o-transform: rotateZ(-30deg);
				box-shadow: 0 0 7px rgba(0, 0, 0, 0.67);
				transform: rotateZ(-30deg);
				top: 190px;
		}
	#rect {
		display: block;
		margin: 30px auto;
		background: #fff;
		padding: 10px;
		border: none;
		font-size: 18px;
		border-radius: 5px;
		color: rgb(232, 29, 98);
		font-family: arial;
	}
#rect2 {
		display: block;
		margin: 30px auto;
		background: #fff;
		padding: 10px;
		border: none;
		font-size: 18px;
		border-radius: 5px;
		color: rgb(232, 29, 98);
		font-family: arial;
	}
	.centralizado{
		margin-left:50%;
		margin-right:50%;
	}
#cont_t {	
			width: 50%;
			margin: auto;
			font-size: 18px;
			text-align: center;
			border: solid 1px;}
.lin{font-size: 14px;}
	
	</style>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
		<p id="ola">Ajuda a Idoso</p>
		<!-- <div id="transcription"></div> -->
		
		
 
		<div id='botao'> <button class=" w3-circle w3-teal w3-button centralizado" id="rect"><i class="fa fa-microphone fa-3x"></i></button> </div>
		
		<!-- <button id="teste">Teste</button> -->
		<div id="cont_t">
			<table class='table'id='tabela'>	
			</table>
		</div>
 
	    <!-- <span id="unsupported" class="hidden">API not supported</span> -->
 
    <script type="text/javascript">
	/* teste de ajax 
	$(document).ready(function(){
		$("#teste").click(function(){
			var nome = $("#transcription").html();
		  $.ajax({
			url: "funcao_ajax.php", 
			type: "POST",             
			data: "e="+nome,         
			success: function(data)   
			{
			  alert(data);
			}
		  });
		})
	});*/
	
      // Test browser support
      window.SpeechRecognition = window.SpeechRecognition       ||
                                 window.webkitSpeechRecognition ||
                                 null;
 
		//caso não suporte esta API DE VOZ                              
		if (window.SpeechRecognition === null) {
	    	document.getElementById('unsupported').classList.remove('hidden');
        }else {
            var recognizer = new window.SpeechRecognition();
            //var transcription = document.getElementById("transcription");
        	//Para o reconhecedor de voz, não parar de ouvir, mesmo que tenha pausas no usuario
        	recognizer.continuous = true
        	recognizer.onresult = function(event){
        		//transcription.textContent = "";
        		for (var i = event.resultIndex; i < event.results.length; i++) {
        			if(event.results[i].isFinal){
        				//transcription.textContent = event.results[i][0].transcript + "KKKK";						
						$.ajax({
							url: "funcao_ajax.php", 
							type: "POST",             
							data: "e="+event.results[i][0].transcript,         
							success: function(data)   
							{
							  $("#botao").html("<a href='index.php' class='lin'><button class='w3-circle w3-teal w3-button centralizado' id='rect'><i class='fa fa-microphone fa-3x'> Fazer nova busca</i></a>");
							  $("#tabela").html(data);
							}
						  });
        			}else{
		            	//transcription.textContent += event.results[i][0].transcript + "LL";
						$.ajax({
							url: "funcao_ajax.php", 
							type: "POST",             
							data: "e="+event.results[i][0].transcript,         
							success: function(data)   
							{
							  alert(data);
							}
						  });
        			}
        		}
        	}
        	document.querySelector("#rect").addEventListener("click",function(){
        		try {
		            recognizer.start();
					$("#botao").html("<button id='rect2'>Ouvindo</button>");
		          } catch(ex) {
		          	alert("error: "+ex.message);
		          }
        	});
        }
    </script>
</body>
</html>