import { createStore } from 'vuex'
import mutations from "./mutations";
import actions from "./actions";
import state from "./state";

const store = createStore({
  state,
  mutations,
  actions,
});

export default store;
