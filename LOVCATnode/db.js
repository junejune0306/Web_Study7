const mysql = require( "mysql" );

const connection = mysql.createConnection({
	host: 'localhost',
	user: 'junejune0306',
	password: '2022Wnsldj!',
	port: 3306,
	database: 'junejune0306'
});

connection.connect((err) => {
	if (err) console.log(err);
	else console.log( 'Connected!' );
});

module.exports = connection;
