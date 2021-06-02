export default {
	methods: {
		convertToSlug(str) {
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
		},

		createFormData(formData, key, value) {

			if (typeof value === 'object' || Array.isArray(value)) {
				for (const i in value) {
					this.createFormData(formData, key + '[' + i + ']', value[i])
				}
			} else {
				formData.append(key, value != null ? value : '')
			}

			return formData
		},

		buildFormData(formData, data, parentKey) {
			if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
				Object.keys(data).forEach(key => {
					this.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
				});
			} else {
				const value = data == null ? '' : data;

				formData.append(parentKey, value);
			}
		}
	}
}
