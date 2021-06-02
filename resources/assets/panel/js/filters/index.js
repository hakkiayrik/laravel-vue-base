/**
 * Show plural label if time is plural number
 * @param {number} time
 * @param {string} label
 * @return {string}
 */
function pluralize(time, label) {
	if (time === 1) {
		return time + label
	}
	return time + label + 's'
}

/**
 * @param {number} time
 */
export function timeAgo(time) {
	const between = Date.now() / 1000 - Number(time)
	if (between < 3600) {
		return pluralize(~~(between / 60), ' minute')
	} else if (between < 86400) {
		return pluralize(~~(between / 3600), ' hour')
	} else {
		return pluralize(~~(between / 86400), ' day')
	}
}

/**
 *
 * @param str
 * @returns {string}
 */
export function convertToSlug(str) {
	str = str.replace(/^\s+|\s+$/g, ''); // trim
	str = str.toLowerCase();

	// remove accents, swap ñ for n, etc
	var from = "àáäâèéëêıìíïîòóöôùúüûñçşğ·/_,:;";
	var to = "aaaaeeeeiiiiioooouuuuncsg------";
	for (var i = 0, l = from.length; i < l; i++) {
		str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
	}

	str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		.replace(/\s+/g, '-') // collapse whitespace and replace by -
		.replace(/-+/g, '-'); // collapse dashes

	return str;
}

/**
 * Upper case first char
 * @param {String} string
 */
export function uppercaseFirst(string) {
	return string.charAt(0).toUpperCase() + string.slice(1)
}
