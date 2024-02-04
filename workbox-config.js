module.exports = {
	globDirectory: 'public/',
	globPatterns: [
		'**/*.{mp3,css,js,old,old2,less,html,ico,eot,svg,ttf,woff,woff2,otf,png,jpeg,jpg,gif,php,txt,json,pdf,config}'
	],
	swDest: 'public/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};