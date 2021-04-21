export function Required(text) {
    return value => !!value || text;
}

export function VerifyEmail(text) {
    return value =>
        /(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/.test(
            value
        ) || text;
}

export function VerifyUsername(text) {
    return value => /^[a-z0-9_-]{3,15}$/.test(value) || text;
}

export function ValueLength(length, field) {
    return value =>
        (value ? value.length >= length : false) ||
        `${field} نباید کوچکتر از ${length} کاراکتر باشد`;
}

export function ValueLengthNullable(length, field) {
    return value => {
        if (!value) {
            return true;
        }
        return (
            (value ? value.length >= length : false) ||
            `${field} نباید کوچکتر از ${length} کاراکتر باشد`
        );
    };
}
