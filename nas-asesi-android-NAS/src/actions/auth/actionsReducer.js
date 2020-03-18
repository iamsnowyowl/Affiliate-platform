import constants from "../../constants/constants";

const actionsReducer = {};

actionsReducer.login = data => ({
  type: constants.LOGIN,
  data: data
});

actionsReducer.logout = () => ({
  type: constants.LOGOUT
});

actionsReducer.upn = data => ({
  type: constants.UPN,
  data: data
});

actionsReducer.permission = data => ({
  type: constants.PERMISSION,
  data: { permission: data }
});

export default actionsReducer;
