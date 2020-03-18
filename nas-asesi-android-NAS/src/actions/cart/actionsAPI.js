import actionsReducer from './actionsReducer';

const actionsAPI = {};

actionsAPI.gotoCart = (data, callback) => dispatch => {
  return dispatch(actionsReducer.goto(data));
};

actionsAPI.getCart = (data, callback) => dispatch => {
  return dispatch(actionsReducer.get(data));
};

actionsAPI.addCart = (data, callback) => dispatch => {
  return dispatch(actionsReducer.add(data));
};

actionsAPI.updateCart = (data, callback) => dispatch => {
  return dispatch(actionsReducer.update(data));
};

actionsAPI.removeCart = (data, callback) => dispatch => {
  return dispatch(actionsReducer.remove(data));
};

export default actionsAPI;
