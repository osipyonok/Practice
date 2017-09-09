<?php
	require_once('functions.php');
?>
<style>
.emojis {
	background: #fff;
    display: inline;
	padding: 5px;
	z-index: 2;
}
.emoji_list {
	position:relative;
	bottom:-50px;
    left:-50px;
    top:-350px;
	background: #fff;
	border-style: outset;
	width: 220px;

    height: 250px;

	z-index: 2;
	overflow-y: scroll;
	display:none;
}
.emoji {
	padding: 5px;
}
.chat_main::-webkit-scrollbar-track {
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	border-radius: 10px;
	background-color: #F5F5F5;
}
.chat_main::-webkit-scrollbar {
	width: 12px;
	background-color: #F5F5F5;
}
.chat_main::-webkit-scrollbar-thumb {
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
	background-color: #D62929;
}
#emoji_list::-webkit-scrollbar-track {
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	border-radius: 10px;
	background-color: #F5F5F5;
}
#emoji_list::-webkit-scrollbar {
	width: 12px;
	background-color: #F5F5F5;
}

#emoji_list::-webkit-scrollbar-thumb {
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
	background-color: #D62929;
}/*
*{
	border: 1px solid red;
}*/
</style>

<form action="" id="chat_form" class="chat_submit" method="post">
	<div>
		<input type="text" id="mess"> 
		<div class='emojis' id='emojis' onclick="Get_Emoji_List();  return false;"><!-- Get_Emoji_List(); -->
			😋
		</div>
		<input type="submit" value="Відіслати" name="send">

		<br>
		
	</div>
</form>

