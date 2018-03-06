<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tic Tac Toe</title>
</head>
<script src="../media/js/jquery-3.3.1.min.js"></script>
<style>
#content{
	display: inline-block;            
    border: 1px solid black;
	width:80%;
	margin-left: auto;
	margin-right: auto;
	margin: 0 auto; 
	text-align: center;
}
.row_blk{
	display: inline-block;            
    border: 1px solid black;
    width: 30px;
    height: 30px;
    margin-left: -1px;
    margin-bottom: -1px;
	padding: 0px 5px;
	tex-align:center;
}
#tic-tac-toe {tex-align:center;}
</style>
<script>
$(document).ready(function(){
	$( ".row_blk" ).click(function() {
		if($("#result").html()==""){
			if($(this).html()!="&nbsp;"){
				$("#result1").html("Error : Invalid Move ,You can't select block which is already occupied.");
				return ;
			}
			$("#result1").html("It's Your turn.");
			$(this).html("X");
			$.ajax({
					url: "../index.php?type=makeMove&pos="+$(this).attr("id"),
					cache: false
			}).done(function( resp ) {
			   var obJson=jQuery.parseJSON(resp.replace("&nbsp;",""));	
			   var pos = obJson.pos;
			   var status = obJson.status;
			   var numMovesLeft = obJson.numMovesLeft;
			   if(numMovesLeft>=0 && $("#"+pos).html()=="&nbsp;"){
				  $("#"+pos).html("O");
			   }			   
			   if(status=="O"){
				   $("#result").html("(I)/(Bot)/Computer won.");	
				    $("#result1").html("");
			   }
			   else if(status=="X"){
				   $("#result").html("(You)/Viewer won.");	
				    $("#result1").html("");
			   }
			   else if(numMovesLeft==0){
				   $("#result").html("It's a draw.");	
				    $("#result1").html("");
			   }
			  
			   console.log(resp);
			   //alert(data);
			});
		}
		
	});
	
	$("#viewer").click(function(){
		resetBoard("viewer");
		
		
	});
	
	$("#computer").click(function(){
		resetBoard("computer");
	});
	
});

function resetBoard(player){
	
	$.ajax({
			url: "../index.php?type=refresh&player="+player,
			cache: false
		}).done(function( resp ) {
			   if(player=="computer"){
					var obJson=jQuery.parseJSON(resp);	
					var pos = obJson.pos;
					var status = obJson.status;
					var numMovesLeft = obJson.numMovesLeft;
					$("#"+pos).html("O");	
			   }
				
	});
	
	for(var i=0;i<3; i++){
		for(var j=0;j<3; j++){
			$("#"+i+"x"+j).html("&nbsp;");
		}
	}
	$("#result").html("");
	$("#result1").html("It's Your turn.");
}

</script>
<body>
<div id="content">
<div class="span3 new_span">
<div class="row">
<h1 class="span3">Welcome To "Tic Tac Toe" </h1>
<div id="player_selection">
	<div style="float:left;width:50%" id="viewer"><a href="#">I / Viewer will start </a></div>
	<div style="float:left;width:50%" id="computer"><a href="#">You /Bot/ Computer will start</a></div>
</div>
<div >
<div>
	<div class="row_blk" id="0x0">
	   &nbsp;
	</div>
	<div class="row_blk" id="0x1">
	   &nbsp;
	</div>
	<div class="row_blk" id="0x2">
	   &nbsp;
	</div>
</div>
<div>
	<div class="row_blk" id="1x0">
	   &nbsp;
	</div>
	<div class="row_blk" id="1x1">
	   &nbsp;
	</div>
	<div class="row_blk" id="1x2">
	   &nbsp;
	</div>
</div>
<div>
	<div class="row_blk" id="2x0">
	   &nbsp;
	</div>
	<div class="row_blk" id="2x1">
	   &nbsp;
	</div>
	<div class="row_blk" id="2x2">
	   &nbsp;
	</div>
</div>
</div>
<h1 id="result"></h1>
<h1 id="result1"></h1>
</div>
</div>
</body>
</html>