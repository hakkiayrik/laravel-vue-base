import Vue from 'vue';
import {configure, extend, ValidationObserver, ValidationProvider} from "vee-validate";
import i18n from "../lang";
import * as rules from 'vee-validate/dist/rules';
import isURL from "validator/es/lib/isURL";

configure({
    defaultMessage: (field, values) => {
        // override the field name.
        values._field_ = i18n.t(`fields.${field}`);
        return i18n.t(`validation.${values._rule_}`, values);
    }
});


Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});


// with typescript
for (let [rule, validation] of Object.entries(rules)) {
    extend(rule, {
        ...validation
    });
}

extend('required_with', {
    params: [{name: 'target', isTarget: true}],
    validate(value, {target}) {
        const otherFieldHasValue = target && String(target).trim().length > 0;
        if (otherFieldHasValue) {
            const valid = value && String(value).trim().length > 0;
            return {
                valid: valid,
                required: otherFieldHasValue,
            };
        }
        return {
            valid: true,
            required: true,
        };
    },
    message: (_, target) => i18n.t(`validations.required_with`, {field: i18n.t(`fields.${_}`), target: i18n.t(`fields.${target.target}`)}),
    computesRequired: true,
})


extend('url', {
    validate(value) {
        return isURL(value, {
            require_tld: false,
            require_protocol: true,
        })
    },
    message: (_) => i18n.t(`validations.url`, {field: i18n.t(`fields.${_}`)}),
})

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
