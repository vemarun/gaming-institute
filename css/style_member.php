<?php header ("Content-type: text/css");require '../steamauth/steamauth.php';?>
@import url('https://fonts.googleapis.com/css?family=Francois+One');
* {
	margin: 0;
	padding: 0;
	font-family: 'Francois One', sans-serif;
}

body {
	background-image: url(../images/bg.gif);
	background-attachment: fixed;
	background-size: cover;
	background-repeat: no-repeat;
}

h2 {
	font-size: 250%;
	font-weight: 700;
	text-align: center;
	padding-top: 2%;
}

h3 {
	font-size: 175%;
	line-height: 155%;
	padding: 5%, 0;
	font-weight: 400;
}

p {
	font-size: 160%;
	line-height: 150%;
	padding: 3%;
	text-indent: 2%;
	text-align: justify;
}

img {
	max-width: 100%;
	height: auto;
	width: auto;
	margin-bottom: -4%;
}

header {
	background-color: #6991AC;
	width: 100%;
	height: 86px;
}

#header-inner {}

#logo {
	margin: 20px;
	float: left;
	width: 200px;
	height: 60px;
	background: url(../images/logo.png) no-repeat center;
}


/*---start nav--*/

nav {
	float: right;
	padding: 25 20 0 0;
}

#menu-icon {
	display: hidden;
	width: 40px;
	height: 40px;
	background: url(../images/menu2.png) center;
}

ul {
	list-style-type: none;
}

nav ul li {
	font-size: 120%;
	display: inline-block;
	float: left;
	padding: 8px;
}

nav ul li a {
	color: #f5f5f5;
	text-decoration: none;
}

nav ul li a:hover {
	color: #c3d7d5;
}

.current {
	color: #c3d7d5;
}

#discord {
	height: 60px;
	width: 60px;
}

#discord:hover {
	filter: grayscale(.8) invert(1);
	-webkit-animation: spin 1s ease-in 1 alternate;
	-moz-animation: spin 1s ease-in 1 alternate;
	animation: spin 1s ease-in 1 alternate;
}

#steam {
	height: 60px;
	width: 60px;
}

#steam:hover {
	filter: grayscale(.8) invert(1);
	-webkit-animation: spin 1s ease-in 1 alternate;
	-moz-animation: spin 1s ease-in 1 alternate;
	animation: spin 1s ease-in 1 alternate;
}

@-moz-keyframes spin {
	100% {
		-moz-transform: rotate(20deg);
	}
}

@-webkit-keyframes spin {
	100% {
		-webkit-transform: rotate(20deg);
	}
}

@keyframes spin {
	100% {
		-webkit-transform: rotate(20deg);
		transform: rotate(20deg);
	}
}

.member_detail {
	background-image: url(../images/bg.gif);
	background-size: cover;
	background-repeat: no-repeat
}


/*---end nav--*/


/**.banner {
	width: 100%;
	background-color: #6991AC;
	background-image: url(../images/bg.gif);
	background-size: cover;
	background-blend-mode: multiply;
	height: 618px;
	background-attachment: fixed;
} **/

.banner-inner {
	max-width: 1100px;
	margin: 0 auto;
}

#banner-img {
	position: relative;
	opacity: .2;
	filter: blur(2);
	max-height: 700px;
	overflow-y: hidden;
	max-width: 100%;
}

.banner-head {
	position: absolute;
	top: 200px;
	right: 0%;
	width: 100%;
	color: #f0f0f0;
}

.one-fourth {
	width: 25%;
	float: left;
	text-align: center;
}

#matches {
	background-color: #f1aa90;
	color: #f0f0f0;
}

#results {
	background-color: #beb9ad;
	color: #f0f0f0;
}

#reg {
	background-color: #aadcd2;
	color: #f0f0f0;
}

#upload {
	background-color: #a2b2c1;
	color: #f0f0f0;
}

.one-fourth a {
	color: white;
	text-decoration: none;
}

.one-fourth a:hover {
	color: white;
	text-decoration: none;
}

.one-fourth i {
	color: #f0f0f0;
	font-size: 500%;
	padding: 13% 0 4% 0;
}


/**#up_matches {
	color: white;
	background: url(../images/bg.gif);
	background-attachment: fixed;
	background-size: cover;
	background-color: #6ca2c6;
	padding-top: 200px;
} **/

.match_header {
	border: 2px white solid;
	display: inline;
	border-radius: 15px;
}

#match_head {
	padding-top: 50px;
}

#list_matches {
	font-size: 150%;
	padding-top: 40px;
	padding-left: 20%;
	overflow: hidden;
}

#icons {
	text-align: center;
	height: 200px;
	padding-top: 50px;
}

.icon_links {
	padding-left: 20px;
	display: inline;
}

.icon_header {
	border: 2px white solid;
	display: inline;
	border-radius: 15px;
	color: white;
}

#icon_id {
	padding-top: 40px;
}

@media screen and (max-width:768px) {
	h2 {
		font-size: 150%;
	}
	h3 {
		font-size: 125%;
	}
	p {
		font-size: 120%;
	}
	header {
		position: absolute;
	}
	#logo {
		margin: 15px 0 20 -25px;
		background: url(../images/logo.png) no repeat center;
	}
	#menu-icon {
		display: inline-block;
	}
	nav ul,
	nav:active ul {
		display: none;
		z-index: 1000;
		position: absolute;
		padding: 20px;
		background: #6991AC;
		right: 20px;
		top: 60px;
		border: 1px solid #fff;
		border-radius: 2px 0 2px 2px;
		width: 50%;
	}
	nav:hover ul {
		display: block;
	}
	nav li {
		text-align: center;
		width: 100%;
		padding: 10px 0;
	}
	.banner {
		padding-top: 86px;
	}
	.one-fourth {
		float: left;
		width: 100%;
	}
	.one-fourth i {
		font-size: 350%;
		padding: 4% 0 1% 0;
	}
	.banner-head {
		position: absolute;
		top: 100px;
		right: 0%;
		width: 100%;
	}
}

.navbar-fixed {
	top: 0;
	position: fixed;
	width: 100%;
	background-image: url(../images/bg.gif);
	z-index: 100;
	background-size: cover;
}

#login {
	padding-left: 50%;
}


/* The container <div> - needed to position the dropdown content */

.dropdown {
	position: relative;
	display: inline-block;
}


/* Dropdown Content (Hidden by Default) */

.dropdown-content {
	display: none;
	position: absolute;
	background-color: #6991AC;
	min-width: 120px;
	box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
	z-index: 1;
}


/* Links inside the dropdown */

.dropdown-content .drop {
	color: black;
	padding: 12px 16px;
	text-decoration: none;
	display: block;
}


/* Change color of dropdown links on hover */

.dropdown-content .drop:hover {
	background-color: #f1f1f1
}


/* Show the dropdown menu on hover */

.dropdown:hover .dropdown-content {
	display: block;
}


/* Change the background color of the dropdown button when the dropdown content is shown */

.dropdown:hover .dropbtn {
	background-color: #3e8e41;
}

#clan_image {
	height: 70px;
	width: 70px;
}

#pro_name {
	border-left: thin solid black;
	width: 50%;
	margin: 0 auto;
}


#level {
	display: inline-flex;
	padding-top: 10px;
	width: 50%;
	margin: 0 auto;
}

#member_menu {
	margin-top: 100px;
	width: 100%;
}

.block {
	height: 180px;
	width: 200px;
	font-size: 20px;
	text-align: center;
	float: left;
}

.block a {
	color: black;
}

.block a:hover {
	color: white;
	text-decoration:none;
}

.block i {
	font-size: 300%;
	padding-top:25px;
}

#create_team {
	background-color: rgba(0, 0, 0, 0.35);
	margin-left: 20%;
}

#sch_match {
	background-color: rgba(0, 0, 0, 0.35);
}

#join_clans {
	background-color: rgba(0, 0, 0, 0.35);
}

#view_clans {
	background-color: rgba(0, 0, 0, 0.35);
}

#stat {
	margin-top: 350px;
	vertical-align: bottom;
	text-align: center;
	background-color: antiquewhite;
}

#user_clan td {
	background-color: lightblue;
	font-size: 130%;
}

.sch_content {
	background-image: url(../images/back.jpg);
	background-size: cover;
	text-align: center;
	color: white;
	width: 100%;
	height: 640px;
}

#trans {
	background-color: rgba(0, 0, 0, 0.65);
	position: absolute;
	height: auto;
	width: 50%;
	left: 25%;
	color: black;
	border-radius: 10px;
}

#match_table {
	height: 100vh;
	width: 100%;
	background: linear-gradient(to right, #79c682, #7bcdd7);
	color: white;
	display: block;
	overflow: hidden;
}

#match_table a {
	color: white;
	text-decoration: none;
}

th {
	background-color: #7bcdd0;
	border: none !important;
}

td {
	border: none !important;
}

#footer {
	clear: both;
	bottom: 0;
}

.footer-block {
	padding-left: 50px;
	height: 200px;
	color: white;
	font-size: 1.2em;
}

.footer-column {
	float: left;
	margin: 0 auto;
	padding-left: 20%;
}

.footer-column a:hover {
	text-decoration: none;
	color: white;
}

#footer2 {
	clear: both;
	background-color: #1a3447;
	height: 150px;
	widows: 100%;
	color: white;
	font-size: .8em;
}

#content {
	width: 600px;
	height: 700px;
	background-color: rgba(0, 0, 0, 0.35);
	border-radius: 15px;
	box-shadow: 0px 0px 8px black;
	margin: 0 auto;
	text-align: center;
	color: white;
}

#head {
	width: 100%;
	height: 275px;
	background: url(../images/bg.gif);
	background-position: right bottom;
	background-size: cover;
	border-radius: 15px 15px 0 0;
}

#profile_pic {
	width: 150px;
	height: 150px;
	margin-left: 225px;
	margin-top: -45px;
	background: url(<?php echo $_SESSION['steam_avatarfull']; ?>);
	background-position: left top;
	background-size: cover;
	border-radius: 100px;
	border: 5px solid white;
	box-shadow: 0px 0px 8px black;
	position: absolute;
}

#content h1 {
	position: absolute;
	width: 400px;
	margin-left: 100px;
	margin-top: 150px;
	font-size: 50px;
}

#content p {
	font-family: "icons-social";
	color: #cecece;
	font-size: 50px;
}

#content ul {
	margin-top: 180px;
}

#content ul li {
	display: inline-block;
	margin: 0px 40px;
	-webkit-transition: all 0.5s;
}

#content ul li:hover {
	cursor: pointer;
	-webkit-transform: scale(1.2);
	-webkit-transition: all 0.5s;
}

#content ul li:hover>p {
	text-shadow: 0px 0px 5px #c7acac;
	color: white;
	-webkit-transition: all 0.5s;
}
#profileimgid{
height:60px;
width:60px;
}
