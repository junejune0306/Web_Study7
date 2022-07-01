const express = require("express");
const ejs = require("ejs");
const path = require("path");
const app = express();
const conn = require("./db.js");
const fs = require('fs');

app.listen(8009, () => {
	var dir = './upload';
	if (!fs.existsSync(dir)) fs.mkdirSync(dir);
	console.log("listening on port 8009");
});

app.use('/', require('./routes/main'))
app.use(express.urlencoded({ extended: false }));
app.use(express.static(path.join(__dirname, 'views')));
app.set("views", path.join(__dirname, "views"));
app.set("view engine", "ejs");