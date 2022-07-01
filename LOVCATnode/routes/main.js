var express = require('express');
var router = express.Router();
var multer = require('multer');
const conn = require("../db.js");
const path = require("path");
const ejs = require("ejs");

var appRoot = process.env.PWD;
var uploadPath = path.join(appRoot,"upload");

var storage = multer.diskStorage({
    destination(req, file, cb) {
        cb(null, 'upload/');
    },
    filename(req, file, cb) {
        cb(null, `${Date.now()}__${file.originalname}`);
    },
});
var upload = multer({ dest: 'upload/' });
var uploadWithOriginalFilename = multer({ storage: storage });

router.get("/", (req, res) => {
	var sql = "SELECT * FROM guestBook ORDER BY seq DESC;";
	conn.query(sql, (err, rows) => {
		if (err) console.log("query is not excuted. select fail!\n" + err);
		else {
			for (var i of rows) {
				i.time.setHours(i.time.getHours() + 9);
				i.time = i.time.getFullYear()+"."+(i.time.getMonth()+1)+"."+i.time.getDate()+" "+i.time.getHours()+":"+i.time.getMinutes()+":"+i.time.getSeconds();
			}
			res.render("guestBook.ejs", { list: rows });
		}
	});
});

router.get('/download/:file', (req, res) => {
    const file = `${uploadPath}/`+req.params.file;
    console.log(file);
    res.download(file);
})

router.post('/', uploadWithOriginalFilename.single('attachment'), (req, res) => {
    console.log(req.file);
	console.log(req.body);
	var sql = "insert into guestBook (name, text, file) values (?, ?, ?);";
	params = [req.body.name, req.body.text, req.file.filename];
	if (params[0] != '' && params[1] != '') conn.query(sql, params, (err, rows) => {
		if (err) console.log(err);
	});
	res.redirect("/");
});

router.get( "/delete/:seq", (req, res) => {
	var sql = 'delete from guestBook where seq=?';
	var params = [ req.params.seq ];
	conn.query( sql, params, (err, rows) => {
		if (err) console.log("query is not excuted. modify fail!\n" + err);
		else res.redirect("/");
	})
});

router.post( "/modify/:seq", (req, res) => {
	var sql = 'update guestBook set text=? where seq=?';
	var params = [ req.body.text, req.params.seq ];
	conn.query( sql, params, (err, rows) => {
		if (err) console.log("query is not excuted. modify fail!\n" + err);
		else res.redirect("/");
	})
});

module.exports = router;