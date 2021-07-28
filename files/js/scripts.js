function hide() {
	for(i = 0; i < arguments.length; i++) arguments[i].setAttribute("class", arguments[i].getAttribute("class") + " hidden");
}

function show(){
	for(i = 0; i < arguments.length; i++) arguments[i].setAttribute("class", arguments[i].getAttribute("class").replace("hidden", ''));
}