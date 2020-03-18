import axios from "axios";
import constants from "../../constants/constants";
import path from "../../constants/path";
import actionsReducer from "./actionsReducer";

const actionsAPI = {};

actionsAPI.getAsesi = (data, callback) => dispatch => {
  return dispatch(actionsReducer.get(data));
};

actionsAPI.addAsesi = (data, callback) => dispatch => {
  return dispatch(actionsReducer.add(data));
};

actionsAPI.updateAsesi = (data, callback) => dispatch => {
  return dispatch(actionsReducer.update(data));
};

actionsAPI.removeAsesi = (data, callback) => dispatch => {
  return dispatch(actionsReducer.remove(data));
};

export default actionsAPI;
