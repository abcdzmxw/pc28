export default {
  updateToken(state, token) {
    state.token = token;
  },
  updateCurrentUserId(state, userId) {
    state.currentUserId = userId;
  },
  updateCurrentUser(state, user) {
    state.currentUser = user;
  },
  updateCurrentUserMoney(state, money) {
    state.money = money;
  },
  updateActiveList(state, Number) {
    state.activeList = Number;
  },
  updateGroup_id(state, group_id) {
    state.group_id = group_id;
  },
  updateusercrud(state, usercrud) {
    state.usercrud = usercrud;
  },
};
