	var body = $("#newArticle iframe")[0].contentDocument.getElementsByTagName("body")[0];
	body.innerHTML = "xxxxx";

	var editBody = $("#newArticle iframe").contents().find("body");
	editBody.html(content);
