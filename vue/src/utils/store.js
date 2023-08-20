// const Store = require("electron-store");

// const store = new Store();

const store = {
  set: function(key, value) {
    return sessionStorage.setItem(key, value);
  },
  get: function(key) {
    return sessionStorage.getItem(key);
  },
  delete: function(key) {
    return sessionStorage.removeItem(key);
  }
};

export default store;
