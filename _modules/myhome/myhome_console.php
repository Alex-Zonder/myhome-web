<style>
#Return_Holder{
	width:calc(100% - 10px);
	height:calc(100% - 31px);
	overflow:auto;
	padding: 0 5px 0 5px;
	font-size: 13px;
}
#Command_Holder{
	width:calc(100%);
	height:calc(31px);
	overflow:hidden;
	border-top: 1px solid gray;
}

#Command_Input{width:80%; float:left;}
#Command_Input>input[type=text]{width:100%;background: var(--color_1);border: 0px;text-align:left;font-size: 14px;padding: 0 5px 0 5px; height:100%;border-radius: 0;}

#Command_Send{width:20%;background:  var(--color_2);border: 0px;text-align:center;cursor: pointer;height:100%;float: left;padding: 5px 0 0 0; -webkit-user-select: none;}
#Command_Send:hover{background: var(--color_1);border-left: 1px solid gray;width: calc(20% - 1px);}
</style>
