'use strict';

window.ValidatorConst = {
    USER_LOGIN_VALIDATOR: /^[a-z\d]{4,16}$/i,
    USER_NAMES_VALIDATOR: /^[a-zа-я]{2,16}$/i,
    USER_PHONE_VALIDATOR: /^\+\d{2}\(\d{3}\)\d{3}-\d{2}-\d{2}$/,
    USER_PASSWORD_VALIDATOR: /^[a-z_?!^%()\d]{6,30}$/i,
    USER_EMAIL_VALIDATOR: /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i,
};

